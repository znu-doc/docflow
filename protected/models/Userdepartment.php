<?php

/**
 * This is the model class for table "userdepartment".
 *
 * The followings are the available columns in table 'userdepartment':
 * @property integer $idUserDepartment
 * @property integer $UserID
 * @property integer $DeptID
 * @property double $quota
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Departments $dept
 */
class Userdepartment extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Userdepartments the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'userdepartment';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('UserID, DeptID, quota', 'required'),
        array('UserID, DeptID', 'numerical', 'integerOnly' => true),
        array('quota', 'numerical'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idUserDepartment, UserID, DeptID, quota', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'user' => array(self::BELONGS_TO, 'User', 'UserID'),
        'dept' => array(self::BELONGS_TO, 'Departments', 'DeptID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idUserDepartment' => 'ID',
        'UserID' => 'User',
        'DeptID' => 'Department',
        'quota' => 'Quota',
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

    $criteria->compare('idUserDepartment', $this->idUserDepartment);
    $criteria->compare('UserID', $this->UserID);
    $criteria->compare('DeptID', $this->DeptID);
    $criteria->compare('quota', $this->quota);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

}
