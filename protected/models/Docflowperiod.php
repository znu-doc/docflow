<?php

/**
 * This is the model class for table "docflowperiod".
 *
 * The followings are the available columns in table 'docflowperiod':
 * @property integer $idDocFlowPeriod
 * @property string $PeriodName
 * @property string $PeriodDescription
 *
 * The followings are the available model relations:
 * @property Docflows[] $docflows
 */
class Docflowperiod extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Docflowperiod the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'docflowperiod';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PeriodName', 'length', 'max'=>64),
			array('PeriodDescription', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idDocFlowPeriod, PeriodName, PeriodDescription', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'docflows' => array(self::HAS_MANY, 'Docflows', 'DocFlowPeriodID'),
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
    'idDocFlowPeriod' => 'Id Doc Flow Period',
    'PeriodName' => 'Period Name',
    'PeriodDescription' => 'Period Description',
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

		$criteria->compare('idDocFlowPeriod',$this->idDocFlowPeriod);
		$criteria->compare('PeriodName',$this->PeriodName,true);
		$criteria->compare('PeriodDescription',$this->PeriodDescription,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
  /**
   * Метод повертає усі можливі типи періодичності.
   * @return array customized data (idDocFlowPeriod=>PeriodName)
   */
	public static function DropDown(){
		$ret = array();
		$models = Docflowperiod::model()->findAll();
		foreach ($models as $model){
			$ret[$model->idDocFlowPeriod] = $model->PeriodName;
		}
		return $ret;
	}
}