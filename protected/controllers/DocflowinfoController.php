<?php

class DocflowinfoController extends Controller {

  /**
   * Інструкціїї :: контроллер
   */
  public $layout='//layouts/main';
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
            'actions' => array('index'),
            'users' => array('*'),
        ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('index'),
            'users' => array('@'),
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
    $this->render('index');
  }

}
