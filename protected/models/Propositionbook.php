<?php

/**
 * This is the model class for table "proposition_book".
 *
 * The followings are the available columns in table 'proposition_book':
 * @property integer $idProposition
 * @property string $Proposition
 * @property integer $UserID
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Propositionbook extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Propositionbook the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'proposition_book';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('Proposition', 'required'),
        array('UserID', 'numerical', 'integerOnly' => true),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idProposition, Proposition, UserID', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'user' => array(self::BELONGS_TO, 'User', 'UserID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idProposition' => 'Id Proposition',
        'Proposition' => 'Скарга або пропозиція',
        'UserID' => 'Користувач',
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

    $criteria->compare('idProposition', $this->idProposition);
    $criteria->compare('Proposition', $this->Proposition, true);
    $criteria->compare('UserID', $this->UserID);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

}
