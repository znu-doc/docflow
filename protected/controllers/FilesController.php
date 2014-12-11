<?php

class FilesController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/docflow';
  public $defaultAction = 'index';

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
        'accessControl', // perform access control for CRUD operations
        'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules() {
    return array(
        array('deny', // deny all anonymous users
            'users' => array('?'),
        ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('DownloadFile', 'DownloadAll'),
            'users' => array("*"),
        ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('upload','delete','filelist'),
            'users' => array("@"),
        ),
        array('allow', // allow for Admins
            'actions' => array('index'),
            //'roles' => array('Admins', "Root"),
            'users' => array('@'),
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Files::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }
  
  /**
   * Відображення усіх файлів разом з верифікаціями існування.
   * @todo Пізніше
   */
  public function actionIndex(){
    $reqFiles = Yii::app()->request->getParam('Files',null);
    $criteria = new CDbCriteria();
    $model = new Files();
    if (!Yii::app()->user->CheckAccess('showProperties')){
      $criteria->compare('t.UserID',Yii::app()->user->id);
    }
    $criteria->with = array(
      'documentfiles',
      'documentfiles.document',
      'user'
    );
    $linkedDocSQL = 'IF(ISNULL(document.idDocument),"---",'
            . 'CONCAT(document.DocumentName,IF(ISNULL(document.DocumentDescription) '
            . 'OR document.DocumentDescription="","",CONCAT("(",document.DocumentDescription,")"))))';
    if ($reqFiles){
      $model->unsetAttributes();
      $model->linkedDocumentName = $reqFiles['linkedDocumentName'];
      $model->idFile = $reqFiles['idFile'];
      $model->FileTimeStamp = $reqFiles['FileTimeStamp'];
      $criteria->compare('idFile',$model->idFile);
      $criteria->compare('FileTimeStamp',$model->FileTimeStamp,true);
      //$criteria->compare('UserID',$model->UserID,true);
      $criteria->compare($linkedDocSQL,$model->linkedDocumentName, true);
    }
    $criteria->select = array('*',
      new CDbExpression($linkedDocSQL.' AS linkedDocumentName'),
    );
    $criteria->together = true;
    $criteria->group = 't.idFile';
    //$model->file_list = $this->getFileList();
    $data = new CActiveDataProvider($model,
      array(
        'criteria' => $criteria,
        'sort' => array(
          'defaultOrder' => array(
              'idFile' => CSort::SORT_DESC,
          ),
        ),
      )
    );
    $this->layout = '//layouts/docflow';
    $this->render('index',array(
      'model' => $model,
      'data' => $data,
    ));
  }

  /**
   * Метод завантаження (скачування).
   * @param integer $id ID : files.idFile
   * @return bool Result of Yii::app()->getRequest()->sendFile function
   * @throws CHttpException
   */
  public function actionDownloadFile($id) {
    $model = $this->loadModel($id);
    if (!$model){
      throw new CHttpException(404, 'Файл з #'.$id.' не знайдено.');
    }
    $path = $model->folder;
    //визначення шляху, де знаходиться файл
    $file_entity = $path . $model->FileLocation;
    //розширення файлу
    $ext = pathinfo($file_entity, PATHINFO_EXTENSION);
    $valid_name = $model->FileName;
    //отримання зв'язку між файлом як версією документа і документом
    if (!empty($model->documentfiles)){
      $df = $model->documentfiles[0];
      /* @var $df Documentfiles */
      $desc = mb_substr($df->document->DocumentDescription, 0, 40, 'utf-8');
      if ($desc == "") {
        $desc = $df->document->DocumentName;
      }
      //заміна усих "негарних" символів для формування імені файлу
      $valid_name = preg_replace('/[\s]|[\/]|[\\]|[<]|[>]|[\?]|[\}]|[\{]|[\[]|[\]]|[\@]|[\"]|[\']/', 
              '_', $desc) . '.' . $ext;
    }
    if (!is_file($file_entity)) {
      throw new CHttpException(404, 'Помилка: файл ' .
      $model->FileLocation .
      ' не знайдено. Можливо він був видалений або виникла помилка при його завантаженні.');
    }
    $mime = NULL;
    if (!strcasecmp($ext,'pdf')){
      $fname = $valid_name;
      if(($mimeType=CFileHelper::getMimeTypeByExtension($file_entity))===null)
              $mimeType='text/plain';
      header('Pragma: public');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header("Content-type: $mimeType");
      if(ob_get_length()===false)
              header('Content-Length: '.(function_exists('mb_strlen') ? mb_strlen($content,'8bit') : strlen($content)));
      header("Content-Disposition: inline; filename=\"$fname\"");
      header('Content-Transfer-Encoding: binary');
      // clean up the application first because the file downloading could take long time
      // which may cause timeout of some resources (such as DB connection)
      Yii::app()->end(0,false);
      echo file_get_contents($file_entity);
      exit(0);

    }
    return Yii::app()->getRequest()->sendFile($valid_name, file_get_contents($file_entity), $mime);
  }

  /**
   * Метод завантаження усіх останніх версій документів розсилки, 
   * стиснених у ZIP-архів. 
   * Якщо документ один - лише останню версію без компрессії.
   * @param integer $id ID : docflows.idDocFlow
   * @throws CHttpException
   */
  public function actionDownloadAll($id) {
    $flowmodel = Docflows::model()->findByPk($id);
    if (!$flowmodel) {
      throw new CHttpException(404, 'Помилка: процес документообігу з ІД ' 
              . $id . ' не знайдено. Прикро.');
    }
    //оновлення статусу документообігу
    $this->UpdateDocflowStatus($id);
    $docflowdocs = $flowmodel->docflowdocs;
    if (count($docflowdocs) == 1) {
      //якщо документ один, завантажити його останню версію
      $docfiles_model = DocumentFiles::model()->find('DocumentID=' 
              . $docflowdocs[0]->DocumentID
              . ' ORDER BY FileID DESC');
      if ($docfiles_model){
        $this->actionDownloadFile($docfiles_model->FileID);
      } else {
        throw new CHttpException(404, 'Документ з ІД ' 
              . $docflowdocs[0]->DocumentID . ' не має файлів для завантаження.');
      }
      exit();
    }
    //Формування zip-архіва
    $zipname = 'Розсилка_' . date('d.m.Y_H-i',strtotime($flowmodel->Created)) . '.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    foreach ($flowmodel->docflowdocs as $dfd) {
      $document = $dfd->document;
      //отримання останньої версії документа
      $docfiles_model = DocumentFiles::model()->find('DocumentID=' 
              . $document->idDocument 
              . ' ORDER BY FileID DESC');
      /* @var $docfiles_model DocumentFiles */
      if (!$docfiles_model){
        continue;
      }
      $model = $docfiles_model->file;
      if (!$model){
        continue;
      }
      $path = $model->folder;
      $file_entity = $path . $model->FileLocation;
      if (file_exists($file_entity)) {
        $file_ext = substr(strrchr($file_entity, '.'), 1);
        $some_string = (!empty($document->DocumentDescription))? $document->DocumentDescription:$document->idDocument;
        $some_string = preg_replace('/[?\|\\\\\/\*\.;:\<\>\"()]/', '_', $some_string);
        $some_string = str_replace('і',"_",$some_string);
        $zip->addFile($file_entity,  
                // 'LastVersion_Of_Document' . $document->idDocument . 
                // '_' . date('d_m_Y',strtotime($model->FileTimeStamp)) 
                ((mb_detect_encoding($some_string) == 'UTF-8')?
                iconv('UTF-8','ibm866//IGNORE',$some_string):$some_string)
                .'.' . $file_ext);
      }
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipname);
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);
    unlink($zipname);
  }
  
  /**
   * Метод вивантаження файлу і закріплення його за документом.
   * @return string JSON data
   */
  public function actionUpload(){
    header('Vary: Accept');
    if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
      header('Content-type: application/json');
    } else {
      header('Content-type: text/plain');
    }
    $DocumentID = Yii::app()->request->getParam('DocumentID',null);
    $this->layout = '//layouts/clear';
    $data = array();
    $model = new Files();
    $model->file_itself = CUploadedFile::getInstance($model, 'file_itself');
    $username = trim(Yii::app()->user->name);
    //якщо файл завантажено
    if ($model->file_itself !== null && $model->validate(array('file_itself'))) {
      //формується назва файлу із його MD5-хешу
      $md5_name = md5_file($model->file_itself->getTempName());
      $ext = $model->file_itself->extensionName;
      $new_filename = $model->folder.$username.DIRECTORY_SEPARATOR.$md5_name.'.'.$ext;
      $folder = $model->folder.$username;
      if (!is_dir($folder)){
        mkdir($folder);
      }
      if (!is_dir($folder)){
        $data[] = array('error', $folder. ' не є текою.');
        echo json_encode($data);
        return ;
      }
      //спроба збереження файлу
      if ($model->file_itself->saveAs($new_filename) !== true){
        $data[] = array('error', $new_filename. ' не зберігся.');
        echo json_encode($data);
        return ;
      }
      $model->FileLocation = $username . DIRECTORY_SEPARATOR . $md5_name . '.' . $ext;
      //ім'я файлу в базі даних - це його md5-хеш і поточний час у форматі Unix Timestamp
      $model->FileName = $md5_name . '_' . time();
      $model->UserID = Yii::app()->user->id;
      //відмнімання від квоти користувача (точніше підрозділу) розміру файлу у МБ
      $this->RecountUserQuota(Yii::app()->user->id, 
              -($model->file_itself->size) / (1024.0 * 1024.0));
      if ($model->save()){
        $message = 'Завантажено';
        if (is_numeric($DocumentID)){
          $dfmodel = new DocumentFiles();
          $dfmodel->FileID = $model->idFile;
          $dfmodel->DocumentID = $DocumentID;
          $dmodel = Documents::model()->findByPk($DocumentID);
          $dmodel->DocumentVisibility = 1;
          $dmodel->save();
          $dfmodel->save();
        }
      } else {
        $message = 'Помилка збереження';
      }
      $returl = Yii::app()->CreateUrl('/documents/index#doc-'.$DocumentID);
      //дані для виведення (асинхронно)
      $data[] = array(
          'name' => $model->file_itself->name,
          'type' => $model->file_itself->type,
          'size' => $model->file_itself->size,
          'returl' => $returl,
          'uploaded' => $message,
          'delete_url' => $this->createUrl('/files/delete',
            array('path' => $new_filename, 'id' => $model->idFile)),
          'delete_type' => 'POST');
    } else {
      //якщо файл не пройшов валідацію
      if ($model->hasErrors('file_itself')) {
        $data[] = array('error', $model->getErrors('file_itself'));
      } else {
        $data[] = array('error', "ERROR");
      }
    }
    // JQuery File Upload expects JSON data
    echo json_encode($data);
  }
  
  /**
   * Метод видалення файлу (асинхронно)
   */
  public function actionDelete(){
    if (Yii::app()->request->isPostRequest){
      $path = Yii::app()->request->getParam('path',null);
      $idFile = Yii::app()->request->getParam('id',null);
      if ($path){
        $file_size = filesize($path) / (1024.0 * 1024.0);
        $this->RecountUserQuota(Yii::app()->user->id,$file_size);
      } else {
        $data[] = array('error', 'Помилка');
        echo json_encode($data);
      }
      if (is_numeric($idFile)){
        $model = Files::model()->findByPk($idFile);
        if ($this->CheckDeptAccess($model->UserID, false) || 
         Yii::app()->user->CheckAccess('showProperties')){
          $model->delete();
        }
      }
    }
  }
  
  /**
   * Метод повертає список файлів в директорії, де вивантажуються документи.
   */
  protected function getFileList(){
    $folder = Yii::app()->getBasePath().'/../docs/';
    $it = new RecursiveDirectoryIterator($folder);
    $display = array ( '*' );
    $files = array();
    foreach(new RecursiveIteratorIterator($it) as $file){
      if (is_file($file) && (Yii::app()->user->CheckAccess('showProperties')? 
       true : strstr($file,Yii::app()->user->name) != null)){
        $filename = str_replace('\\', '/', $file);
        $file_indb = str_replace('\\','/',mb_substr($file,strlen($folder),strlen($file)-strlen($folder)));
        $filemodel = Files::model()->find('FileLocation LIKE "'.$file_indb.'"');
        $files[] = array(
          $filename,
          $filemodel
        );
      }
    }
    return $files;
  }

  /**
   * @todo TODO!
   * Вивантаження на гугл-драйв
   */
  public function actionFilelist(){
    /* @var $file Google_DriveFile */
    /* @var $service Google_DriveService */
    //$file = Yii::app()->JGoogleAPI->getObject('DriveFile','Drive');
    $drive_backup_dir_defaultname = 'DOCFLOW_BACKUP';
    $service = Yii::app()->JGoogleAPI->getService('Drive');
    $email_writers = array(
        'it.znu.edu@gmail.com'
    );
    
    $drive_backup_dir = Yii::app()->JGoogleAPI->getDirByName(
            $service,$drive_backup_dir_defaultname);
    
    if ($drive_backup_dir === null){
      $drive_backup_dir = Yii::app()->JGoogleAPI->createDir($service, $drive_backup_dir_defaultname,
              'docflowBACKUP', null, $email_writers);
      var_dump($drive_backup_dir->title . ' was created');
    } else {
      var_dump($drive_backup_dir->title . ' already exists, good!');
    }
    $cnt = 0;
    foreach ($this->getFileList() as $file_info){
      $path = $file_info[0];
      var_dump($path 
              . ' associate with '
              . ((is_object($file_info[1]))? 
                get_class($file_info[1]) . ', good!':$file_info[1]));
      if ($file_info[1]){
        $drive_user_dir = Yii::app()->JGoogleAPI->getDirByName(
            $service,$file_info[1]->user->username);
        if ($drive_user_dir === null){
          $dirParent = new Google_ParentReference();
          $dirParent->setId( $drive_backup_dir->id );
          $drive_user_dir = Yii::app()->JGoogleAPI->createDir($service, $file_info[1]->user->username,
                  $file_info[1]->user->info, $dirParent, $email_writers);
          var_dump($drive_user_dir->title . ' was created');
        } else {
          var_dump($drive_user_dir->title . ' already exists, good!');
        }
        $fileParent = new Google_ParentReference();
        $fileParent->setId( $drive_user_dir->id );
        
        $file_ext = substr(strrchr($path, '.'), 1);
        $file_name = $file_info[1]->documentfiles[0]->DocumentID 
                . '_' . $file_info[1]->idFile
                . '.' . $file_ext;
        $file_description = $file_info[1]->documentfiles[0]->document->DocumentDescription;
        
        $drive_file = Yii::app()->JGoogleAPI->uploadFile($service,
                $path, $file_name, $file_description,
                $fileParent, $email_writers);
        var_dump($drive_file);
      }
      $cnt++;
      if ($cnt == 1){
        break;
      }
    }
    
//    $file->setTitle('trololo');
//    $file->setDescription('This test file');
//    $file->setmimeType('application/vnd.google-apps.drive-sdk');
//    $createdFile = $service->files->insert($file);
    
    //var_dump($this->getFileList());
  }
  

  
}
