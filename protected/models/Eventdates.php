<?php

/**
 * Ця модель створена для таблиці "eventdates".
 *
 * Далі йде перелік стовпців таблиці 'eventdates':
 * @property integer $idEventDate
 * @property integer $EventID
 * @property string $EventDate
 *
 * Реляційні відношення:
 * @property Events $event
 */
class Eventdates extends CActiveRecord
{
  public $past;
  public $EventFullTime;
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Eventdates the static model class
   */
  public static function model($className=__CLASS__){
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'eventdates';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('EventID, EventDate', 'required'),
      array('EventID', 'numerical', 'integerOnly'=>true),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idEventDate, EventID, EventDate', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations(){
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'event' => array(self::BELONGS_TO, 'Events', 'EventID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels(){
    return array(
    'idEventDate' => 'Id Event Date',
    'EventID' => 'Event',
    'EventDate' => 'Дата заходу',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search($event){
    $criteria=new CDbCriteria;
    $criteria->with = array(
       'event'
    );
    $criteria->select = array('*',
      'CONCAT(EventDate," ",'
        . 'IF(ISNULL(event.StartTime),"08:00:00",event.StartTime)) as EventFullTime',
      (($event->past)? $event->past: '0') .' as past',
    );
    $criteria->together = true;
    $criteria->group = 'idEventDate';
    
    $criteria->compare('idEventDate',$this->idEventDate);
    $criteria->compare('EventID',$this->EventID);
    $criteria->compare('EventDate',$this->EventDate,true);
    
    if ($event->past == 0){
      $criteria->addCondition('NOW() < CONCAT(EventDate," ",'
        . 'IF(ISNULL(event.StartTime),"08:00:00",event.StartTime))');
    } 
    if ($event->past == 1){
      $criteria->addCondition('NOW() >= CONCAT(EventDate," ",'
        . 'IF(ISNULL(event.FinishTime),"17:00:00",event.FinishTime))');
    }
    if ($event->past == 2){
      $criteria->addCondition('NOW() < CONCAT(EventDate," ",'
        . 'IF(ISNULL(event.FinishTime),"17:00:00",event.FinishTime))');
      $criteria->addCondition('NOW() >= CONCAT(EventDate," ",'
        . 'IF(ISNULL(event.StartTime),"08:00:00",event.StartTime))');
    }
    $criteria->compare('event.idEvent',$event->idEvent);
    $criteria->compare('event.EventName',$event->EventName,true);
    $criteria->compare('event.EventDescription',$event->EventDescription,true);
    $criteria->compare('event.EventPlace',$event->EventPlace,true);
    $criteria->compare('event.Responsible',$event->Responsible,true);
    $criteria->compare('event.ResponsibleContacts',$event->ResponsibleContacts,true);
    
    $criteria->compare('event.EventKindID',$event->EventKindID);
    $criteria->compare('event.EventTypeID',$event->EventTypeID);
    $criteria->compare('event.UserID',$event->UserID);
    $criteria->compare('event.Created',$event->Created,true);
    
    $criteria->order = 'EventFullTime '.( ($event->past == 0)? 'ASC':'DESC');
    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => array(
          'pageSize' => 15
      )
    ));
  }
}

