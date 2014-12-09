<?php

/**
 * This is the model class for table "documentfiles".
 *
 * The followings are the available columns in table 'documentfiles':
 * @property integer $idDocumentFile
 * @property integer $DocumentID
 * @property integer $FileID
 * @property string $Comment
 *
 * The followings are the available model relations:
 * @property Documents $document
 * @property Files $file
 */
class DocumentFiles extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return DocumentFiles the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'documentfiles';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocumentID', 'required'),
        array('Comment', 'length', 'max' => 255),
        array('DocumentID, FileID', 'numerical', 'integerOnly' => true),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocumentFile, DocumentID, FileID, Comment', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'document' => array(self::BELONGS_TO, 'Documents', 'DocumentID'),
        'file' => array(self::BELONGS_TO, 'Files', 'FileID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocumentFile' => 'Id Document File',
        'DocumentID' => 'Документ',
        'FileID' => 'Файл',
        'Comment' => "Коментар",
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

    $criteria->compare('idDocumentFile', $this->idDocumentFile);
    $criteria->compare('DocumentID', $this->DocumentID);
    $criteria->compare('FileID', $this->FileID);
    $criteria->compare('Comment', $this->Comment);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

}
