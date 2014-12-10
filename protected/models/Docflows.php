<?php

/**
 * This is the model class for table "docflows".
 *
 * The followings are the available columns in table 'docflows':
 * @property integer $idDocFlow
 * @property string $DocFlowName
 * @property string $DocFlowDescription
 * @property integer $DocFlowTypeID
 * @property integer $DocFlowStatusID
 * @property integer $DocFlowGroupID
 * @property string $ExpirationDate
 * @property string $ControlDate
 * @property string $Created
 * @property string $Finished
 * @property integer $DocFlowPeriodID
 *
 * The followings are the available model relations:
 * @property Docflowdocs[] $docflowdocs
 * @property Docflowgroups $docFlowGroup
 * @property Docflowstatus $docFlowStatus
 * @property Docflowtypes $docFlowType
 * @property Docflowperiod $period
 * @property Docflowanswers $docflowAnswers
 * @property Documents $documents
 */
class Docflows extends CActiveRecord {

  public $searchDocflowGroup;
  public $searchDocument;
  public $searchDept;
  public $DocumentID;
  public $mode;
  
  public $searchField;
  public $AnswerDeptIDs;
  public $MyDeptIDs;
  
  public $doc_field;
  public $dept_field;
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflows the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'docflows';
  }
  
  public function beforeDelete(){
    Docflowdocs::model()->deleteAll('DocFlowID='.$this->idDocFlow);
    Docflowevents::model()->deleteAll('DocFlowID='.$this->idDocFlow);
    Docflowanswers::model()->deleteAll('DocFlowID='.$this->idDocFlow);
    return parent::beforeDelete();
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocFlowName, DocFlowTypeID, DocFlowStatusID, DocFlowGroupID, Created', 'required'),
        array('DocFlowTypeID, DocFlowStatusID, DocFlowGroupID, DocFlowPeriodID', 'numerical', 'integerOnly' => true),
        array('DocFlowName', 'length', 'max' => 255),
       array('ExpirationDate', 'default', 'value' => null), //make ExpirationDate safe and convert "" to null
        array('DocFlowDescription, ExpirationDate, ControlDate, Finished, searchField', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocFlow, DocFlowName, DocFlowDescription, DocFlowTypeID, '
            . 'DocFlowStatusID, DocFlowGroupID, ExpirationDate, '
            . 'Created, Finished, ControlDate, DocFlowPeriodID, searchField', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'docFlowGroup' => array(self::BELONGS_TO, 'Docflowgroups', 'DocFlowGroupID'),
        'docFlowStatus' => array(self::BELONGS_TO, 'Docflowstatus', 'DocFlowStatusID'),
        'docFlowType' => array(self::BELONGS_TO, 'Docflowtypes', 'DocFlowTypeID'),
        'period' => array(self::BELONGS_TO, 'Docflowperiod', 'DocFlowPeriodID'),
        'docflowdocs' => array(self::HAS_MANY, 'Docflowdocs', 'DocFlowID'),
        'docflowevents' => array(self::HAS_MANY, 'Docflowevents', 'DocFlowID'),
        'docflowAnswers' => array(self::HAS_MANY, 'Docflowanswers', 'DocFlowID'),
        'documents' => array(self::HAS_MANY, 'Documents', 'DocumentID', 'through' => 'docflowdocs'),
        'respdepts' => array(self::MANY_MANY, 'Departments', 'docflowgroupdepts(DocFlowGroupID,DeptID)', 'through' => 'docFlowGroup'),
        // 'respdeptanswers' => array(self::HAS_MANY, 'Docflowanswers', 'DocFlowID', 'on'=>'respdeptanswers.DeptID=respdepts.idDepartment'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocFlow' => 'ID',
        'DocFlowName' => 'Назва документообігу',
        'DocFlowDescription' => 'Опис та особливості процесу',
        'DocFlowTypeID' => 'Тип документообігу',
        'DocFlowStatusID' => 'Статус процесу документообігу',
        'DocFlowGroupID' => 'Група учасників документообігу',
        'ExpirationDate' => 'Дата закінчення процесу',
        'ControlDate' => 'Контроль',
        'Created' => 'Процес ініційовано',
        'Finished' => 'Процес завершено',
        'DocFlowPeriodID' => 'Періодичність',
        'searchField' => 'Розсилки'
    );
  }
  
  /**
   * Метод - джерело даних розсилок
   * @return \CActiveDataProvider|null
   */
  public function search_rel(){
    //MyDeptIDs - строка виду "2,4,9"
    if (!$this->MyDeptIDs){
      return null;
    }
    //формуємо критерії пошуку для розсилок по окремим полям із залежностями
    // а також для нових розсилок для підрозділів
    $criteria = $this->getOwnCriteria();
    //формуємо критерії пошуку для вхідних розсилок
    $inbox_criteria = $this->getInboxCriteria();
    //формуємо критерії пошуку для вихідних розсилок
    $outbox_criteria = $this->getOutboxCriteria();
    //якщо встановлено режим розсилок без відповіді або вхідних розсилок
    //або режим не задано і не задано ІД. розсилки, ІД. документа і користувач не з правами адмін.
    if ((!$this->mode && !$this->DocumentID 
            && !$this->idDocFlow 
            && !Yii::app()->user->checkAccess('showProperties')) 
          || $this->mode == 'in' 
          || $this->mode == 'new'){
      //об_єднати основні критерії вибірки та критерії пошуку для вхідних розсилок
      $criteria->mergeWith($inbox_criteria,true);
    }
    //якщо встановлено режим вихідних розсилок (ініційованих)
    if ($this->mode == 'from'){
      //об_єднати основні критерії вибірки та критерії пошуку для вихідних розсилок
      $criteria->mergeWith($outbox_criteria,true);
    }
    //якщо задано ІД. розсилки або ІД. документа
    if ($this->DocumentID || $this->idDocFlow){
      //критерії пошуку для вихідних та вхідних розсилок об_єднуються оператором OR
      $inbox_criteria->mergeWith($outbox_criteria,false);
      //далі об_єднуються з основними критеріями оператором AND
      $criteria->mergeWith($inbox_criteria,true);
    }
    //формуємо критерії пошуку для документів у розсилках
    $doc_criteria = $this->getDocSearchCriteria();
    //формуємо критерії вибірки серед розсилок по загальному полю пошуку
    $partial_criteria = $this->getOwnPartialCriteria();
    //об_єднуємо з критеріями для пошуку серед документів розсилок оператором OR
    $partial_criteria->mergeWith($doc_criteria,false);
    //об_єднуємо з основними критеріями оператором AND
    $criteria->mergeWith($partial_criteria,true);
    //формуємо джерело даних на основі моделі і критеріїв, 
    // сортування зв спаданням ІД. розсилок
    // пагінація: максимум по 10 елементів на сторінку
    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
        'sort' => array(
            'defaultOrder' => array(
                'idDocFlow' => CSort::SORT_DESC,
            ),
        ),
        'pagination' => array(
            'pageSize' => 10
        )
    ));
  }
  
  /**
   * Метод повертає додаткові критерії для пошуку серед розсилок
   * @return \CDbCriteria
   */
  public function getOwnPartialCriteria(){
    $partial_criteria = new CDbCriteria();
    
    $partial_criteria->with = array(
       'docFlowGroup',
       'docFlowGroup.owner',
       'docFlowGroup.owner.departments' => array('select' => false),
    );
    $partial_criteria->together = true;
    if ($this->searchField){
      //пошук у власне розсилках
      $partial_criteria->compare('CONCAT('
              . 'DocFlowName,'
              . 'if(isnull(DocFlowDescription),"без_змісту_розсилки",DocFlowDescription),'
              . 'if(isnull(ControlDate),"без_контролю",ControlDate),'
              . 'if(isnull(ExpirationDate),"без_дати_закінчення",DATE_FORMAT(ExpirationDate,"%d.%m.%Y")),'
              . 'owner.info,'
              . 'departments.DepartmentName'
              . ')',$this->searchField,true);
    }
    return $partial_criteria;
  }
  
  /**
   * Метод повертає критерії пошуку по полям розсилок
   * @return \CDbCriteria
   */
  public function getOwnCriteria(){
    $criteria = new CDbCriteria();
    $criteria->select = array(
       '*',
       
       "(select GROUP_CONCAT("
       . "CONCAT_WS('||',"
       . "d.idDocument,"
       . "IF(isnull(d.DocumentInputNumber),'',d.DocumentInputNumber),"
       . "IF(isnull(d.DocumentOutputNumber),'',d.DocumentOutputNumber),"
       . "IF(isnull(d.DocumentDescription),'',d.DocumentDescription),"
       . "du.username,"
       . "(SELECT IF(isnull(GROUP_CONCAT( dfl.FileID SEPARATOR ';')),'',GROUP_CONCAT(dfl.FileID SEPARATOR ';')) "
       . "   FROM documentfiles dfl WHERE dfl.DocumentID=d.idDocument GROUP BY dfl.DocumentID )"
       . ") SEPARATOR '$$$') "
       . "from docflowdocs dfd "
       . "LEFT JOIN documents d ON dfd.DocumentID=d.idDocument "
       . "JOIN sys_users du ON du.id=d.UserID "
       . "WHERE dfd.DocFlowID=t.idDocFlow GROUP BY dfd.DocFlowID) as doc_field",
       
       "(select GROUP_CONCAT(dept.DepartmentName SEPARATOR '$$') "
       . "from docflowgroupdepts dfgdpts "
       . "join departments dept ON dept.idDepartment=dfgdpts.DeptID"
       . " WHERE dfgdpts.DocFlowGroupID=t.DocFlowGroupID) as dept_field"
    );
    $criteria->with = array(
       'docFlowStatus',
       'docFlowGroup',
       'docFlowGroup.owner',
       'docFlowGroup.owner.departments' => array('select' => false),
    );
    $criteria->together = true;
    if ($this->mode == 'new'){
      $criteria->addCondition("isnull((SELECT idDocFlowAnswer from docflowanswers dfans"
        . " WHERE dfans.DeptID IN (" . $this->MyDeptIDs . ") AND dfans.DocFlowID = t.idDocFlow LIMIT 1))");
    }
    $criteria->compare('idDocflow',$this->idDocFlow);
    $criteria->compare('t.DocFlowName', $this->DocFlowName, true);
    $criteria->compare('t.DocFlowDescription', $this->DocFlowDescription, true);
    $criteria->compare('t.DocFlowTypeID', $this->DocFlowTypeID);
    $criteria->compare('t.DocFlowStatusID', $this->DocFlowStatusID);
    $criteria->compare('t.DocFlowGroupID', $this->DocFlowGroupID);
    $criteria->compare('t.ExpirationDate', $this->ExpirationDate, true);
    $criteria->compare('t.ControlDate', $this->ControlDate, true);
    $criteria->compare('t.Created', $this->Created, true);
    $criteria->compare('t.Finished', $this->Finished, true);
    $criteria->compare('t.DocFlowPeriodID', $this->DocFlowPeriodID);
    return $criteria;
  }
  
  /**
   * Метод повертає критерії пошуку вхідних розсилок
   * @return \CDbCriteria
   */
  public function getInboxCriteria(){
    $criteria = new CDbCriteria();
    $criteria->addCondition('t.DocFlowGroupID IN ((select docflowgroupdepts.DocFlowGroupID from docflowgroupdepts '
      .'where docflowgroupdepts.DeptID IN ('. $this->MyDeptIDs . ')))');
    return $criteria;
  }
  
  /**
   * Метод повертає критерії пошуку вихідних розсилок
   * @return \CDbCriteria
   */
  public function getOutboxCriteria(){
    $criteria = new CDbCriteria();
    $criteria->with = array(
       'docFlowStatus',
       'docFlowGroup',
       'docFlowGroup.owner',
       'docFlowGroup.owner.departments' => array('select' => false),
    );
    $criteria->addCondition('departments.idDepartment IN ('.$this->MyDeptIDs.')');
    return $criteria;
  }
  
  /**
   * Метод повертає критерії пошуку по серед документів у розсилках
   * @return \CDbCriteria
   */
  public function getDocSearchCriteria(){
    //пошук в документах із розсилок
    $criteria = new CDbCriteria();
    if ($this->DocumentID){
      $criteria->compare(
       "(select GROUP_CONCAT("
       . "CONCAT('||',CONCAT_WS('||',"
       . "d.idDocument"
       . "),'||') SEPARATOR '$$$') "
       . "from docflowdocs dfd "
       . "LEFT JOIN documents d ON dfd.DocumentID=d.idDocument "
       . "WHERE dfd.DocFlowID=t.idDocFlow GROUP BY dfd.DocFlowID)", '||'.$this->DocumentID.'||', true);
    }
    if (!empty($this->searchField)){
      $criteria->compare(
       "(select GROUP_CONCAT("
       . "CONCAT_WS('||',"
       . "IF(isnull(d.DocumentInputNumber),'',d.DocumentInputNumber),"
       . "IF(isnull(d.DocumentOutputNumber),'',d.DocumentOutputNumber),"
       . "IF(isnull(d.DocumentDescription),'',d.DocumentDescription),"
       . "du.username,"
       . "(SELECT IF(isnull(GROUP_CONCAT( dfl.FileID SEPARATOR ';')),'',GROUP_CONCAT(dfl.FileID SEPARATOR ';')) "
       . "   FROM documentfiles dfl WHERE dfl.DocumentID=d.idDocument GROUP BY dfl.DocumentID )"
       . ") SEPARATOR '$$$') "
       . "from docflowdocs dfd "
       . "LEFT JOIN documents d ON dfd.DocumentID=d.idDocument "
       . "JOIN sys_users du ON du.id=d.UserID "
       . "WHERE dfd.DocFlowID=t.idDocFlow GROUP BY dfd.DocFlowID)", $this->searchField,true);
    }
    return $criteria;
  }
  
  /**
   * Метод повертає першу відповідь від певних підрозділів на певну розсилку
   */
  public function getAnswer(){
    if (!$this->MyDeptIDs){
      return null;
    }
    $criteria = new CDbCriteria();
    $criteria->addCondition('DeptID IN ('.$this->MyDeptIDs.')');
    $criteria->compare('DocFlowID',$this->idDocFlow);
    return Docflowanswers::model()->find($criteria);
  }

  /**
   * Метод з'ясовує, чи можливо завантажити усі документи одним архівом.
   * @return bool
   */
  public function CanDownloadAllDocs(){
    $cnt = 0;
    foreach ($this->documents as $doc){
      $cnt += $doc->RealFilesCount();
    }
    if (!$cnt){
      return false;
    }
    return true;
  }
  
  /**
   * Метод повертає усі нові вхідні розсилки для вказаних підрозділів.
   * @return bool
   */
  public function getMyDeptNewFlows(){
    if (!$this->MyDeptIDs){
      return ;
    }
    $criteria = new CDbCriteria();
    $criteria->with = array('docFlowGroup',
        'documents' => array('select' => false),
        'docFlowGroup.departments' => array('select' => false),
    );
    $criteria->group = 't.idDocFlow';
    $criteria->together = true;
    $criteria->addCondition('departments.idDepartment IN (' . $this->MyDeptIDs . ')');
    $criteria->compare("t.DocFlowStatusID", 1);
    $criteria->addCondition("isnull((SELECT idDocFlowAnswer from docflowanswers dfans"
      . " WHERE dfans.DeptID IN (" . $this->MyDeptIDs . ") AND dfans.DocFlowID = t.idDocFlow LIMIT 1))");
    $models = $this->findAll($criteria);
    return $models;
  }
  
  /**
   * Метод повертає масив ID підрозділів-респондентів даної розсилки.
   * @return bool
   */
  public function getRespondentsID(){
    $deptIDs = array();
    foreach ($this->docFlowGroup->docflowgroupdepts as $dfgd){
      $deptIDs[] = $dfgd->DeptID;
    }
    return $deptIDs;
  }
  
  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;
    
    $criteria->with = array(
        'docflowdocs' => array('select' => false),
        'docFlowGroup',
        'docFlowGroup.owner'
    );
    $criteria->group = 't.idDocFlow';
    $criteria->together = true;
    $criteria->compare('idDocFlow', $this->idDocFlow);
    $criteria->compare('DocFlowName', $this->DocFlowName, true);
    $criteria->compare('DocFlowDescription', $this->DocFlowDescription, true);
    $criteria->compare('DocFlowTypeID', $this->DocFlowTypeID);
    $criteria->compare('DocFlowStatusID', $this->DocFlowStatusID);
    $criteria->compare('DocFlowGroupID', $this->DocFlowGroupID);
    $criteria->compare('ExpirationDate', $this->ExpirationDate, true);
    $criteria->compare('ControlDate', $this->ControlDate, true);
    $criteria->compare('Created', $this->Created, true);
    $criteria->compare('Finished', $this->Finished, true);
    //$criteria->compare('DocflowPeriodID', $this->DocflowPeriodID);
    $criteria->compare('docflowdocs.DocumentID', $this->searchDocument->idDocument);
    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  
}
