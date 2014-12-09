<?php

/**
 * This is the model class for table "invited".
 *
 * The followings are the available columns in table 'invited':
 * @property integer $idInvited
 * @property integer $EventID
 * @property integer $DeptID
 * @property string $InvitedComment
 * @property string $Seets
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property Departments $dept
 */
class Invited extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Invited the static model class
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
    return 'invited';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('EventID, DeptID', 'required'),
      array('EventID, DeptID, Seets', 'numerical', 'integerOnly'=>true),
      array('InvitedComment', 'length', 'max'=>255),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idInvited, EventID, DeptID, InvitedComment, Seets', 'safe', 'on'=>'search'),
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
    'idInvited' => 'Id Invited',
    'EventID' => 'Event',
    'DeptID' => 'Dept',
    'InvitedComment' => 'Invited Comment',
    'Seets' => 'К-сть місць',
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

    $criteria->compare('idInvited',$this->idInvited);
    $criteria->compare('EventID',$this->EventID);
    $criteria->compare('DeptID',$this->DeptID);
    $criteria->compare('InvitedComment',$this->InvitedComment,true);
    $criteria->compare('Seets',$this->Seets,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
}