<?php

/**
 * This is the model class for table "eventtypes".
 *
 * The followings are the available columns in table 'eventtypes':
 * @property integer $idEventType
 * @property string $EventTypeName
 * @property string $EventTypeDescription
 * @property string $EventTypeStyle
 *
 * The followings are the available model relations:
 * @property Events[] $events_by_type
 */
class Eventtypes extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Eventtypes the static model class
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
    return 'eventtypes';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('EventTypeName', 'required'),
      array('EventTypeName', 'length', 'max'=>128),
      array('EventTypeStyle', 'length', 'max'=>255),
      array('EventTypeDescription', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idEventType, EventTypeName, EventTypeDescription, EventTypeStyle', 'safe', 'on'=>'search'),
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
      'events_by_type' => array(self::HAS_MANY, 'Events', 'EventKindID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
    'idEventType' => 'ID',
    'EventTypeName' => 'Назва рівня заходу',
    'EventTypeDescription' => 'Опис',
    'EventTypeStyle' => 'Стилі',
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

    $criteria->compare('idEventType',$this->idEventType);
    $criteria->compare('EventTypeName',$this->EventTypeName,true);
    $criteria->compare('EventTypeDescription',$this->EventTypeDescription,true);
    $criteria->compare('EventTypeStyle',$this->EventTypeStyle,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public static function DropDown(){
    $list = array();
    foreach (Eventtypes::model()->findAll('1 ORDER BY EventTypeName') as $model){
      $list[$model->idEventType] = $model->EventTypeName;
    }
    return $list;
  }
}