<?php

/**
 * This is the model class for table "eventkinds".
 *
 * The followings are the available columns in table 'eventkinds':
 * @property integer $idEventKind
 * @property string $EventKindName
 * @property string $EventKindDescription
 * @property string $EventKindStyle
 *
 * The followings are the available model relations:
 * @property Events[] $events_by_kind
 */
class Eventkinds extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Eventkinds the static model class
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
    return 'eventkinds';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('EventKindName', 'required'),
      array('EventKindName', 'length', 'max'=>128),
      array('EventKindStyle', 'length', 'max'=>255),
      array('EventKindDescription', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idEventKind, EventKindName, EventKindDescription, EventKindStyle', 'safe', 'on'=>'search'),
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
      'events_by_kind' => array(self::HAS_MANY, 'Events', 'EventKindID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
    'idEventKind' => 'ID',
    'EventKindName' => 'Назва виду',
    'EventKindDescription' => 'Опис',
    'EventKindStyle' => 'Стилі',
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

    $criteria->compare('idEventKind',$this->idEventKind);
    $criteria->compare('EventKindName',$this->EventKindName,true);
    $criteria->compare('EventKindDescription',$this->EventKindDescription,true);
    $criteria->compare('EventKindStyle',$this->EventKindStyle,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  /**
   * @return array customized values (id=>name)
   */
  public static function DropDown(){
    $kinds = array();
    foreach (Eventkinds::model()->findAll('1 ORDER BY EventKindName') as $model){
      $kinds[$model->idEventKind] = $model->EventKindName;
    }
    return $kinds;
  }
}