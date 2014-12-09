<?php

class DocflowsController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/docflow';
  public $defaultAction = 'index';
  public $mode = false;

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
        array('deny',
            'users' => array('?'),
        ),
        array('allow', // allow all users to perform 'index' and 'view' actions
            'actions' => array('createflow', 'deleteflow', 
              'ajaxupdatestatus', 'ajaxdocflowrespondents', 
              'index', 'updateEditable', 'docflowwatch', 'search', 'tst'),
            'users' => array('*'),
        ),
        array('deny',
            'users' => array('*'),
        ),
    );
  }

  public function actionIndex($id = 0){
    //Параметри пошуку
    $reqDocflows = Yii::app()->request->getParam('Docflows',null);
    //Режим перегляду (in|from|all)
    $reqViewMode = Yii::app()->request->getParam('mode',false);
    //ID розсилки для перегляду окремої розсилки
    $reqId = Yii::app()->request->getParam('id',null);
    //ID документа для перегляду усіх його розсилок
    $reqDocId = Yii::app()->request->getParam('DocumentID',null);
    $model = new Docflows;
    $model->unsetAttributes();
    if ($id && !$reqId){
      $reqId = $id;
    }
    $my_dept_ids = implode(',',$this->my_dept_ids);
    if ($reqDocflows){
      foreach ($reqDocflows as $rqkey => $rqdf){
        $model->$rqkey = $rqdf;
      }
    }
    if (is_numeric($reqDocId)){
      $model->DocumentID = $reqDocId;
    }
    if (is_numeric($reqId)){
      $model->idDocFlow = $reqId;
    }
    $model->mode = $reqViewMode;
    if ((empty($model->mode) && !$reqDocId && !$reqId) 
            || $model->mode == 'in' || $model->mode == 'new'){
      $this->mode = true;
    }
    $model->MyDeptIDs = $my_dept_ids;
    //власне метод пошуку
    $data = $model->search_rel();
    $data->keyAttribute ='idDocFlow';
    $this->layout = '//layouts/docflow';
    $this->render('docflowsview', array(
       'data' => $data,
       'model' => $model,
    ));
  }

  /**
   * Метод для асинх. оновлення у віджетах x-editable
   */
  public function actionUpdateEditable(){
    //$reqField = Yii::app()->request->getParam('field');
    $es = new EditableSaver('Docflows');
    $es->update();
  }

  
  /**
   * Метод створення процесу розсилки
   */
  public function actionCreateflow(){
    $model = new Docflows();
    //параметри розсилки, щоб зберегти
    $reqDocflows = Yii::app()->request->getParam('Docflows',null);
    if (!$reqDocflows){
      //якщо параметри не пришйли, то просто створити форму
      $this->renderCreateFormPage($model);
      return ;
    }
    //ідентифікатори респондентів - підрозділів
    $reqRespIDs = Yii::app()->request->getParam('input_check_dept',null);
    //ідентифікатори документів, що будуть розсилатися
    $reqDocIDs = Yii::app()->request->getParam('DocIDs',null);
    //ідентифікатор обраної групи розсилки
    $reqIdDocFlowGroupSelected = Yii::app()->request->getParam('idDocFlowGroupSelected',null);
    //чи обрано групи розсилки
    $reqIs_selected_group = Yii::app()->request->getParam('is_selected_group',null);
    //ім_я для збереження групи
    $reqSave_group_as = Yii::app()->request->getParam('save_group_as',null);
    $reqDocFlowGroupName = ($reqSave_group_as > 0)? 
    Yii::app()->request->getParam('DocFlowGroupName',''): '';
    $model->attributes = $reqDocflows;
    $model->DocFlowStatusID = 1;
    $initiated = date('Y-m-d H:i:s');
    $model->ExpirationDate = (!$model->ExpirationDate)? 
      '' : date('Y-m-d H:i:s',strtotime(str_replace('.','-',$model->ExpirationDate)));
    $model->Created = $initiated;
    $model->Finished = null;
    if (!empty($reqRespIDs) && !$reqIs_selected_group) {
      //якщо група не обрана, то створити її
      $dept_ids = $reqRespIDs;
      $model->DocFlowGroupID = $this->getDocFlowGroupID($dept_ids,$reqDocFlowGroupName);
    }
    if ($reqIs_selected_group > 0 && $reqIdDocFlowGroupSelected > 0){
      $model->DocFlowGroupID = $reqIdDocFlowGroupSelected;
    }
    if (!($model->DocFlowGroupID > 0)){
      throw new CHttpException(400, 'Помилка створення групи для розсилки');
    }
    $model->DocFlowTypeID = ($model->ControlDate)? 4 : 3;
    $doc_ids = explode(',',$reqDocIDs);
    if (!empty($model->ControlDate)){
      foreach ($doc_ids as $doc_id){
        $doc_model = Documents::model()->findByPk($doc_id);
        if (!$doc_model){
          continue;
        }
        $doc_model->ControlField = $model->ControlDate;
        $doc_model->save();
      } 
    }
    $Dmodel = Documents::model()->findByPk($doc_ids[0]);
    if ($Dmodel){
      //назва розсилки формується із змісту першого документа
      $descr = $Dmodel->DocumentDescription;
      $df_auto_name = 'Розсилка ` '. $descr . ' `';
      $model->DocFlowName = $df_auto_name;
    } else {
      throw new CHttpException(400, 
        'Помилка створення автоматичної назви розсилки : документа з ID'.$doc_ids[0].' не знайдено.');
    }
    if ($model->DocFlowPeriodID > 1){
      //якщо встановлена періодичність, то назва періодичності додається до строки контролю )
      $add_str = Docflowperiod::model()->findByPk($model->DocFlowPeriodID)->PeriodName;
      $model->ControlDate .= ' `'.$add_str.'`';
    }
    if ($model->save()){
      foreach ($doc_ids as $doc_id) {
        if (is_numeric($doc_id)){
          $Fdmodel = new Docflowdocs;
          $Fdmodel->DocumentID = $doc_id;
          $Fdmodel->DocFlowID = $model->idDocFlow;
          if (!$Fdmodel->save()){
            throw new CHttpException(400, 
              'Помилка збереження документа #'.$doc_id.' для розсилки #'.$model->idDocFlow);
          }
        } else {
          throw new CHttpException(400, 'Помилка : "'.$doc_id.'" - не є ідентифікатором документа');
        }
      }
      $this->redirect(Yii::app()->CreateUrl('/docflows/index',array(
        'mode' => 'from'
      )));
    } else {
      $this->renderCreateFormPage($model);
    }
 }
  
 /**
  * Метод створення форми для нової розсилки
  * @param Docflows $model
  */
  protected function renderCreateFormPage($model){
     $reqDocumentID = Yii::app()->request->getParam('DocumentID',null);
     $reqDocFlowID = Yii::app()->request->getParam('DocFlowID',null);
     $docs = array();
     $doc_models = array();
     $dept_list = array();
     foreach (Departments::model()->findAll('(idDepartment NOT IN ('
       . implode(',',$this->my_dept_ids).')) '
       . 'AND (idDepartment > 1) ORDER BY DepartmentName ASC') as $dpt){
       /* @var $dpt Departments */
       $dept_list[$dpt->idDepartment]['name'] = $dpt->DepartmentName;
       $dept_list[$dpt->idDepartment]['desc'] = $dpt->FunctionDescription;
     }
     if (is_numeric($reqDocumentID)){
       $doc_models = Documents::getAllMyDocs($reqDocumentID,0);
     }
     if (is_numeric($reqDocFlowID)){
       $doc_models = Documents::getAllMyDocs(0,$reqDocFlowID);
       $dfmodel = Docflows::model()->findByPk($reqDocFlowID);
       if ($dfmodel && count($doc_models) > 0){
         $model->attributes = $dfmodel->attributes;
         $model->ExpirationDate = date('d.m.Y',strtotime($dfmodel->ExpirationDate));
       }
     }
     if (!empty($doc_models)){
       foreach ($doc_models as $doc_model){
         $docs [$doc_model->idDocument] = ((!$doc_model->DocumentInputNumber)? 
                 $doc_model->DocumentName : $doc_model->DocumentInputNumber) 
         . ' : ' . $doc_model->DocumentDescription;
       }
     }
     if (!$model->DocFlowName){
       $model->DocFlowName = "автоматично";
     }
     $this->render('/docflows/createflow', array(
       'model' => $model,
       'docs' => $docs,
       'dept_list' => $dept_list,
     ));     
  }

  /**
   * Видалення процесу документообігу.
   * @param integer ID розсилки
   */
  public function actionDeleteflow($id = false) {
    /* @var $model Docflows */
    $reqAjax = Yii::app()->request->getPost('ajax', null);
    $reqRetUrl = Yii::app()->request->getParam('url', null);
    $reqId = Yii::app()->request->getParam('id', null);
    if (!$id && is_numeric($reqId)){
      $id = $reqId;
    }
    $model = Docflows::model()->findByPk($id);
    if (!Yii::app()->user->checkAccess('showDirectiries')) {
      $this->CheckAccess($model->docFlowGroup->OwnerID);
    }
    $model->delete();
    if ($reqAjax === null && $reqRetUrl) {
      $this->redirect($reqRetUrl);
    }
  }
  
  /**
   * 'Перероблено
   * Перегляд відповідей і стану ознайомлення респондентів з документами певної розсилки
   */
  public function actionDocflowwatch($id = 0) {
    $flow = Docflows::model()->findByPk($id);
    if (!$flow){
      throw new CHttpException(400, 
        'Не знайдено.');      
    }
    $model = new StatAll();
    $model->unsetAttributes();
    $model->idDocFlow = $id;
    $flowname = $flow->DocFlowName;
    //$model->OwnerID = Yii::app()->user->id;
    $this->layout = '//layouts/docflow';
    $this->render('/docflows/docflow_watch', array(
        'model' => $model,
        'flowname' => $flowname,
    ));
  }
  
  /**
   * Метод асинхронно оновлює статус розсилки.
   * @param integer $id docflows.idDocFlow
   */
  public function actionAjaxupdatestatus($id){
    if (is_numeric($id)){
      $status_id = $this->UpdateDocflowStatus($id);
      $model = Docflowstatus::model()->findByPk($status_id);
      $data = array();
      $data['DocFlowStatusName'] =  $model->DocFlowStatusName;
      $data['idDocFlow'] = $id;
      echo CJSON::encode($data);
    }
  }
  
  /**
   * Метод асинхронно оновлює інформацію про респондентів розсилки.
   * @param integer $id docflows.idDocFlow
   */
  public function actionAjaxdocflowrespondents($id){
    if (is_numeric($id)){
      $model = Docflows::model()->findByPk($id);
      if (!$model){
        return ;
      }
      $dept_html_list = '';
      foreach ($model->docFlowGroup->departments as $respdept){
        $answer = $respdept->getAnswerToDocflow($model->idDocFlow);
        $dept_html_list .= '<li id="'.$model->idDocFlow.'_'.$respdept->idDepartment.'" '
            .'class="'
              .(($answer)? 
                'already_answered':'not_answered_yet')
              .'" '
            .'title="'
              .(($answer)? 
                'Відповідь надано '.date('d.m.Y h:i:s',strtotime($answer->AnswerTimestamp)) 
                : 'Немає відповіді або не інформовано'
              )
              .' ('.implode(',',$respdept->getUsernames()).')"'
            .'>'
            .$respdept->DepartmentName.'</li>';
      }
      $data = array();
      $data['idDocFlow'] = $id;
      $data['dept_html_list'] = $dept_html_list;
      echo CJSON::encode($data);
    }
  }
  
  /**
   * Метод створення форми пошуку серед розсилок.
   */
  public function actionSearch(){
    $reqViewMode = Yii::app()->request->getParam('mode',null);
    $model = new Docflows();
    $this->render('/docflows/search',array(
      'model' => $model,
      'mode' => $reqViewMode,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Docflows::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'docflows-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}
