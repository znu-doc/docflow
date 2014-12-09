<?php

class DocflowanswersController extends Controller {

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
        array('allow', // allow all users to perform 'index' and 'view' actions
            'actions' => array('createanswer', 'error', 'deleteanswers', 'UpdateEditable'),
            'users' => array('@'),
        ),
        array('deny', // deny all users
            //'actions'=>array('index'),
            'users' => array('*'),
        ),
    );
  }

  /**
   * Метод для створення відповіді у процесі документообігу (на певну розсилку).
   */
  public function actionCreateanswer() {
    $reqDocFlowID = Yii::app()->request->getParam('DocFlowID', null);
    $dept_ids = $this->my_dept_ids;
    $reqDocumentID = Yii::app()->request->getParam('AnswerDocID', 0);
    $reqAnswerTypeID = Yii::app()->request->getParam('AnswerTypeID', 0);
    $reqComment = Yii::app()->request->getParam('Comment', "");
    $reqReturl = Yii::app()->request->getParam('returl', "");
    $returl = $reqReturl;
    $err_url = Yii::app()->CreateUrl('/docflowanswers/error');
    if (!$returl){
      $returl = Yii::app()->CreateUrl('/site/index');
    }
    if (!is_numeric($reqDocFlowID) || 
      ((!empty($reqDocumentID))? !is_numeric($reqDocumentID): false) ||
      !is_numeric($reqAnswerTypeID)
      ){
      $this->redirect(Yii::app()->CreateUrl('/docflowanswers/error', 
        array(
          'returl' => $returl,
          'message' => 'Помилка отримання даних.',
        )
       )
      );
    }
    $returl .= '#df-'.$reqDocFlowID;
    $exist_answer_cnt = Docflowanswers::model()->count('DeptID IN ('.implode(',',$dept_ids).') AND DocFlowID=:DocFlowID',
      array(':DocFlowID'=>$reqDocFlowID));
    if ($exist_answer_cnt){
      $this->redirect(Yii::app()->CreateUrl('/docflowanswers/error', 
        array(
          'returl' => $returl,
          'message' => 'Помилка внутрішньої обробки даних: відповідь вже надано.',
        )
       )
      );
    }
    $docflow_model = Docflows::model()->findByPk($reqDocFlowID);
    $docflow_group_deptIDs = array();
    if ($docflow_model){
      $docflow_group_deptIDs = $docflow_model->getRespondentsID();
    } else {
        throw new CHttpException(400, 'Розсилка з внутрішнім номером '
              . $reqDocFlowID . ' не існує.');
    }
    foreach ($dept_ids as $DeptID){
      if (!in_array($DeptID,$docflow_group_deptIDs)){
        continue;
      }
      $model = new Docflowanswers();
      $model->DeptID = $DeptID;
      $model->UserID = Yii::app()->user->id;
      $model->DocFlowID = $reqDocFlowID;
      $model->AnswerTypeID = $reqAnswerTypeID;
      if ($reqComment){
        $model->DocFlowAnswerText = $reqComment;
      }
      if ($reqDocumentID){
        $model->DocumentID = $reqDocumentID;
      }
      if (!$model->save()){
        $this->redirect(Yii::app()->CreateUrl('/docflowanswers/error', 
          array(
            'returl' => $returl,
            'message' => 'Помилка збереження даних: '.serialize($model->errors),
          )
         )
        );
      }
    }
    $this->UpdateDocflowStatus($reqDocFlowID);
    $this->redirect($returl);
  }
  /**
    Метод видалення відповідей респондента за вказаним ІД документообігу.
  */
  public function actionDeleteanswers($id){
    $dept_ids = $this->my_dept_ids;
    if (is_numeric($id)){
      Docflowanswers::model()->deleteAll('DeptID IN ('.implode(',',$dept_ids).') AND DocFlowID=:DocFlowID',
        array(':DocFlowID'=>$id));
      $this->redirect(Yii::app()->request->urlReferrer.'#df-'.$id);
    }
  }
  
  public function actionError(){
    $reqReturl = Yii::app()->request->getParam('returl', "");
    $reqMessage = Yii::app()->request->getParam('message', "");
    $this->render('error',
      array(
        'returl' => $reqReturl,
        'message' => $reqMessage,
      )
    );
  }
  
  public function actionUpdateEditable(){
    $es = new EditableSaver('Docflowanswers');
    $es->update();
  }
}
