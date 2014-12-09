<?php

/**
 * This is the model class for table "docflowanswers".
 * Відповіді на розсилки.
 * Я свідомо використав неоптимальну надмірність (db - redudance),
 *   зберігаючі і ІН користувача і ІН його підрозділу для швидкості вибірок.
 * 
 * The followings are the available columns in table 'docflowanswers':
 * @property integer $idDocFlowAnswer
 * @property integer $DocFlowID
 * @property string $DocFlowAnswerText
 * @property integer $DeptID
 * @property integer $UserID
 * @property integer $AnswerTypeID
 * @property string $AnswerTimestamp
 * @property integer $DocumentID

 *
 * The followings are the available model relations:
 * @property Departments $department
 * @property Answertypes $answerType
 */
class Docflowanswers extends CActiveRecord {
    public $searchFlow;
    public $searchDept;
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Docflowanswers the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'docflowanswers';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DeptID, AnswerTypeID, DocFlowID, UserID', 'required'),
        array('DeptID, AnswerTypeID, DocFlowID, UserID, DocumentID', 'numerical', 'integerOnly' => true),
        array('DocFlowAnswerText,AnswerTimestamp', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocFlowAnswer, DocFlowAnswerText, DeptID, AnswerTypeID, '
           . 'AnswerTimestamp, DocFlowID, UserID, DocumentID', 
           'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'department' => array(self::BELONGS_TO, 'Departments', 'DeptID'),
        'user' => array(self::BELONGS_TO, 'User', 'UserID'),
        'answerType' => array(self::BELONGS_TO, 'Answertypes', 'AnswerTypeID'),
        'docFlow' => array(self::BELONGS_TO, 'Docflows', 'DocFlowID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocFlowAnswer' => 'Id Doc Flow Answer',
        'DocFlowID' => 'ID DocFLOW',
        'DocFlowAnswerText' => 'Текст відповіді',
        'DeptID' => 'Підрозділ',
        'UserID' => 'Відповідальний',
        'AnswerTypeID' => 'Тип відповіді',
        'AnswerTimestamp' => 'Рішення прийнято',
        'DocumentID' => 'Doc',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;

    $criteria->compare('idDocFlowAnswer', $this->idDocFlowAnswer);
    $criteria->compare('DocFlowAnswerText', $this->DocFlowAnswerText, true);
    $criteria->compare('DeptID', $this->DeptID);
    $criteria->compare('UserID', $this->UserID);
    $criteria->compare('AnswerTypeID', $this->AnswerTypeID);
    $criteria->compare('AnswerTimestamp', $this->AnswerTimestamp, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }


}