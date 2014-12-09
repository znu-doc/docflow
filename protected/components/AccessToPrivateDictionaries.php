<?php

class AccessToPrivateDictionaries {

  //під питанням -- повертає права доступу на приватні сторінки
  public static function getAccessRulesToDictionaries() {
    return array(
        /* array('allow',  // allow all users to perform 'index' and 'view' actions
          'actions'=>array('update','admin'),
          'users'=>array('Users'),
          ), */
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('view', 'create', 'update', 'admin', 'delete'),
            'users' => array('Admins'),
        ),
        array('deny', // deny all users
            'actions' => array('index'),
            'users' => array('*'),
        ),
    );
  }

}

?>
