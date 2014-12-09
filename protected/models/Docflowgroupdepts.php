<?php

/**
 * This is the model class for table "docflowgroupdepts".
 * Зв'язки груп для розсилок та підрозділів. Нічого цікавого.
 * Один підрозділ може входити в декілька груп, 
 *   а в одну групу - декілька підрозділів.
 *
 * The followings are the available columns in table 'docflowgroupdepts':
 * @property integer $idDocFlowGroupDept
 * @property integer $DocFlowGroupID
 * @property integer $DeptID
 *
 * The followings are the available model relations:
 * @property Docflowgroups $docFlowGroup
 * @property Departments $department
 */
class Docflowgroupdepts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Docflowgroupdepts the static model class
	 */
  public $MyDeptIDs;
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'docflowgroupdepts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DocFlowGroupID', 'required'),
			array('DocFlowGroupID, DeptID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idDocFlowGroupDept, DocFlowGroupID, DeptID', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'docFlowGroup' => array(self::BELONGS_TO, 'Docflowgroups', 'DocFlowGroupID'),
			'department' => array(self::BELONGS_TO, 'Departments', 'DeptID'),
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
    'idDocFlowGroupDept' => 'Id Doc Flow Group Dept',
    'DocFlowGroupID' => 'ID групи для розсилки',
    'DeptID' => 'ID підрозділу',
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

		$criteria->compare('idDocFlowGroupDept',$this->idDocFlowGroupDept);
		$criteria->compare('DocFlowGroupID',$this->DocFlowGroupID);
		$criteria->compare('DeptID',$this->DeptID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public function search_ids(){
    $criteria = new CDbCriteria();
    $criteria->select = array(
       't.DocFlowGroupID'
    );
    $entry_condition = 'IN';
    $count_group_ids = $this->count(
            'DeptID IN ('.$this->MyDeptIDs.')');
    $count_not_group_ids = $this->count(
            'DeptID NOT IN ('.$this->MyDeptIDs.')');
    if ($count_not_group_ids < $count_group_ids){
      $criteria->addCondition('t.DeptID NOT IN (', $this->MyDeptIDs . ')');
      $entry_condition = 'NOT IN';
    } else {
      $criteria->addCondition('t.DeptID IN ('. $this->MyDeptIDs . ')');
    }
    $criteria->distinct = true;
    $docflowgroupdepts = $this->findAll($criteria);
    $group_ids = array();
    foreach ($docflowgroupdepts as $docflowgroupdept){
      $group_ids [] = $docflowgroupdept->DocFlowGroupID;
    }
    return $group_ids;
  }
}