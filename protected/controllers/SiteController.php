<?php

class SiteController extends Controller {

  /**
   * Declares class-based actions.
   */
  //public $layout='//layouts/column2_1';
  public function actions() {
    return array(
        // captcha action renders the CAPTCHA image displayed on the contact page
        'captcha' => array(
            'class' => 'CCaptchaAction',
            'backColor' => 0xFFFFFF,
        ),
        // page action renders "static" pages stored under 'protected/views/site/pages'
        // They can be accessed via: index.php?r=site/page&view=FileName
        'page' => array(
            'class' => 'CViewAction',
        ),
       'yiichat'=>array('class'=>'YiiChatAction'), // 
    );
  }

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
        array('allow', // allow all users to perform  actions
            'actions' => array('login', 'error', 'captcha', 'index', 'contact',
                "logout", 'hidesmallmenu'),
            'users' => array('*'),
        ),
        array('allow',
            'actions' => array('checknotification', 'anketa', 'AnketaModal','yiichat','chat'),
            'users' => array('@'),
        ),
        array('allow',
            'actions' => array('monitoring',),
            'users' => array('munspel', 'admin')
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex() {
    if (Yii::app()->user->checkAccess('asOperatorStart')) {
      $this->redirect(Yii::app()->createUrl("/docflows/index"));
    }
    $this->layout = '//layouts/docflow';
    $this->render('index');
  }
  
  public function actionChat(){
    $this->render('chat');
  }
  
  

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError() {
    $this->layout = '//layouts/main';
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest) {
        echo $error['message'];
      } else {
        $this->render('error', $error);
      }
    }
  }

  /**
   * Displays the contact page
   */
  public function actionContact() {
    $model = new ContactForm;
    if (isset($_POST['ContactForm'])) {
      $model->attributes = $_POST['ContactForm'];
      if ($model->validate()) {
        $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
        $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
        $headers = "From: $name <{$model->email}>\r\n" .
                "Reply-To: {$model->email}\r\n" .
                "MIME-Version: 1.0\r\n" .
                "Content-type: text/plain; charset=UTF-8";

        mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
        Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
        $this->refresh();
      }
    }
    $this->layout = '//layouts/docflow';
    $this->render('contact', array('model' => $model));
  }

  /**
   * Displays the login page
   */
  public function actionLogin() {
    $model = new LoginForm;
    $this->layout = "//layouts/main";
    // if it is ajax validation request
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    // collect user input data
    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      // validate user input and redirect to the previous page if valid
      if ($model->validate() && $model->login())
        $this->redirect(Yii::app()->user->returnUrl);
    }
    // display the login form*/

    $this->render('login', array('model' => $model));
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout() {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }


  /**
   * Асинхронно повертає дані для WebKit-нотифікацій
   * @return string JSON string for AJAX WebKIT Notifications
   */
  public function actionChecknotification() {
    $this->layout = '//layouts/clear';
    $docflow = new Docflows();
    $docflow->unsetAttributes();
    $docflow->MyDeptIDs = implode(',',$this->my_dept_ids);
    $models = $docflow->getMyDeptNewFlows();
    if (!empty($models)) {
      $data = array();
      $i = 0;
      foreach ($models as $model) {
        $data[$i]['DocFlowName'] = $model->DocFlowName;
        $dept_names = array();
        foreach ($model->docFlowGroup->owner->departments as $dept){
          $dept_names [] = $dept->DepartmentName;
        }
        $data[$i]['DeptartmentName'] = implode('; ',$dept_names);
        $data[$i]['Url'] = Yii::app()->CreateUrl('/docflows/index',array('id' => $model->idDocFlow));
        $i++;
      }
      echo json_encode($data);
      return ;
    }
    return ;
  }

  public function actionAnketa() {
    $model = new Monitoringanswers;
    $already_answered = Monitoringanswers::model()->count('UserID=' . Yii::app()->user->id);
    if ($already_answered > 0 || Yii::app()->user->checkAccess('showProperties')) {
      $this->layout = '//layouts/docflow';
      $this->render('/site/anketa', array(
          'already_answered' => $already_answered,
          'model' => $model,
      ));
      exit();
    }
    if (Yii::app()->request->isPostRequest) {
      $attrs = $model->attributeLabels();
      foreach ($_POST as $key => $val) {
        if (isset($attrs[$key]) && is_numeric($val)) {
          $attrs[$key] = $val;
        }
      }
      $attrs['UserID'] = Yii::app()->user->id;
      if (isset($_POST['q10'])) {
        $attrs['q10'] = $_POST['q10'];
      } else {
        $attrs['q10'] = '--';
      }
      $attrs['Created'] = date('Y-m-d h:i:s');
      $model->attributes = $attrs;
      if ($model->save()) {
        $this->redirect('anketa');
      } else {
        $this->render('/site/anketa', array(
            'model' => $model,
        ));
        exit();
      }
    }
    $this->layout = '//layouts/docflow';
    $this->render('/site/anketa', array(
        'aflag' => true
    ));
  }

  public function actionAnketaModal() {
    $this->renderPartial('//site/_anketaModal', array(
        true, true
    ));
  }

  public function actionMonitoring() {
    $model = new Monitoringanswers('search');
    $reqMonitoringanswers = Yii::app()->request->getParam('Monitoringanswers', null);
    $model->unsetAttributes();
    if ($reqMonitoringanswers) {
      $model->attributes = $reqMonitoringanswers;
    }
    $this->layout = '//layouts/docflow';
    $this->render('/site/monitoring', array(
        'model' => $model
    ));
  }
  

}
