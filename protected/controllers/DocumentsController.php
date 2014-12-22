<?php

class DocumentsController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/docflow';
  public $defaultAction = 'index';
  public $showAddversionBlock = false;

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
        array('allow', // allow ok
            'actions' => array('update', 'createnew', 'index',
                'deleteversion', 'deletedocument', 'doclist',
                'cardprint', 'cardprintback',
                'autonum', 'doclisttoxls', 'select2','updateEditable',
                'search','updateEditableCard', 'AjaxItems', 'AjaxDocAnswers'),
            'users' => array("@"),
        ),
        array('allow', // allow ok
            'actions' => array('rept1','rept2'),
            'users' => array("*"),
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Метод відображення усіх документів підрозділу
   * @param integer $id documents.idDocument
   */
  public function actionIndex($id = 0){
    $reqDocumentCategoryID = Yii::app()->request->getParam('DocumentCategoryID',null);
    $reqDocuments = Yii::app()->request->getParam('Documents',null);
    //чи показувати лише власні документи
    $reqOwn = Yii::app()->request->getParam('own',null);
    //чи показувати документ (зберегти його?)
    $reqSave = Yii::app()->request->getParam('Save',null);
    //лише не збережені документи
    $reqHidden = Yii::app()->request->getParam('hidden',null);
    if (!$id){
      $reqId = intval(Yii::app()->request->getParam('id',0));
    } else { $reqId = intval($id);}
    if ($id > 0 && !empty($reqSave)){
      //якщо вказівка зберегти документ, то просто призначаємо, щоб він був видимий
      $model = Documents::model()->findByPk($id);
      $model->DocumentVisibility = 1;
      $model->save();
      $this->redirect(Yii::app()->CreateUrl('/documents/index'));
    }
    $model = new Documents;
    $model->unsetAttributes();
    $model->DocumentVisibility = ($reqHidden)? 0:1;
    $model->MyDeptIDs = implode(',',$this->my_dept_ids);
    if ($reqDocuments){
      //встановлюємо вхідні атрибути моделі
      foreach ($reqDocuments as $rqkey => $rqdoc){
        $model->$rqkey = $rqdoc;
      }
    }
    if (is_numeric($reqId) && $reqId > 0){
      $model->idDocument = $reqId;
      $this->showAddversionBlock = true;
    }
    if (is_numeric($reqDocumentCategoryID)){
      $model->DocumentCategoryID = $reqDocumentCategoryID;
    }
    if ($reqOwn !== null){
      //якщо показувати лише власноруч додані, а не всього підрозділу
      $model->UserID = Yii::app()->user->id;
    }
    //власне пошук
    $data = $model->search_rel();
    $this->layout = '//layouts/docflow';
    $curr_cat = '';
    if (is_numeric($reqDocumentCategoryID)){
      $curr_cat = Documentcategory::model()->findByPk($reqDocumentCategoryID)->DocumentCategoryName;
    }
    $doc_cats = Documentcategory::DropDown();
    $this->render('/documents/documentsview', array(
       'data' => $data,
       'model' => $model,
       'doc_cats' => $doc_cats,
       'current_cat' => $curr_cat,
    ));
  }

  /**
   * Створення нового документа.
   * @throws CHttpException
   */
  public function actionCreatenew() {
    $reqJustCreatedDocID = Yii::app()->request->getParam('JustCreatedDocID',null);
    $fmodel = new Files();
    if (!$reqJustCreatedDocID){
      //якщо документ ще не створено, то створити і показати форму для змін атрибутів
      $dmodel = new Documents();
      $dmodel->DocumentCategoryID = 14;
      $dmodel->DocumentTypeID = 1;
      $dmodel->UserID = Yii::app()->user->id;
      $dmodel->DocumentName = 'Натисніть, щоб ввести значення';
      if (!$dmodel->save()){
        throw new CHttpException(404, 'Помилка при створенні початкових даних нового документа.');
      }
      $this->redirect(Yii::app()->CreateUrl('/documents/createnew',array('JustCreatedDocID' => $dmodel->idDocument)));
    } else {
      $dmodel = Documents::model()->findByPk(intval($reqJustCreatedDocID));
    }

    $this->layout = '//layouts/docflow';
    $this->render('//documents/createdocument', array(
        'model' => $dmodel,
        'fmodel' => $fmodel,
        'cat_list' => Documentcategory::DropDown(),
        'type_list' => Documenttype::DropDown()
    ));
  } 

  /**
   * Метод видалення документа.
   * @param integer $id ID : documents.idDocument
   * @throws CHttpException
   */
  public function actionDeletedocument($id = 0) {
    $returl = Yii::app()->request->getParam('returl',Yii::app()->CreateUrl('/site/index'));
    if (!$id){
      $id = Yii::app()->request->getParam('id',0);
    }
    $dmodel = Documents::model()->findByPk($id);
    if (!$dmodel){
        throw new CHttpException(404, 'Документ з ІД '
              . $id . ' не існує.');
    }
    //перевірка прав доступу (лише той, хто додав документ, може його видалити)
    $this->CheckAccess($dmodel->UserID);
    $dmodel->delete();
    $this->redirect($returl);
  }
  
//  /**
//   * Метод видаляє усі пусті або помилково створені документи без файлів
//   * не пізніше 10 хвилин тому
//   * @return boolean
//   */
//  protected function deleteEmptyDocs(){
//    $criteria = new CDbCriteria();
//    $criteria->addCondition('DATE_ADD(Created, INTERVAL 10 MINUTE) <= NOW()');
//    $criteria->addCondition('DocumentDescription IS NULL');
//    $criteria->addCondition('(SELECT df.FileID FROM documentfiles df '
//            . 'WHERE df.DocumentID=idDocument) IS NULL');
//    $criteria->addCondition('(SELECT dfd.DocFlowID FROM docflowdocs dfd '
//            . 'WHERE dfd.DocumentID=idDocument) IS NULL');
//    return Documents::model()->deleteAll($criteria);
//  }
  
  /**
   * Метод видалення версії документа.
   * @param integer $id ID : documentfiles.idDocumentFiles
   * @throws CHttpException
   */
  public function actionDeleteversion($id){
    $returl = Yii::app()->request->getParam('returl',Yii::app()->CreateUrl('/site/index'));
    $dfmodel = DocumentFiles::model()->findByPk($id);
    if ($dfmodel){
      $file = Files::model()->findByPk($dfmodel->FileID);
      $dfmodel->delete();
      $file->delete();
    }
    $this->redirect($returl.'#doc-'.$dfmodel->document->idDocument);
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Documents::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Метод формування таблиці документів і
   * повернення її як Excel-документа. ЖУРНАЛ.
   */
  public function actionDoclisttoxls() {
    $defaultLimitValue = 100;
    $reqLimit = Yii::app()->request->getParam('limit', $defaultLimitValue);
    $reqDateFrom = Yii::app()->request->getParam('datefrom', null);
    $reqDateTo = Yii::app()->request->getParam('dateto', null);

    if (!is_numeric($reqLimit) || !$reqLimit) {
      $reqLimit = 100;
    }

    //Перетворення дат з формату d.m.Y у формат Y-m-d
    if ($reqDateFrom) {
      $t = strtotime(str_replace('.', '-', $reqDateFrom));
      $reqDateFrom = date('Y-m-d', $t);
    }
    if ($reqDateTo) {
      $t = strtotime(str_replace('.', '-', $reqDateTo));
      $reqDateTo = date('Y-m-d', $t);
    }

    $reqCategory = Yii::app()->request->getParam('category', null);
    $this->layout = 'clear';
    //Це було непросто
    $models = Documents::model()->findAll('UserID IN (('
            . 'SELECT userdepartment.UserID '
            . 'FROM userdepartment WHERE DeptID IN'
            . '((SELECT ud.DeptID FROM userdepartment AS ud '
            . '  WHERE ud.UserID=' . Yii::app()->user->id
            . ')) ))'
            . ' AND ' . (($reqCategory && is_numeric($reqCategory)) ? 'DocumentCategoryID=' . $reqCategory : '1')
            . ' AND Created'
            . ' BETWEEN STR_TO_DATE("' . $reqDateFrom . ' 00:00:00",  "%Y-%m-%d %H:%i:%s" ) '
            . '  AND STR_TO_DATE("' . $reqDateTo . ' 23:59:59",  "%Y-%m-%d %H:%i:%s" )'
            . '  AND DocumentVisibility > 0'
            . ' ORDER BY Created DESC LIMIT ' . $reqLimit);
    $this->render('//documents/xlsdoclist', array(
        'models' => $models
    ));
  }
  
  public function actionRept1($year=''){
    $data = array();
    $year = Yii::app()->request->getParam('year',date('Y'));
    $year = date('Y',strtotime($year.'-01-01'));
    $data= Yii::app()->db->createCommand('select 
 dcc.DocumentCategoryName as `cat`,
(select count(idDocument)
from  documentcategory dc  left join documents on dc.idDocumentCategory=documents.DocumentCategoryID left join userdepartment ud on ud.UserID=documents.UserID where ((Created > "'.$year.'-01-01" and Created <= "'.$year.'-12-31" and ud.DeptID in (46,121,119,118)) ) and dc.idDocumentCategory=dcc.idDocumentCategory and DocumentVisibility is not null group by idDocumentCategory
) as `at_all`,

(select count(idDocument)
from  documentcategory dc  left join documents on dc.idDocumentCategory=documents.DocumentCategoryID left join userdepartment ud on ud.UserID=documents.UserID where ((Created > "'.$year.'-01-01" and Created <= "'.$year.'-12-31" and ud.DeptID in (46,121,119,118) and documents.ControlField IS NOT NULL and ControlField not like "")) and dc.idDocumentCategory=dcc.idDocumentCategory 
 and DocumentVisibility is not null group by idDocumentCategory  
) as `control_mark`,

(select count(idDocument)
from  documentcategory dc  left join documents on dc.idDocumentCategory=documents.DocumentCategoryID left join userdepartment ud on ud.UserID=documents.UserID where ((Created > "'.$year.'-01-01" and Created <= "'.$year.'-12-31" and ud.DeptID in (46,121,119,118) and documents.ControlField IS NOT NULL and ControlField not like "" and mark is not null and mark not like "")) and dc.idDocumentCategory=dcc.idDocumentCategory  
 and DocumentVisibility is not null group by idDocumentCategory
) as `done_mark`
 from  documentcategory dcc')->queryAll();
    $this->layout = 'clear';
    //var_dump($data);exit();
    $this->render('//documents/xls_rept1',array('data'=>$data,
    'year' => $year
    ));
  }
  
  public function actionRept2(){
    $data = array();
    $year = Yii::app()->request->getParam('year',date('Y'));
    $year = date('Y',strtotime($year.'-01-01'));
    $data= Yii::app()->db->createCommand("
select 
idDepartment,
DepartmentName,
(select count(distinct dfd.DocumentID) from 
  docflowgroupdepts dfgdp 
  join docflowgroups dfg on dfgdp.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflows df on df.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflowdocs dfd on dfd.DocFlowID=df.idDocFlow 
  join documents docs on docs.idDocument=dfd.DocumentID 
 where dfgdp.DeptID=departments.idDepartment 
  and not( docs.ControlField = '' or docs.ControlField is null) 
  and dfg.OwnerID in (select UserID from userdepartment where DeptID = 46)
) as zagalom,

(select count(distinct dfd.DocumentID) from 
  docflowgroupdepts dfgdp 
  join docflowgroups dfg on dfgdp.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflows df on df.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflowdocs dfd on dfd.DocFlowID=df.idDocFlow 
  join documents docs on docs.idDocument=dfd.DocumentID 
 where dfgdp.DeptID=departments.idDepartment 
  and not( docs.ControlField = '' or docs.ControlField is null) 
  and docs.DocumentInputNumber like '%.".$year."%' 
  and dfg.OwnerID in (select UserID from userdepartment where DeptID = 46)
) as za_rik,

(select count(distinct dfd.DocumentID) from 
  docflowgroupdepts dfgdp 
  join docflowgroups dfg on dfgdp.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflows df on df.DocFlowGroupID=dfg.idDocFlowGroup 
  left join docflowdocs dfd on dfd.DocFlowID=df.idDocFlow 
  join documents docs on docs.idDocument=dfd.DocumentID 
 where dfgdp.DeptID=departments.idDepartment 
  and not( docs.ControlField = '' or docs.ControlField is null) 
  and docs.DocumentInputNumber like '%.".$year."%' 
  and not(docs.mark = '' or docs.mark is null) 
  and dfg.OwnerID in (select UserID from userdepartment where DeptID = 46)
) as vykonano

from departments 
where idDepartment not in (136,120,123,125,127,126,129,124,128,130,122,118,119,132,133,1,121) 
order by DepartmentName
")->queryAll();
    $this->layout = 'clear';
    //var_dump($data);exit();
    $this->render('//documents/xls_rept2',array('data'=>$data,
    'year' => $year
    ));
  }

  /**
   * Метод відображення картки документів для друку.
   * @param integer $id ID : documents.idDocument
   */
  public function actionCardprint($id) {
    $model = Documents::model()->findByPk($id);
    if (!$model) {
      $this->redirect('/documents/index');
    }
    $this->layout = 'clear';
    $this->render('//documents/cardprint', array(
        'model' => $model
    ));
  }

  /**
   * Метод відображення зворотньої сторони картки документів для друку.
   * @param integer $id ID : documents.idDocument
   * @author Valeriy Efimov <valera_e@ukr.net>
   */
  public function actionCardprintback($id) {
    $model = ControlMark::model()->find('DocumentID=:DocumentID',
            array(':DocumentID' => $id));
    $dmodel = Documents::model()->findByPk($id);
    if ($dmodel && !Yii::app()->request->getParam('print',false) && 
            Yii::app()->user->id == (($dmodel)? $dmodel->UserID : 0)){
      $this->layout = '//layouts/docflow';
      if (!$model){
        $model = new ControlMark;
        $model->DocumentID = $dmodel->idDocument;
        $model->save();
      }
      $this->render('//documents/cardback', array(
          'dmodel' => $dmodel,
          'model' => $model,
      ));
    }
    if ($dmodel && (Yii::app()->request->getParam('print',false) || 
            Yii::app()->user->id != (($dmodel)? $dmodel->UserID : 0) )){
      $this->layout = 'clear';
      $this->render('//documents/cardprintback', array(
          'model' => $model
      ));
    }
    if (!$dmodel){
      throw new CHttpException(404, 'Документ з ІД '
            . $id . ' не існує.');
    }
  }

  /**
   * Метод формування дати надходження та індекса документа
   * @param integer $id ID : documentcategory.idDocumentCategory
   * @return string
   */
  protected function ajaxMakeInputNumber($id) {
    $model = Documentcategory::model()->findByPk($id);
    if (!$model) {
      return;
    }
    $n = preg_match('/\[(.+)\]/', $model->DocumentCategoryName, $matches);
    if (!$n) {
      return;
    }
    $number = '';
    $postfix = $matches[1];
    $criteria = new CDbCriteria();
    $criteria->compare('DocumentCategoryID', $model->idDocumentCategory);
    $criteria->addCondition('DocumentInputNumber NOT LIKE ""');
    $criteria->order = 'Created DESC';
    $docs = Documents::model()->findAll($criteria);
    $i = 0;
    foreach ($docs as $doc) {
      $n = preg_match('/([0-9]+)[\/|\\\].+/', $doc->DocumentInputNumber, $matches);
      if ($n) {
        $next_n = (integer) $matches[1] + 1;
        $number = '№ ' . $next_n . '/' . $postfix . ' від ' . date('d.m.Y');
        return $number;
      }
    }
    $number = '№ 1/' . $postfix . ' від ' . date('d.m.Y');
    return $number;
  }

  /**
    * Метод : автоматично згенерувати дату надходження та індекс документа і перейти на список документів.
    * @param integer $id ID : documents.idDocument
  */
  public function actionAutonum($id){
    if (is_numeric($id)){
      $model = Documents::model()->findByPk($id);
      if ($model){
        $cat_id = $model->DocumentCategoryID;
        $autonum = $this->ajaxMakeInputNumber($cat_id);
        if ($autonum){
          $model->DocumentInputNumber = $autonum;
        }
      }
      $model->DocumentVisibility = 1;
      $model->save();
    }
    $this->redirect(Yii::app()->CreateUrl('/documents/index'));
  }

  /**
   * Асинх. формування списку документів для відображення у віджеті select2
   * @param string $q параметр для пошуку (токен)
   */
  public function actionSelect2($q){
    $model = new Documents;
    $MydeptIDs = $this->my_dept_ids;
    $MydeptUsersID = array();
    $Myudeptusers = Userdepartment::model()->findAll('DeptID IN (' . implode(',', $MydeptIDs) . ')');
    foreach ($Myudeptusers as $ud) {
      $MydeptUsersID[] = $ud->UserID;
    }

    $criteria = new CDbCriteria();
    $criteria->with = array('docflows',
        'docflows.docFlowGroup' => array('select' => false),
        'docflows.docFlowGroup.departments' => array('select' => false),
    );
    $criteria->group = 't.idDocument';
    $criteria->together = true;
    $criteria->order = 't.idDocument DESC';
    $criteria->limit = 50;
    $criteria->addCondition('departments.idDepartment IN (' . implode(',', $MydeptIDs) .
            ') OR t.UserID IN (' . implode(',', $MydeptUsersID) . ')');
    $criteria->compare('CONCAT(t.DocumentInputNumber," ",t.DocumentDescription)',$q,true);
    $models = $model->findAll($criteria);
    $res = array();
    foreach ($models as $dmodel){
      $res[] = array(
        'text' => (($dmodel->DocumentInputNumber)? $dmodel->DocumentInputNumber.' : ' : '') .
          (($dmodel->DocumentDescription)? $dmodel->DocumentDescription : ' Документ #'.$dmodel->idDocument),
        'id' => $dmodel->idDocument
      );
    }
    echo CJSON::encode($res);
  }

  /**
   * Для віджетів x-editable
   */
  public function actionUpdateEditable(){
    $es = new EditableSaver('Documents');
    $es->update();
  }

  /**
   * Для віджетів x-editable
   */
  public function actionUpdateEditableCard(){
    $es = new EditableSaver('ControlMark');
    $es->update();
  }
  
  /**
   * Метод створення форми для пошуку серед документів. 
   */
  public function actionSearch(){
    $model = new Documents();
    $this->render('/documents/search',array(
      'model' => $model,
    ));
  }
  
  /**
   * Метод для формування списку значень окремого атрибуту для автодоповення
   */
  public function actionAjaxItems(){
    $items = array();
    $attr = Yii::app()->request->getParam('attr','DocumentName');
    $q = Yii::app()->request->getParam('term',null);
    $crt = new CDbCriteria();
    $crt->compare('UserID',Yii::app()->user->id);
    $crt->compare($attr,$q,true);
    $crt->select = array($attr);
    $crt->distinct = true;
    $models = Documents::model()->findAll($crt);
    unset($crt);
    foreach ($models as $doc){
      if (!empty($doc->$attr)){
      $items[] = $doc->$attr;
      }
    }
    echo CJSON::encode($items);
  }
  
  /**
   * Метод асинх. формування таблиці інформування або відповідей по розсилкам документа
   * ДОВГО працює - дані бере із MySQL-розрізу (VIEW)
   * @return
   */
  public function actionAjaxDocAnswers(){
    $reqDocID = Yii::app()->request->getParam('DocID',null);
    
    $dmodel = Documents::model()->findByPk($reqDocID);
    if (!$reqDocID || !$dmodel || !$this->CheckDeptAccess($dmodel->UserID,false)){
      echo 'Перегляд неможливий';
      return ;
    }
    $model = new StatAll();
    $model->unsetAttributes();
    $model->DocIDs = $reqDocID;
    if (!Docflowdocs::model()->count('DocumentID='.intval($model->DocIDs))){
      echo 'Розсилки відсутні';
      return ;
    }
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'docflow-watch-grid-'.$reqDocID,
        'dataProvider' => $model->search(),
        'emptyText' => 'Розсилки цього документа відсутні',
        'columns' => array(
            array(
                'header' => 'Розпочато',
                'filter' => false,
                'value' => 'CHtml::link($data->FlowCreated,
                  Yii::app()->CreateUrl("/docflows/index",array("id"=>$data->idDocFlow)),
                  array("title" => "Ініціював користувач: ".$data->Initiator))',
                'type' => 'raw',),
            array(
                'header' => 'Респондент',
                'filter' => false,
                'value' => 'empty($data->AnswerCreated) ? 
                  "<span style=\'color: red;\'>".$data->RespDeptName."</span>" 
                  :
                  "<span style=\'color: green;\'>".$data->RespDeptName."</span>"',
                'type' => 'raw',),
            array(
                'value' => 'empty($data->AnswerCreated) ? 
                  "<span style=\'color: red;\'>немає</span>" 
                  :$data->AnswerCreated',
                'header' => 'Інформовано',
                'type' => 'raw'),
            array(
                'header' => 'Коментар',
                'value' => '(!empty($data->AnswerComment))? $data->AnswerComment:""'),
            array(
                'header' => 'Док. у відповідь',
                'value' => function ($data){ echo ((!$data->AnswerDocID)? "немає" :
                CHtml::link('переглянути',
                 Yii::app()->CreateUrl('documents/index',
                   array('id'=>$data->AnswerDocID)
                ))); },
                'type' => 'raw',
            ),
        ),
        'htmlOptions' => array('style' => 'font-size: 8pt;'),
    ));
  }
  
}
