<?php

class DepartmentsController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/docflow'
   */
  public $layout='//layouts/column2';
  public $defaultAction='admin';

  /**
   * @return array action filters
   */
  public function filters()
  {
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
        array('allow', // allow all users to perform  actions
            'actions' => array('select2'),
            'users' => array('*'),
        ),
        array('allow',
            'actions' => array('create','update','delete','index','admin'),
            'users' => array('munspel', 'admin')
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $this->render('view',array(
      'model'=>$this->loadModel($id),
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Departments;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Departments']))
    {
      $model->attributes=$_POST['Departments'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->idDepartment));
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Departments']))
    {
      $model->attributes=$_POST['Departments'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->idDepartment));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=new CActiveDataProvider('Departments');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Departments('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Departments']))
      $model->attributes=$_GET['Departments'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Метод для асинх. вибірки підрозділів у віджетах select2
   * @param string $q параметр для пошуку назви підрозділу
   */
  public function actionSelect2($q=null,$n_ids="[]"){
    $fields = array();
    $criteria = new CDbCriteria();
    $n_ids = CJSON::decode($n_ids);
    $q = ($rq = Yii::app()->request->getParam('query',null))?  $rq : $q;
    $criteria->compare('CONCAT(DepartmentName,FunctionDescription)', $q, true);
    if (!empty($n_ids)){
      $criteria->addNotInCondition('idDepartment',$n_ids);
    }
    //$criteria->addCondition('idDepartment NOT IN ('.implode(',',$this->my_dept_ids).')');
    $criteria->addCondition('idDepartment > 1');
    $criteria->order = 'DepartmentName ASC';
    foreach (Departments::model()->findAll($criteria) as $model){
      $fields[] = array(
        'text' => $model->DepartmentName, 
        'id' => $model->idDepartment
      );
    }
    echo CJSON::encode($fields);
  }
  
  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id)
  {
    $model=Departments::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='departments-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
