<?php

/**
 * This is the model class for table "departments".
 *
 * The followings are the available columns in table 'departments':
 * @property integer $idDepartment
 * @property string $DepartmentName
 * @property string $FunctionDescription
 *
 * The followings are the available model relations:
 * @property Userdepartment[] $userdepartments
 * @property Docflowanswers[] $docflowanswers
 * @property Docflowgroupdepts[] $docflowgroupdepts
 * @property Docflowgroups[] $docflowGroups
 */
class Departments extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Departments the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'departments';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('DepartmentName', 'required'),
      array('DepartmentName', 'length', 'max'=>255),
      array('FunctionDescription', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idDepartment, DepartmentName, FunctionDescription', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations(){
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'userdepartments' => array(self::HAS_MANY, 'Userdepartment', 'DeptID'),
      'deptusers' => array(self::HAS_MANY, 'User', 'UserID', 'through' => 'userdepartments'),
      'depdocflowanswers' => array(self::HAS_MANY, 'Docflowanswers', 'DeptID'),
      'docflowgroupdepts' => array(self::HAS_MANY, 'Docflowgroupdepts', 'DeptID'),
      'docflowGroups' => array(self::MANY_MANY, 'Docflowgroups', 'docflowgroupdepts(DeptID,DocFlowGroupID)'),
      'respDocflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowGroupID', 
        'through'=>'docflowgroupdepts'),
      'respAnswers' => array(self::HAS_MANY, 'Docflowanswers', 'DeptID', 
        'on'=>'respAnswers.DocFlowID=respDocflows.idDocFlow'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels(){
    return array(
    'idDepartment' => 'Оберіть свій підрозділ',
    'DepartmentName' => 'Департамент',
    'FunctionDescription' => 'Опис ф-цій',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search(){
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria=new CDbCriteria;

    $criteria->compare('idDepartment',$this->idDepartment);
    $criteria->compare('DepartmentName',$this->DepartmentName,true);
    $criteria->compare('FunctionDescription',$this->FunctionDescription,true);
    $criteria->order = 'DepartmentName ASC';
    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => array(
          'pageSize' => 100
      )
    ));
  }
  
  /**
   * Формує масив усіх підрозділів в алфавітному порядку
   * @return array [idDepartment=>DepartmentName]
   */
  public static function DropDown(){
     $res = array();
     foreach(Departments::model()->findAll(
             '1 ORDER BY DepartmentName ASC')as $record) {
        $res[$record->idDepartment] = $record->DepartmentName;
     }
     return $res;
  }

  
  /**
   * Формує масив користувачів певного підрозділу
   * @return array [id=>username]
   */
  public function getUsernames(){
     $res = array();
     foreach($this->deptusers as $user) {
        $res[$user->id] = $user->username;
     }
     return $res;
  }
  
  /**
   * Метод повертає відповідь (підтвердження) даного підрозділу
   * на розсилку з ІД = DocFlowID
   * @param integer $DocFlowID ID : docflows.idDocFlow
   * @return Docflowanswers
   */
  public function getAnswerToDocflow($DocFlowID){
    if (!$this->idDepartment){
      return null;
    }
    $dfa = Docflowanswers::model()->findByAttributes(array(
      'DeptID' => $this->idDepartment,
      'DocFlowID' => $DocFlowID
    ));
    return $dfa;
  }
}