<?php

/**
 * Ця модель створена для таблиці "stat_average".
 *
 * Далі йде перелік стовпців таблиці 'stat_average':
 * @property integer $OwnerID
 * @property integer $idRespDept
 * @property string $Owner
 * @property string $OwnerDept
 * @property string $RespDeptName
 * @property string $AveAnswerDelay
 * @property string $NoReplyCnt
 * @property string $AnswerCnt
 * @property string $AllFlowsCnt
 */
class StatAverage extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return StatAverage the static model class
   */
  public static function model($className=__CLASS__){
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'stat_average';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('OwnerID, Owner, RespDeptName', 'required'),
      array('OwnerID, idRespDept', 'numerical', 'integerOnly'=>true),
      array('RespDeptName', 'length', 'max'=>255),
      array('AveAnswerDelay', 'length', 'max'=>32),
      array('NoReplyCnt, AnswerCnt', 'length', 'max'=>23),
      array('AllFlowsCnt', 'length', 'max'=>21),
      array('OwnerDept', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('OwnerID, idRespDept, Owner, OwnerDept, RespDeptName, AveAnswerDelay, NoReplyCnt, AnswerCnt, AllFlowsCnt', 'safe', 'on'=>'search'),
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
    'OwnerID' => 'Owner',
    'idRespDept' => 'Id Resp Dept',
    'Owner' => 'Owner',
    'OwnerDept' => 'Owner Dept',
    'RespDeptName' => 'Resp Dept Name',
    'AveAnswerDelay' => 'Ave Answer Delay',
    'NoReplyCnt' => 'No Reply Cnt',
    'AnswerCnt' => 'Answer Cnt',
    'AllFlowsCnt' => 'All Flows Cnt',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search(){
    $criteria=new CDbCriteria;

    $criteria->compare('OwnerID',$this->OwnerID);
    $criteria->compare('idRespDept',$this->idRespDept);
    $criteria->compare('Owner',$this->Owner,true);
    $criteria->compare('OwnerDept',$this->OwnerDept,true);
    $criteria->compare('RespDeptName',$this->RespDeptName,true);
    $criteria->compare('AveAnswerDelay',$this->AveAnswerDelay,true);
    $criteria->compare('NoReplyCnt',$this->NoReplyCnt,true);
    $criteria->compare('AnswerCnt',$this->AnswerCnt,true);
    $criteria->compare('AllFlowsCnt',$this->AllFlowsCnt,true);
    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => array(
          'pageSize' => 1000
      )
    ));
  }
}