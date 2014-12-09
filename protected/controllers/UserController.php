<?php

class UserController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
        'accessControl', // perform access control for CRUD operations
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules() {
    return array(
        array('allow', // allow authenticated user to perform 'ownprofile' action
            'actions' => array('ownprofile','UpdateEditable'),
            'users' => array('@'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('index', 'view', 'admin', 'create'),
            'roles' => array('Admins'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('index', 'view', 'admin', 'delete', 'create', 'update'),
            'roles' => array("Root"),
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Редагування інформації користувача ним же.
   */
  public function actionOwnprofile() {
    $reqPass = Yii::app()->request->getParam('password',null);
    $model = User::model()->findByPk(Yii::app()->user->id);
    if ($reqPass){
      $model->password = $reqPass;
      $model->save();
    }
    $dfcriteria = new CDbCriteria();
    $dfcriteria->with = array(
      'docFlowGroup'
    );
    $dfcriteria->together = true;
    $dfcriteria->group = 't.idDocFlow';
    $dfcriteria->compare('OwnerID',Yii::app()->user->id);
    $dept_models = Departments::model()->findAll('idDepartment IN ('.implode(',',$this->my_dept_ids).')');
    $my_dept_names = array();
    foreach ($dept_models as $dept_model){
      $my_dept_names[] = $dept_model->DepartmentName;
    }
    $ans_count = Docflowanswers::model()->count('UserID = '.Yii::app()->user->id);//('.implode(',',$this->my_dept_ids).')');
    $flow_count = Docflows::model()->count($dfcriteria);
    $doc_count = Documents::model()->count('UserID="'.Yii::app()->user->id.'"');
    $file_count = Files::model()->count('UserID="'.Yii::app()->user->id.'"');
    $udmodel = Userdepartment::model()->find('UserID='.Yii::app()->user->id);
    $quota = $udmodel->quota . ' МБ';
    $this->layout = '//layouts/docflow';
    $this->render('/user/info', array(
        'model' => $model,
        'my_dept_names' => $my_dept_names,
        'ans_count' => $ans_count,
        'flow_count' => $flow_count,
        'doc_count' => $doc_count,
        'file_count' => $file_count,
        'quota' => $quota,
    ));
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id) {
    $this->render('view', array(
        'model' => $this->loadModel($id),
    ));
  }

  /**
   * Creates a new model.
   */
  public function actionCreate() {
    $model = new User();
    $deptmodel = new Departments();
    $reqUser = Yii::app()->request->getPost('User', null);
    $reqDepartments = Yii::app()->request->getPost('Departments', null);
    if ($reqUser && $reqDepartments) {
      $model->attributes = $reqUser;
      $already_exists = User::model()->find('username LIKE \'' . $model->username . '\'');
      //якщо такий користувач існує, то це помилка
      if (!empty($already_exists)) {
        $this->render('create', array('model' => $model,
            'deptmodel' => $deptmodel,
            'error' => 'Такий логін вже існує!'));
        exit();
      }
      if ($model->save()) {
        //створення зв'язку між користувачем і підрозділом
        $dept_attr = $reqDepartments;
        $udmodel = new Userdepartment();
        $udmodel->UserID = $model->id;
        $udmodel->DeptID = $dept_attr['idDepartment'];
        $udmodel->quota = 700;
        if ($udmodel->save()) {
          //користувау назначаються стандартні права користувача
          $sysmodel = new Sysroleassignments();
          $sysmodel->userid = $model->id;
          $sysmodel->itemname = 'Users';
          $sysmodel->data = 's:0:""';
          $sysmodel->save();
          $this->redirect(Yii::app()->CreateUrl('user/admin'));
        }
      } else {
        $this->render('create', array('model' => $model, 'deptmodel' => $deptmodel));
      }
    } else {
      $this->render('create', array('model' => $model, 'deptmodel' => $deptmodel));
    }
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    $model->password = "";
    $udmodeli = $model->userdepartments;
    if (count($udmodeli) == 0) {
      $udmodel = new Userdepartment;
      $udmodel->DeptID = 1;
      $udmodel->UserID = $model->id;
      $udmodel->quota = 700;
      $udmodel->save();
    }
    if (isset($_POST['User'])) {
      $model->attributes = $_POST['User'];
      if (isset($_POST['Departments'])) {
        $udmodel = $udmodeli[0];
        $dept_attr = $_POST['Departments'];
        $udmodel->DeptID = $dept_attr['idDepartment'];
        if (!$udmodel->save()) {
          throw new CHttpException(404, "Виникла помилка при збереженні зв'язку підрозділу з ID " .
          $udmodel->DeptID . " і користувача з ID " . $model->id . ".");
        }
      }
      if ($model->save())
        $this->redirect(array('view', 'id' => $model->id));
    }

    $this->render('update', array(
        'model' => $model,
        'deptmodel' => (count($udmodeli)) ?
                $model->userdepartments[0]->dept : Userdepartment::model(),
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $model = User::model()->findByPk($id);

      //видалення усіх зв'язок користувачів з їх правами
      Sysroleassignments::model()->deleteAllByAttributes(
              array('userid' => $model->id));

      //видалення усіх зв'язок користувачів з їх підрозділ
      Userdepartment::model()->deleteAllByAttributes(
              array('UserID' => $model->id));

      //видалення усіх зв'язаних відповідей на розсилки
      Docflowanswers::model()->deleteAllByAttributes(
              array('UserID' => $model->id));

      //видалення усіх скарг і пропозицій користувача
      Propositionbook::model()->deleteAll('UserID=' . $model->id);

      //вибірка усіх груп документообігу користувача
      $docflowgroups = Docflowgroups::model()->findAllByAttributes(
              array('OwnerID' => $model->id));
      foreach ($docflowgroups as $group) {
        $group_id = $group->idDocFlowGroup;
        //видалення зв'язок групи і підрозділів
        Docflowgroupdepts::model()->deleteAllByAttributes(
                array('DocFlowGroupID' => $group_id));
        //вибірка усіх розсилок групи користувача
        $docflows = Docflows::model()->findAll('DocFlowGroupID=' . $group_id);
        foreach ($docflows as $docflow) {
          $flow_id = $docflow->idDocFlow;
          //видалення відповідей на розсилку
          Docflowanswers::model()->deleteAllByAttributes(
                  array('DocFlowID' => $flow_id));
          //видалення зв'язок документів і розсилки
          Docflowdocs::model()->deleteAllByAttributes(
                  array('DocFlowID' => $flow_id));
          //видалення розсилок на групу
          $docflow->delete();
        }
        //видалення групи
        $group->delete();
      }
      //вибірка усих документів корситувача
      $documents = Documents::model()->findAllByAttributes(
              array('UserID' => $model->id));
      foreach ($documents as $document) {
        $doc_id = $document->idDocument;
        //видалення звязків між процесами документообігу і документом
        Docflowdocs::model()->deleteAllByAttributes(
                array('DocumentID' => $doc_id));
        //видалення усіх відповідей на розсилки, де прикріплено цей документ
        Docflowanswers::model()->deleteAllByAttributes(
                array('DocumentID' => $doc_id));
        //вибірка усих звязків між документом та його версіями (файлами)
        $documentfiles = DocumentFiles::model()->findAllByAttributes(
                array('DocumentID' => $doc_id));
        $def_path = Yii::app()->getBasePath().'/../docs/';
        foreach ($documentfiles as $documentfile) {
          //видалення файлів
          $file_location = $def_path .
                  $documentfile->file->FileLocation;
          $file = $documentfile->file;
          if (file_exists($file_location)) {
            unlink($file_location);
          }
          $documentfile->delete();
          $file->delete();
        }
        $document->delete();
      }
      //видалення усіх файлів, завантаженим користувачем
      $files = Files::model()->findAllByAttributes(
              array('UserID' => $model->id));
      foreach ($files as $file) {
        DocumentFiles::model()->deleteAllByAttributes(
                array('FileID' => $file->idFile));
        $file->delete();
      }

      $model->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax'])) {
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
      }
    } else
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $this->redirect(Yii::app()->createUrl("user/admin"));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin() {
    $getUser = Yii::app()->request->getParam('User', null);
    $getDept = Yii::app()->request->getParam('Departments', null);
    $model = new User('search');
    $dept = new Departments('search');
    $model->unsetAttributes();  // clear any default values
    if ($getUser) {
      $model->attributes = $getUser;
    }
    if ($getDept) {
      $dept->attributes = $getDept;
    }
    $model->searchDept = $dept;
    $this->render('admin', array(
        'model' => $model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = User::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  public function actionUpdateEditable(){
    $es = new EditableSaver('User');
    $es->update();
  }

}
