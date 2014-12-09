<?php

/**
 * This is the model class for table "docflowstatus".
 *
 * The followings are the available columns in table 'docflowstatus':
 * @property integer $idDocFlowStatus
 * @property string $DocFlowStatusName
 * @property string $DocFlowStatusDescription
 *
 * The followings are the available model relations:
 * @property Docflows[] $docflows
 */
class Docflowstatus extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowstatus the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'docflowstatus';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocFlowStatusName', 'required'),
        array('DocFlowStatusName', 'length', 'max' => 255),
        array('DocFlowStatusDescription', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocFlowStatus, DocFlowStatusName, DocFlowStatusDescription', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'docflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowStatusID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocFlowStatus' => 'Id Doc Flow Status',
        'DocFlowStatusName' => 'Doc Flow Status Name',
        'DocFlowStatusDescription' => 'Doc Flow Status Description',
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

    $criteria->compare('idDocFlowStatus', $this->idDocFlowStatus);
    $criteria->compare('DocFlowStatusName', $this->DocFlowStatusName, true);
    $criteria->compare('DocFlowStatusDescription', $this->DocFlowStatusDescription, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * Метод повертає усі можливі статуси документообігу.
   * @return array (idDocFlowStatus => DocFlowStatusName)
   */
  public static function DropDown() {
    $data = array();
    $smodel = new Docflowstatus;
    foreach ($smodel->findAll() as $model) {
      $data[$model->idDocFlowStatus] = $model->DocFlowStatusName;
    }
    return $data;
  }

}
