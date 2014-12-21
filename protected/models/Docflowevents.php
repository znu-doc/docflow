<?php

/**
 * This is the model class for table "docflowevents".
 * Зв'язки заходів та розсилок. Теж нічого цікавого.
 * 
 * The followings are the available columns in table 'docflowevents':
 * @property integer $idDocFlowEvent
 * @property integer $DocFlowID
 * @property integer $EventID
 *
 * The followings are the available model relations:
 * @property Docflows $docFlow
 * @property Events $event
 
create table docflowevents(
    idDocFlowEvent int(11) primary key not null auto_increment,
    DocFlowID int(11) not null,
    EventID int(11) not null,
    constraint fk1_docflowevents_DocFlowID foreign key (DocFlowID)
    references docflows(idDocFlow),
    constraint fk1_docflowevents_EventID foreign key (EventID)
    references `events`(idEvent)
); 
 
 */
class Docflowevents extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowevents the static model class
   */
  public static function model($className=__CLASS__){
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'docflowevents';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('DocFlowID, EventID', 'required'),
      array('DocFlowID, EventID', 'numerical', 'integerOnly'=>true),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idDocFlowEvent, DocFlowID, EventID', 'safe', 'on'=>'search'),
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
      'event' => array(self::BELONGS_TO, 'Events', 'EventID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels(){
    return array(
    'idDocFlowDoc' => 'Id Doc Flow Doc',
    'DocFlowID' => 'Doc Flow',
    'EventID' => 'Event',
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
    $criteria->compare('EventID',$this->DocumentID);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
}