<?php

/**
 * This is the model class for table "documenttype".
 *
 * The followings are the available columns in table 'documenttype':
 * @property integer $idDocumentType
 * @property string $DocumentTypeName
 * @property string $info
 *
 * The followings are the available model relations:
 * @property Documents[] $documents
 */
class Documenttype extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Documenttype the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'documenttype';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DocumentTypeName, info', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idDocumentType, DocumentTypeName, info', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'documents' => array(self::HAS_MANY, 'Documents', 'DocumentTypeID'),
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
    'idDocumentType' => 'Id Document Type',
    'DocumentTypeName' => 'Назва типу документа',
    'info' => 'Info',
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

		$criteria->compare('idDocumentType',$this->idDocumentType);
		$criteria->compare('DocumentTypeName',$this->DocumentTypeName,true);
		$criteria->compare('info',$this->info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
   * Метод повертає усі типи документів.
   * @return array (idDocumentType => DocumentTypeName)
   */
	public static function DropDown(){
		$models = Documenttype::model()->findAll();
		$types = array();
		foreach ($models as $model){
			$types[$model->idDocumentType] = $model->DocumentTypeName;
		}
		return $types;
	}
}