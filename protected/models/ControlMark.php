<?php

/**
 * This is the model class for table "control_mark".
 *
 * The followings are the available columns in table 'control_mark':
 * @property integer $id
 * @property string $Fund
 * @property string $Description
 * @property string $Act
 * @property integer $DocumentID
 */
class ControlMark extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ControlMark the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'control_mark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
      array('id, DocumentID', 'numerical', 'integerOnly'=>true),
			array('Fund, Description, Act', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, DocumentID, Fund, Description, Act', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
    'id' => 'ID',
    'Fund' => 'Фонд №',
    'Description' => 'Опис №',
    'Act' => 'Справа №',
    'DocumentID' => 'Документ',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('Fund',$this->Fund,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Act',$this->Act,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}