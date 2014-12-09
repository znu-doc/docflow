<?php

/**
 * This is the model class for table "eventorganizers".
 *
 * The followings are the available columns in table 'eventorganizers':
 * @property integer $idEventOrganizer
 * @property integer $DeptID
 * @property integer $EventID
 * @property string $OrganizerComment
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property Departments $dept
 */
class Eventorganizers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Eventorganizers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'eventorganizers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DeptID, EventID', 'required'),
			array('DeptID, EventID', 'numerical', 'integerOnly'=>true),
			array('OrganizerComment', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idEventOrganizer, DeptID, EventID, OrganizerComment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'event' => array(self::BELONGS_TO, 'Events', 'EventID'),
			'dept' => array(self::BELONGS_TO, 'Departments', 'DeptID'),
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
    'idEventOrganizer' => 'Id Event Organizer',
    'DeptID' => 'Dept',
    'EventID' => 'Event',
    'OrganizerComment' => 'Organizer Comment',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('idEventOrganizer',$this->idEventOrganizer);
		$criteria->compare('DeptID',$this->DeptID);
		$criteria->compare('EventID',$this->EventID);
		$criteria->compare('OrganizerComment',$this->OrganizerComment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}