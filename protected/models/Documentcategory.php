<?php

/**
 * This is the model class for table "documentcategory".
 *
 * The followings are the available columns in table 'documentcategory':
 * @property integer $idDocumentCategory
 * @property string $DocumentCategoryName
 * @property string $info
 *
 * The followings are the available model relations:
 * @property Documents[] $documents
 */
class Documentcategory extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Documentcategory the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'documentcategory';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DocumentCategoryName, info', 'length', 'max' => 255),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDocumentCategory, DocumentCategoryName, info', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'documents' => array(self::HAS_MANY, 'Documents', 'DocumentCategoryID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDocumentCategory' => 'Id Document Category',
        'DocumentCategoryName' => 'Назва категорії документа',
        'info' => 'Info',
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

    $criteria->compare('idDocumentCategory', $this->idDocumentCategory);
    $criteria->compare('DocumentCategoryName', $this->DocumentCategoryName, true);
    $criteria->compare('info', $this->info, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * Метод повертає усі категорії документів. (!)
   * @return array (idDocumentCategory => DocumentCategoryName)
   */
  public static function DropDown() {
    $asOffice = Yii::app()->user->checkAccess('asOffice');
    $criteria = new CDbCriteria();
    $criteria->order = 'DocumentCategoryName';
    $criteria->compare('idDocumentCategory', ($asOffice)? array(): array(14,13)); 
    $models = Documentcategory::model()->findAll($criteria);
    $categories = array();
    foreach ($models as $model) {
      $categories[$model->idDocumentCategory] = $model->DocumentCategoryName;
    }
    return $categories;
  }
  
  /**
   * Метод повертає усі категорії документів.
   * @return array (idDocumentCategory => DocumentCategoryName)
   */
  public static function DropDownFull() {
    $criteria = new CDbCriteria();
    $criteria->order = 'DocumentCategoryName';
    $models = Documentcategory::model()->findAll($criteria);
    $categories = array();
    foreach ($models as $model) {
      $categories[$model->idDocumentCategory] = $model->DocumentCategoryName;
    }
    return $categories;
  }

}
