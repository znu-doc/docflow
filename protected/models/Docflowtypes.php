<?php

/**
 * This is the model class for table "docflowtypes".
 *
 * The followings are the available columns in table 'docflowtypes':
 * @property integer $idDocFlowType
 * @property string $DocFlowTypeName
 *
 * The followings are the available model relations:
 * @property Docflows[] $docflows
 */
class Docflowtypes extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowtypes the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'docflowtypes';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocFlowTypeName', 'required'),
        array('DocFlowTypeName', 'length', 'max' => 255),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocFlowType, DocFlowTypeName', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'docflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowTypeID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocFlowType' => 'Id Doc Flow Type',
        'DocFlowTypeName' => 'Doc Flow Type Name',
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

    $criteria->compare('idDocFlowType', $this->idDocFlowType);
    $criteria->compare('DocFlowTypeName', $this->DocFlowTypeName, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * Метод повертає усі можливі типи розсилок.
   * @return array (idDocFlowType => DocFlowTypeName)
   */
  public static function DropDown() {
    $res = array();
    foreach (DocFlowTypes::model()->findAll()as $record) {
      $res[$record->idDocFlowType] = $record->DocFlowTypeName;
    }
    return $res;
  }

}
