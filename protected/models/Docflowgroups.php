<?php

/**
 * This is the model class for table "docflowgroups".
 * Групи для розсилки
 *
 * The followings are the available columns in table 'docflowgroups':
 * @property integer $idDocFlowGroup
 * @property string $DocflowGroupName
 * @property string $Responsibility
 * @property integer $OwnerID
 *
 * The followings are the available model relations:
 * @property User $owner
 * @property Docflowgroupdepts[] $docflowgroupdepts
 * @property Docflows[] $docflows
 * @property Departments[] $departments
 */
class Docflowgroups extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowgroups the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'docflowgroups';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocflowGroupName, OwnerID', 'required'),
        array('OwnerID', 'numerical', 'integerOnly' => true),
        array('DocflowGroupName', 'length', 'max' => 255),
        array('Responsibility', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocFlowGroup, DocflowGroupName, Responsibility, OwnerID', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'owner' => array(self::BELONGS_TO, 'User', 'OwnerID'),
        'docflowgroupdepts' => array(self::HAS_MANY, 'Docflowgroupdepts', 'DocFlowGroupID'),
        'docflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowGroupID'),
        'departments' => array(self::MANY_MANY, 'Departments', 'docflowgroupdepts(DocFlowGroupID,DeptID)'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocFlowGroup' => 'ID групи документообігу',
        'DocflowGroupName' => 'Назва групи документообігу',
        'Responsibility' => 'Додаткова інформація',
        'OwnerID' => 'Групу створив',
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

    $criteria->compare('idDocFlowGroup', $this->idDocFlowGroup);
    $criteria->compare('DocflowGroupName', $this->DocflowGroupName, true);
    $criteria->compare('Responsibility', $this->Responsibility, true);
    $criteria->compare('OwnerID', $this->OwnerID);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * Метод повертає усі групи користувача, починаючи з останніх.
   * @return array customized data (idDocFlowGroup=>DocflowGroupName)
   */
  public static function DropDown() {
    $res = array();
    foreach (DocFlowGroups::model()->findAll(
            'OwnerID=' . Yii::app()->user->id . ' ORDER BY idDocFlowGroup DESC') as $record) {
      $res[$record->idDocFlowGroup] = $record->DocflowGroupName;
    }
    return $res;
  }

}
