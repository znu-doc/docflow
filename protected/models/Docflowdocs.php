<?php

/**
 * This is the model class for table "docflowdocs".
 * Зв'язки документів та розсилок. Нічого цікавого.
 * 
 * The followings are the available columns in table 'docflowdocs':
 * @property integer $idDocFlowDoc
 * @property integer $DocFlowID
 * @property integer $DocumentID
 *
 * The followings are the available model relations:
 * @property Docflows $docFlow
 * @property Documents $document
 */
class Docflowdocs extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowdocs the static model class
   */
  public static function model($className=__CLASS__){
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'docflowdocs';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('DocFlowID, DocumentID', 'required'),
      array('DocFlowID, DocumentID', 'numerical', 'integerOnly'=>true),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idDocFlowDoc, DocFlowID, DocumentID', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations(){
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'docFlow' => array(self::BELONGS_TO, 'Docflows', 'DocFlowID'),
      'document' => array(self::BELONGS_TO, 'Documents', 'DocumentID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels(){
    return array(
    'idDocFlowDoc' => 'Id Doc Flow Doc',
    'DocFlowID' => 'Doc Flow',
    'DocumentID' => 'Document',
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

    $criteria->compare('idDocFlowDoc',$this->idDocFlowDoc);
    $criteria->compare('DocFlowID',$this->DocFlowID);
    $criteria->compare('DocumentID',$this->DocumentID);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
}