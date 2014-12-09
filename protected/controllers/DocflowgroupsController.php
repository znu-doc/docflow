<?php

class DocflowgroupsController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $defaultAction = 'admin';

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
        array('allow', // allow all users to perform 'index' and 'view' actions
            'actions' => array('delete', 'depts'),
            'users' => array('@'),
        ),
        array('deny', // deny all users
            //'actions'=>array('index'),
            'users' => array('*'),
        ),
    );
  }

  /**
   * Метод видалення групи розсилки.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    $model = $this->loadModel($id);
    if (!empty($model->docflows)) {
      $this->redirect(
              array(
                  Yii::app()->createUrl('../docview/index', 
                          array('docflow' => 'show')),
              )
      );
    }
    foreach ($model->docflowgroupdepts as $dfgu) {
      $dfgu->delete();
    }
    $model->delete();
    $this->redirect(
            array(
                Yii::app()->createUrl('../docview/index', 
                        array('docflow' => 'init')),
            )
    );
  }
  
  /**
   * Повертає JSON закодований масив ID підрозділів групи
   * @param integer $id
   */
  public function actionDepts($id){
    $model = $this->loadModel($id);
    $depts = array();
    foreach ($model->docflowgroupdepts as $dfdept){
      $depts[] = $dfdept->DeptID;
    }
    echo json_encode($depts);
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Docflowgroups::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

}
