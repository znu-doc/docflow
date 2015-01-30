<?php

class EventsController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/docflow';
  public $defaultAction = 'admin';
  public $wdays = array("нд","пн","вт","ср","чт","пт","сб","нд");
  public $wday_alias = array(
     "нд" => "щонеділі",
     "пн" => "щопонеділка",
     "вт" => "щовівторка",
     "ср" => "щосереди",
     "чт" => "щочетверга",
     "пт" => "щоп`ятниці",
     "сб" => "щосуботи",
  );
  /**
   * @return array action filters
   */
  public function filters() {
    return array(
        'accessControl', // perform access control for CRUD operations
        //'postOnly + delete', // we only allow deletion via POST request
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
        array('allow', //
            'actions' => array('create', 
              'xupdate', 'update', 'attachmentrm'),
            'roles' => array('Event'),
        ),
        array('allow', //
            'actions' => array('eventdatedelete', 'delete'),
            'roles' => array('EventAdmin'),
        ),
        array('allow', //
            'actions' => array('index','admin','attachment','ajaxcounters'),
            'users' => array('@'),
        ),
        array('deny', // deny all users
            //'actions'=>array('index'),
            'users' => array('*'),
        ),
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new Events;
    $nmodel = $this->commonSave($model);
    $this->render('create', array(
        'model' => $nmodel,
    ));
  }
  

  public function actionXupdate(){
    $reqField = Yii::app()->request->getParam('field',null);
    $modelName = 'Events';
    $es = new EditableSaver($modelName);
    $es->update();
  }
  
  public function actionUpdate($id){
    $model = $this->loadModel($id);
    $user = User::model()->findByPk(Yii::app()->user->id);
    if (!Yii::app()->user->checkAccess('showProperties') && !in_array('EventAdmin',$user->getRoles())){
      $this->CheckDeptAccess($model->UserID,true);
    }
    $nmodel = $this->commonSave($model);
    $this->render('update', array(
        'model' => $nmodel,
    ));
  }
  
  protected function commonSave($model){
    $Events = Yii::app()->request->getParam('Events',array());
    $eventdates = Yii::app()->request->getParam('eventdates',array());
    $invited_ids = Yii::app()->request->getParam('invited_ids',array());
    $invited_descrs = Yii::app()->request->getParam('invited_descrs',array());
    $invited_seets = Yii::app()->request->getParam('invited_descrs_comment',array());
    $organizer_ids = Yii::app()->request->getParam('organizer_ids',array());
    $organizer_descrs = Yii::app()->request->getParam('organizer_descrs',array());
    if (!empty($Events)) {
      $model->attributes = $Events;
      $model->invited_ids = $invited_ids;
      $model->invited_descrs = $invited_descrs;
      $model->invited_seets = $invited_seets;
      $model->organizer_ids = $organizer_ids;
      $model->organizer_descrs = $organizer_descrs;
      $model->event_dates = $eventdates;
      if (!$model->FinishTime){
        $model->FinishTime = null;
      }
      if (!$model->StartTime){
        $model->StartTime = null;
      }
      if (empty($model->event_dates)){
        throw new CHttpException(400, 
          'Помилка збереження заходу : невірно вказана дата');
      }
      $model = $this->saveAttachment($model);
      $model->UserID = Yii::app()->user->id;
      if ($model->save()){
	$model->send_to_site = isset($Events['send_to_site'])? 
	  $Events['send_to_site']:false;
	$model->create_docflow = isset($Events['create_docflow'])? 
	  $Events['create_docflow']:false;
        $resp = 'success';
	$this->genDocFlow($model);
	if ($model->send_to_site){
	  $response = $this->SendToService($model);
	  //var_dump($response);exit();
	  $decoded_response = json_decode($response);
	  if ((!isset($decoded_response->calendar))?
	    true :
	    !isset($decoded_response->calendar->id)
	  ){
	    $resp = 'Сталася помилка з інформацією: '.$response;
	    $model->ExternalID = null;
	    $model->NewsUrl = null;
	    $model->save();
	    $this->redirect(Yii::app()->CreateUrl('events/index',array(
	      'id' => $model->idEvent,
	      'response' => $resp)));
	  }
	  $model->ExternalID = $decoded_response->calendar->id;
	  $model->NewsUrl = 
	  str_replace('{year}', date("Y",strtotime($model->event_dates[0])), 
	    str_replace('{month}', date("m",strtotime($model->event_dates[0])),
	      str_replace('{day}', date("d",strtotime($model->event_dates[0])),
		$decoded_response->calendar->url)));
	  $model->save();
	  $this->redirect(Yii::app()->CreateUrl('events/index',array(
	    'id' => $model->idEvent,
	    'response' => $resp)));
        }
      }
      $this->redirect(Yii::app()->CreateUrl('events/index',array('id' => $model->idEvent)));
    }
    return $model;
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    $model = $this->loadModel($id);

    $model->delete();
    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * Видалення однієї з дат заходу (якщо це остання, то і самого заходу)
   * @param integer $id the ID of the model to be deleted
   */
  public function actionEventdatedelete($id) {
    $model = Eventdates::model()->findByPk($id);
    if ($model){
      $model->delete();
    } else {
      $id = 0;
    }
    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * View separate event.
   */
  public function actionIndex($id,$response = '') {
    $model = $this->loadModel($id);
    $this->render('index', array(
        'model' => $model,
        'response' => $response,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin() {
    $model = new Events('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Events'])){
      $model->attributes = $_GET['Events'];
      if (isset($_GET['Events']['past'])){
        $model->past = $_GET['Events']['past'];
      } else {
        $model->past = 0;
      }
      if (isset($_GET['Events']['date_search'])){
        $model->date_search = $_GET['Events']['date_search'];
      } else {
        $model->date_search = '';
      }
    }
    $this->render('admin', array(
        'model' => $model,
    ));
  }
  
  public function actionAttachment($id){
    $model = Events::model()->findByPk($id);
    if (!$model){
	throw new CHttpException(404, 
	  'Захід #'.$id.' не знайдено.');
    }
    if ($model->FileID){
      $this->redirect(Yii::app()->CreateUrl("/files/DownloadFile",array('id' => $model->FileID)));
    } else {
      $this->redirect(Yii::app()->CreateUrl("/events/index",array('id' => $model->idEvent)));
    }
  }
  
  public function actionAttachmentrm($id){
    $model = Events::model()->findByPk($id);
    if (!$model){
	throw new CHttpException(404, 
	  'Захід #'.$id.' не знайдено.');
    }
    if ($model->FileID){
      $model->attfile->delete();
      $model->FileID = null;
      $model->save();
    }
    $this->redirect(Yii::app()->CreateUrl("events/update",array('id' => $model->idEvent)));
  }
  
  protected function embedImageFromAttachment($id){
    $model = Events::model()->findByPk($id);
    if (!$model){
	throw new CHttpException(404, 
	  'Захід #'.$id.' не знайдено.');
    }
    if (!$model->FileID){
	return false;
    }
    $fullname = $model->attfile->getFullName();
    if (!$model->attfile->FileExists()){
	return false;
    }
    $contents = file_get_contents($fullname);
    $base64   = base64_encode($contents); 
    if(($mime=CFileHelper::getMimeTypeByExtension($fullname))===null)
              $mime='text/plain';
    if (strpos($mime,"image") === false){
      return false;
    }
    return ('data:' . $mime . ';base64,' . $base64);
  }
  
  public function actionAjaxcounters(){
    $_date1 = Yii::app()->request->getParam('date1',date('Y-m-01'));
    $_date2 = Yii::app()->request->getParam('date2',date('Y-m-01',strtotime("next month")));
    $date1 = date("Y-m-d",strtotime($_date1));
    $date2 = date("Y-m-d",strtotime($_date2));
    $list= Yii::app()->db->createCommand('select count(EventID) as cnt,EventDate '
    .'from eventdates '
    .'where EventDate between "'.$date1.'" and "'.$date2.'"'
    .'group by EventDate order by EventDate ASC')->queryAll();
    echo CJSON::encode($list);
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Events::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'events-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  /**
   * Збереження або заміна прикріпленого файлу
   * @param Events $model
   */
  protected function saveAttachment($model){
    $att = CUploadedFile::getInstance($model, 'attachment');
    //var_dump($att);exit();
    if ( $att !== null){
      $file = new Files();
      if (!$att->getTempName()){
        throw new CHttpException(400, 
          'Сталася помилка: файл завеликий або пошкоджений.');
      }
      $username = trim(Yii::app()->user->name);
      $md5_name = md5_file($att->getTempName());
      $ext = $att->extensionName;
      $new_filename = $file->folder.$username.DIRECTORY_SEPARATOR.$md5_name.'.'.$ext;
      $folder = $file->folder.$username;
      if (!is_dir($folder)){
	mkdir($folder);
      }
      $file->FileName = $att->name;
      if (!is_dir($folder) || ($att->saveAs($new_filename) !== true)){
	throw new CHttpException(400, 
	  'Помилка збереження заходу : не вдалось зберегти прикріплений файл ^ ' . $att->name);
      }
      $file->FileLocation = $username.DIRECTORY_SEPARATOR.$md5_name.'.'.$ext;
      $file->UserID = Yii::app()->user->id;
      $file->FileVisibility = 1;
      $file->file_itself = $att;
      $new_quota_size = $this->RecountUserQuota(Yii::app()->user->id, 
	      -($att->size) / (1024.0 * 1024.0));
      if (!$file->save()){
	throw new CHttpException(400, 
	  'Помилка збереження заходу : не вдалось зберегти прикріплений файл.');
      }
      if ($model->FileID > 0){
	$model->attfile->delete();
      }
      $model->FileID = $file->idFile;
    }
    return $model;
  }
  
  /**
   * Створення розсилки запрошеним підрозділам
   * @param Events $model
   */
  protected function genDocFlow($model){
    if (!$model->create_docflow || empty($model->invited_ids)){
      return false;
    }
    $no_invited = true;
    foreach ($model->invited_ids as $invited_id){
      if ($invited_id > 0){
        $no_invited = false;
        break;
      }
    }
    if ($no_invited){
      return false;
    }
    $old_docflowevents = Docflowevents::model()->findAllByAttributes(
      array('EventID' => $model->idEvent)
    );
    if (count($old_docflowevents) > 0){
      $old_docflowevents[0]->docFlow->delete();
    }
    $docflow = new Docflows();
    $docflow->DocFlowName = $model->EventName;
    $docflow->DocFlowDescription = $model->EventDescription;
    $docflow->ExpirationDate = $model->eventDates[0]->EventDate;
    $docflow->DocFlowStatusID = 1;
    $docflow->Created =  date('Y-m-d H:i:s');
    $docflow->Finished = null;
    $docflow->DocFlowGroupID = $this->getDocFlowGroupID($model->invited_ids,"група розсилки заходу #".$model->EventName."#");
    $docflow->DocFlowTypeID = 3;
    if (!$docflow->save()){
	throw new CHttpException(400, 
	  'Помилка збереження розсилки.');
    }
    $docflowevent = new Docflowevents();
    $docflowevent->DocFlowID = $docflow->idDocFlow;
    $docflowevent->EventID = $model->idEvent;
     if (!$docflowevent->save()){
	$docflow->delete();
	throw new CHttpException(400, 
	  'Помилка збереження розсилки.');
    }
    return true;
  }
  
  /**
   * Відправлення даних на веб-сервіс через CURL POST-запитом
   * @param Events $model
   */
  protected function SendToService($model){
    $response = false;
    $date_intervals = array();
    // підключення
    $url = "http://sites.znu.edu.ua/cms/index.php";
    $api_key='dksjf;aj;weio[wlooiuoiuhlk;lk\'';
    $site_id = 89;
//     $url = "http://10.1.22.8/cms/index.php"; //test-service
//     $api_key='1234567';
//     $site_id = 62;
    $ch = curl_init($url);
    $invited = "";
    $organizers = "";
    $date_time =  preg_replace("/,(\d\d?)(,|$)/i",",$1 числа кожного місяця$2",
          str_replace($this->wdays,$this->wday_alias, mb_strtolower($model->DateSmartField,'utf8')))
        . " ".(($model->StartTime)? mb_substr($model->StartTime,0,5,"utf-8"): "(час початку не вказано)")
        .(($model->FinishTime)? " - ".mb_substr($model->FinishTime,0,5,"utf-8"): "");
    if ($model->isAllFacultiesInvited()){
      $invited = "<ul><li>Усі факультети</li></ul>";
    } else {
      $vals = $model->getInvited(); 
      for ($i = 0; ($i < count($vals) && is_array($vals)); $i++){
	if ($i == 0){
	  $invited .= '<ul>';
	}
	$invited .= '<li>'.$vals[$i]['InvitedComment']
	  .'</li>';
	if ($i == count($vals) - 1){
	  $invited .= "</ul>";
	}
      }
    }
    $vals = $model->getOrganizers(); 
    for ($i = 0; ($i < count($vals) && is_array($vals)); $i++){
      if ($i == 0){
        $organizers .= '<ul>';
      }
      $organizers .= '<li>'.$vals[$i]['OrganizerComment']
        .'</li>';
      if ($i == count($vals) - 1){
        $organizers .= "</ul>";
      }
    }
    
    for ($i = 0; $i < count($model->event_dates) && is_array($model->event_dates); $i++){
      $begin_time = (strlen($model->StartTime) > 0)? $model->StartTime : "00:00:00";
      $end_time = (strlen($model->FinishTime) > 0)? $model->FinishTime : "23:59:59";
      
      $begin_timestamp = strtotime($model->event_dates[$i] . ' ' . $begin_time);
      $end_timestamp = strtotime($model->event_dates[$i] . ' ' . $end_time);
      $date_intervals[] = array(
         'pochrik' => date('Y',$begin_timestamp),
         'pochmis' => date('m',$begin_timestamp),
         'pochtyzh' => -1,
         'pochday' => date('d',$begin_timestamp),
         'pochgod' => date('H',$begin_timestamp),
         'pochhv' => date('i',$begin_timestamp),
         
         'kinrik' => date('Y',$end_timestamp),
         'kinmis' => date('m',$end_timestamp),
         'kintyzh' => -1,
         'kinday' => date('d',$end_timestamp),
         'kingod' => date('H',$end_timestamp),
         'kinhv' => date('i',$end_timestamp)
      );
    }
    // дані для відправки
    $data = array(
      'api_key' => $api_key,
      'action' => 'calendar/api/'.(($model->ExternalID)? 'update':'create'),
      'lang' => 'ukr',
      'site_id' => $site_id,
      'nazva' => $model->EventName,
      'vis' => 1,
      'categories' => implode(',',
        array(
          $model->eventKind->EventKindName,
          $model->eventType->EventTypeName
        )
      ),
      'dates' => $date_intervals, 
      'description' => ''
        . '<div class="EventPlaceHeader">Місце проведення: </div>'
        . '<div class="EventPlace">'
            .((empty($model->EventPlace))? 
            "не вказано":$model->EventPlace) . '</div>'
        . '<div class="DateTimeHeader">Дата і час: </div> '
        . '<div class="DateTime">'.$date_time . '</div>'
        . '<div class="EventDescription">'. $model->EventDescription . '</div>'
        . '<div class="InvitedHeader">Запрошені: </div>'
        . '<div class="InvitedList">'.((empty($invited))? "не вказано":$invited).'</div>'
        . '<div class="OrganizersHeader">Організатори: </div>'
        . '<div class="OrganizersList">'.((empty($organizers))? "не вказано":$organizers).'</div>'
        . '<div class="ResponsibleHeader">'.'Відповідальні особи: </div>'
        . '<div class="Responsible">'
            .((empty($model->Responsible))? 
            "не вказано":$model->Responsible) . '</div>'
        . '<div class="ContactsHeader">'.'Контактні дані: </div>'
        . '<div class="Contacts">'
            .((empty($model->ResponsibleContacts))? 
            "не вказано":$model->ResponsibleContacts) . '</div>'
    );
    if ($model->ExternalID > 0){
      $data['id'] = $model->ExternalID;
    }
    //var_dump($data);exit();
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    // треба отримати результат
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // запит...
    $response = curl_exec($ch);
    //$errmsg  = curl_error( $ch );
    //$err     = curl_errno( $ch );
    //$header  = curl_getinfo( $ch );
    // закрити з_єднання
    curl_close($ch);
    return $response;
  }
  
}
