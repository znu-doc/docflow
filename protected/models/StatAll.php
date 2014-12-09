<?php

/**
 * Ця модель створена для представлення "stat_all".
 *
 * Далі йде перелік стовпців представлення 'stat_all':
 * @property integer $OwnerID
 * @property integer $idDocFlow
 * @property integer $idRespDept
 * @property integer $AnswerID
 * @property string $DocIDs
 * @property string $Initiator
 * @property string $IniDeptNames
 * @property string $RespDeptName
 * @property string $FlowCreated
 * @property string $AnswerComment
 * @property integer $AnswerDocID
 * @property string $AnswerCreated
 * @property integer $AnswerDelay
 */
class StatAll extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return StatAll the static model class
   */
  public static function model($className=__CLASS__){
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName(){
    return 'stat_all';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules(){
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('OwnerID, Initiator, RespDeptName', 'required'),
      array('OwnerID, idDocFlow, idRespDept, AnswerID, 
        AnswerDocID, AnswerDelay', 'numerical', 'integerOnly'=>true),
      array('RespDeptName', 'length', 'max'=>255),
      array('FlowCreated, AnswerCreated', 'length', 'max'=>21),
      array('DocIDs, IniDeptNames, AnswerComment', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('OwnerID, idDocFlow, idRespDept, AnswerID, DocIDs, 
        Initiator, IniDeptNames, RespDeptName, FlowCreated, 
        AnswerComment, AnswerDocID, AnswerCreated, AnswerDelay', 'safe', 'on'=>'search'),
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
    'idDocFlow' => 'Id Doc Flow',
    'idRespDept' => 'Id Resp Dept',
    'AnswerID' => 'Answer',
    'DocIDs' => 'Doc Ids',
    'Initiator' => 'Initiator',
    'IniDeptNames' => 'Ini Dept Names',
    'RespDeptName' => 'Resp Dept Name',
    'FlowCreated' => 'Flow Created',
    'AnswerComment' => 'Answer Comment',
    'AnswerDocID' => 'Answer Doc',
    'AnswerCreated' => 'Answer Created',
    'AnswerDelay' => 'Answer Delay',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search(){
    $criteria=new CDbCriteria;
    $d_criteria = new CDbCriteria();
    if (Docflowdocs::model()->count('DocumentID='.intval($this->DocIDs)) || !$this->DocIDs){
      $criteria->compare('OwnerID',$this->OwnerID);
      $criteria->compare('idDocFlow',$this->idDocFlow);
      $criteria->compare('idRespDept',$this->idRespDept);
      $criteria->compare('Initiator',$this->Initiator,true);
      $criteria->compare('IniDeptNames',$this->IniDeptNames,true);
      $criteria->compare('RespDeptName',$this->RespDeptName,true);
      $criteria->compare('FlowCreated',$this->FlowCreated,true);
      $criteria->compare('AnswerComment',$this->AnswerComment,true);
      $criteria->compare('AnswerDocID',$this->AnswerDocID);
      $criteria->compare('AnswerCreated',$this->AnswerCreated,true);
      $criteria->compare('AnswerDelay',$this->AnswerDelay);
      $criteria->compare('AnswerID',$this->AnswerID);
      if ($this->DocIDs){
        $d_criteria->compare('DocIDs',$this->DocIDs . ',',true,'OR');
        $d_criteria->compare('DocIDs',',' . $this->DocIDs ,true,'OR');
        $d_criteria->compare('DocIDs', $this->DocIDs ,false,'OR');
        $criteria->mergeWith($d_criteria);
      }
      $criteria->order = 'FlowCreated DESC, RespDeptName ASC';
    } else {
      $criteria->compare('idDocFlow',-1);
    }
    
    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination' => array(
          'pageSize' => 1000
      )
    ));
  }
}