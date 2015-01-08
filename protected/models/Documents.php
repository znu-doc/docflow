<?php

/**
 * This is the model class for table "documents".
 *
 * The followings are the available columns in table 'documents':
 * @property integer $idDocument
 * @property string $DocumentName
 * @property string $DocumentDescription
 * @property integer $UserID
 * @property string $DocumentInputNumber
 * @property string $DocumentOutputNumber
 * @property string $signed
 * @property string $DocumentForWhom
 * @property string $Correspondent
 * @property string $ControlField
 * @property string $mark
 * @property integer $DocumentCategoryID
 * @property integer $DocumentTypeID
 * @property string $Created
 * @property integer $DocumentVisibility
 * @property string $SubmissionDate
 *
 * The followings are the available model relations:
 * @property Docflowanswers[] $docflowanswers
 * @property Docflowdocs[] $docflowdocs
 * @property DocumentFiles[] $documentfiles
 * @property User $user
 * @property Documentcategory $category
 * @property Documenttype $type
 * @property Docflows $docflows
 */
class Documents extends CActiveRecord {

  public $searchDocflow;
  public $ControlDateField;
  public $searchDocflowGroup;
  public $searchDept;
  public $searchField;
  public $MyDeptIDs;
  public $DocYear;
  public $control_only;
//  public $searchDeptAnswer;
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Documents the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'documents';
  }
  
  public function beforeDelete(){
    foreach ($this->docflows as $df){
      $df->delete();
    }
    foreach ($this->dfiles as $dfl){
      $dfl->delete();
    }
    Docflowdocs::model()->deleteAll('DocumentID='.$this->idDocument);
    DocumentFiles::model()->deleteAll('DocumentID='.$this->idDocument);
    Docflowanswers::model()->deleteAll('DocumentID='.$this->idDocument);
    //видалення всіх контрольних відміток
    ControlMark::model()->deleteAll('DocumentID = ' . $this->idDocument);
    return parent::beforeDelete();
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocumentName', 'required'),
        array('UserID, DocumentCategoryID, DocumentTypeID, DocumentVisibility', 
          'numerical', 'integerOnly' => true),
        array('Created, SubmissionDate', 'length', 'max' => 128),
        array('DocumentName, DocumentInputNumber, DocumentOutputNumber, signed, DocumentForWhom, mark, '
           . 'Correspondent, ControlField', 
          'length', 'max' => 255),
        array('DocumentDescription', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocument, DocumentName, DocumentDescription, UserID, DocumentInputNumber, '
            . 'DocumentOutputNumber, signed, DocumentForWhom, DocumentCategoryID, DocumentTypeID,'
            . 'Correspondent, mark, Created, DocumentVisibility, ControlField, SubmissionDate', 
           'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'docflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowID', 'through' => 'docflowdocs'),
        'docflowanswers' => array(self::HAS_MANY, 'Docflowanswers', 'DocumentID'),
        'docflowdocs' => array(self::HAS_MANY, 'Docflowdocs', 'DocumentID'),
        'documentfiles' => array(self::HAS_MANY, 'DocumentFiles', 'DocumentID'),
        'dfiles' => array(self::HAS_MANY, 'Files', 'FileID', 'through' => 'documentfiles'),
        'user' => array(self::BELONGS_TO, 'User', 'UserID'),
        'type' => array(self::BELONGS_TO, 'Documenttype', 'DocumentTypeID'),
        'category' => array(self::BELONGS_TO, 'Documentcategory', 'DocumentCategoryID'),
    );
  }
  
  /**
   * Метод-тригер - спрацьовує після знаходження окремої моделі
   * Поки що пустий
   * @return type
   */
  public function afterFind() {
    return parent::afterFind();
  }


  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocument' => 'Id Document',
        'DocumentName' => 'Назва документа',
        'DocumentDescription' => 'Зміст',
        'UserID' => 'Користувач, що додав документ',
        'DocumentInputNumber' => 'Дата надходж. та індекс док.',
        'DocumentOutputNumber' => 'Дата та індекс документа',
        'signed' => 'Підписано',
        'DocumentForWhom' => 'Резолюція або кому надісл. док.',
        'DocumentCategoryID' => 'Категорія документа',
        'DocumentTypeID' => 'Тип документа',
        'Correspondent' => 'Кореспондент',
        'mark' => 'Відмітки про виконання',
        "Created" => "Створено",
        "DocumentVisibility" => "DocumentVisibility",
        "SubmissionDate" => "Дата надходження",
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;

    if (empty($this->UserID) || is_numeric($this->UserID)) {
      $UserID = "";
      $criteria->compare('UserID', $this->UserID);
    } else {
      $item = User::model()->find('username LIKE \'%' . $this->UserID . '%\'');
      $UserID = $item->id;
      $criteria->compare('UserID', $UserID);
    }

    $criteria->compare('idDocument', $this->idDocument);
    $criteria->compare('DocumentTypeID', $this->DocumentTypeID);
    $criteria->compare('DocumentCategoryID', $this->DocumentCategoryID);
    $criteria->compare('DocumentName', $this->DocumentName, true);
    $criteria->compare('DocumentDescription', $this->DocumentDescription, true);
    $criteria->compare('DocumentInputNumber', $this->DocumentInputNumber, true);
    $criteria->compare('DocumentOutputNumber', $this->DocumentOutputNumber, true);
    $criteria->compare('ControlField', $this->ControlField, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * special search with related specifications
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search_rel() {
    $criteria = new CDbCriteria();
    $criteria->with = array(
       'user',
       'user.userdepartments' => array('select' => false),
       'type',
       'category',
       'documentfiles' => array('select' => false),
       'docflows',
       'docflows.docFlowGroup' => array('select' => false),
       'docflows.docFlowGroup.departments' => array('select' => false),
       'docflowanswers' => array('select' => false),
    );
    
    if (!Yii::app()->user->CheckAccess('CanWatchAllDocs')) {
      //лише документи, які створені підрозділом або надійшли розсилками або
      //надійшли разом з відповіддю
      $criteria->addCondition('departments.idDepartment IN (' . $this->MyDeptIDs
              . ')  OR userdepartments.DeptID IN (' . $this->MyDeptIDs
              . ')  OR docflowanswers.DocFlowID IN '
              . '((SELECT df.idDocFlow FROM docflows AS df, docflowgroups as dfg '
              . 'WHERE (dfg.OwnerID=' . Yii::app()->user->id . ') AND (df.DocFlowGroupID=dfg.idDocFlowGroup) ))');
    }
    if ($this->searchField){
      //якщо є пошуковий токен, то врахувати його
      $criteria->compare('CONCAT_WS(" ",t.DocumentName,t.DocumentDescription,'
          . 't.Correspondent,t.Created,t.DocumentInputNumber,t.DocumentOutputNumber,t.signed,t.DocumentForWhom, '
              . 't.ControlField)'
        ,$this->searchField,true);
    }
    if ($this->ControlDateField){
      //якщо є пошуковий токен контролю (окреме поле), то врахувати його
      $criteria->compare('t.ControlField'
        ,$this->ControlDateField,true);
    }
    if ($this->control_only){
      $criteria->addCondition("trim(if(isnull(t.ControlField),'',t.ControlField)) not like '' 
	and trim(if(isnull(t.mark),'',t.mark)) like '' ");
    }
    $criteria->compare('DocumentCategoryID',$this->DocumentCategoryID);
    $criteria->compare('t.idDocument', $this->idDocument);
    $criteria->compare('t.DocumentName', $this->DocumentName, true);
    $criteria->compare('t.DocumentDescription', $this->DocumentDescription, true);
    $criteria->compare('t.DocumentInputNumber', $this->DocumentInputNumber, true);
    $criteria->compare('t.DocumentOutputNumber', $this->DocumentOutputNumber, true);
    $criteria->compare('t.Correspondent', $this->Correspondent, true);
    $criteria->compare('t.DocumentForWhom', $this->DocumentForWhom, true);
    $criteria->compare('t.signed', $this->signed, true);
    $criteria->compare('t.Created', $this->Created, true);
    $criteria->compare('t.SubmissionDate', $this->SubmissionDate, true);
    $criteria->compare('t.UserID', $this->UserID);
    $criteria->compare('t.DocumentTypeID', $this->DocumentTypeID);
    if ($this->DocumentVisibility && !$this->idDocument){
      //врахування "видимості" документа (незбережені нормально не показуються)
      $criteria->compare('t.DocumentVisibility', $this->DocumentVisibility);
    } else if (!$this->idDocument) {
      $criteria->addCondition('t.DocumentVisibility IS NULL OR t.DocumentVisibility=0');
    }
    if ($this->DocYear){
      $criteria->compare('if(isnull(t.SubmissionDate),t.Created,t.SubmissionDate)',$this->DocYear,true);
    } else if (!$this->idDocument) {
      $criteria->compare('if(isnull(t.SubmissionDate),t.Created,t.SubmissionDate)',date('Y'),true);
    }
    if (!empty($this->searchDocflow)){
      $criteria->compare('docflows.ControlDate', $this->searchDocflow->ControlDate, true);
      $criteria->compare('docflows.idDocFlow', $this->searchDocflow->idDocFlow);
    }
    if (!empty($this->searchDocflowGroup)){
      $criteria->compare('docFlowGroup.DocflowGroupName', $this->searchDocflowGroup->DocflowGroupName, true);
      $criteria->compare('departments.DepartmentName', $this->searchDept->DepartmentName, true);
    }
    $criteria->together = true;
    $criteria->group = 't.idDocument';
    $data = new CActiveDataProvider($this,array(
      'criteria' => $criteria,
      'sort' => array(
        'defaultOrder' => array(
            'Created' => CSort::SORT_DESC,
        ),
      ),
      'pagination' => array(
          'pageSize' => 10
      )
    ));
    return $data;
  }

  /**
   * Метод повертає ID файлу, який є останньою версією документа.
   * @param integer $idDocument ID : documents.idDocument
   * @return integer
   */
  public static function LastDocVersion($idDocument=0) {
    if (!$idDocument){
        $model = $this;
    }
    $model = Documents::model()->findByPk($idDocument);
    if (!$model){
        return 0;
    }
    $file_id = 0;
    foreach ($model->documentfiles as $df) {
      if ($file_id < $df->FileID) {
        $file_id = $df->FileID;
      }
    }
    return $file_id;
  }

  /**
   * Метод повертає масив моделей документів, 
   * до яких має доступ користувач.
   * @param integer $id ID : documents.idDocument
   * @return Documents[]
   */
  public static function getAllMyDocs($id=0,$DocFlowID=0) {
    $model = new Documents;
    $Myudepts = Userdepartment::model()->findAllByAttributes(
            array('UserID' => Yii::app()->user->id));
    $MydeptIDs = array();
    foreach ($Myudepts as $Myudept) {
      $MydeptIDs[] = $Myudept->DeptID;
    }
    $MydeptUsersID = array();
    $Myudeptusers = Userdepartment::model()->findAll('DeptID IN (' . implode(',', $MydeptIDs) . ')');
    foreach ($Myudeptusers as $ud) {
      $MydeptUsersID[] = $ud->UserID;
    }

    $criteria = new CDbCriteria();
    $criteria->with = array('docflows',
        'docflows.docFlowGroup' => array('select' => false),
        'docflows.docFlowGroup.departments' => array('select' => false),
        'docflowanswers' => array('select' => false),
    );
    if ($id > 0){
      $criteria->compare('t.idDocument',$id);
    }
    if ($DocFlowID > 0){
      $criteria->compare('docflows.idDocFlow',$DocFlowID);
    }
    $criteria->group = 't.idDocument';
    $criteria->together = true;
    $criteria->order = 't.idDocument DESC';
    $criteria->addCondition('departments.idDepartment IN (' . implode(',', $MydeptIDs) .
            ') OR t.UserID IN (' . implode(',', $MydeptUsersID)
              . ')  OR docflowanswers.DocFlowID IN '
              . '((SELECT df.idDocFlow FROM docflows AS df, docflowgroups as dfg '
              . 'WHERE (dfg.OwnerID=' . Yii::app()->user->id . ') AND (df.DocFlowGroupID=dfg.idDocFlowGroup) ))');
    $models = $model->findAll($criteria);
    return $models;
  }
  
  /**
   * Метод повертає кількість розсилок певного (поточного) документа, які доступні для перегляду поточному підрозділу
   * @return integer
   */
  public function MyDeptDocflowsCount(){
    $cntcriteria = new CDbCriteria();
    $cntcriteria->with = array(
      'documents' => array('select' => false),
      'docFlowGroup',
      'docFlowGroup.owner',
      'docFlowGroup.owner.userdepartments' => array('select' => false),
      'docFlowGroup.docflowgroupdepts' => array('select' => false),
    ); 
    $cntcriteria->together = true;
    $cntcriteria->group = 't.idDocFlow';
    $my_dept_ids = $this->MyDeptIDs;
    $cntcriteria->addCondition('docflowgroupdepts.DeptID IN ('.$my_dept_ids.') OR '
      .'userdepartments.DeptID IN ('.$my_dept_ids.')');
    $cntcriteria->compare('documents.idDocument',$this->idDocument);
    return Docflows::model()->count($cntcriteria);
  }  
  
  /**
   * Метод повертає кількість існуючих файлів, пов`язаних з поточним документом
   * @return integer
   */
  public function RealFilesCount(){
    $docfiles = $this->documentfiles;
    $cnt = 0;
    foreach ($docfiles as $df){
      $cnt += ($df->file->FileExists())? 1:0;
    }
    return $cnt;
  }
  
  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search_ids() {
    $criteria = new CDbCriteria;
    $criteria->with = array(
       'type',
       'category',
       'user'
    );
    $criteria->select = array(
       'idDocument'
    );
    $criteria->together = true;
    $criteria->compare('idDocument', $this->idDocument);
    $criteria->compare('ControlField', $this->ControlField, true);
    if (!empty($this->searchField)){
      $criteria->compare('CONCAT(DocumentName,'
            . 'type.DocumentTypeName,'
            . 'category.DocumentCategoryName,'
            . 'if(isnull(DocumentDescription),"без змісту",DocumentDescription),'
            . 'if(isnull(DocumentInputNumber),"без внутрішнього номеру",DocumentInputNumber),'
            . 'if(isnull(DocumentOutputNumber),"без вхідного номеру",DocumentOutputNumber),'
            . 'if(isnull(Correspondent),"без кореспондента",Correspondent),'
            . 'if(isnull(DocumentForWhom),"без розписано",DocumentForWhom),'
            . 'if(isnull(signed),"без підпису",signed),'
            . 'if(isnull(mark),"без відмітки",mark),'
            . 'if(isnull(ControlField),"без контролю",ControlField),'
            . 'Created,'
            . 'user.info)', $this->searchField,true);
    }
    $all_count = $this->count();
    $doc_count = $this->count($criteria);
    $documents = $this->findAll($criteria);
    $doc_ids = array();
    if ($doc_count < $all_count){
      foreach ($documents as $doc){
        $doc_ids[] = $doc->idDocument;
      }
    }
    return $doc_ids;
  }
  

}
