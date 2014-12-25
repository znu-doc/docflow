<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property integer $idFile
 * @property string $FileLocation
 * @property string $FileName
 * @property integer $FileVisibility
 * @property integer $UserID
 * @property string $FileTimeStamp
 *
 * The followings are the available model relations:
 * @property Documentfiles[] $documentfiles
 * @property User $user
 */
class Files extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Files the static model class
   */
  public $file_itself;
  public $file_list;
  public $linkedDocumentName;
  public $full_path;
  public $folder;
  
  public function init(){
    $this->folder = Yii::app()->params['docPath'];
    return parent::init();
  }

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'files';
  }
  
  public function beforeDelete(){
    DocumentFiles::model()->deleteAll('FileID='.$this->idFile);
    if (is_file($this->folder . $this->FileLocation)){
      $size = filesize($this->folder . $this->FileLocation);
      $udmodel = Userdepartment::model()->find('UserID=' . Yii::app()->user->id);
      if (!$udmodel) {
	$udmodel = new Userdepartment();
	$udmodel->quota = 1000;
	$udmodel->UserID = Yii::app()->user->id;
	$udmodel->DeptID = 1;
      }
      $udmodel->quota += $size;
      if (!$udmodel->save()){
	throw new CHttpException(404, 'Помилка переобчислення квоти.');
      }
      unlink($this->folder . $this->FileLocation);
    }
    
    return parent::beforeDelete();
  }
  
  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        //array('file_itself', 'required'),
        array('FileVisibility', 'numerical', 'integerOnly' => true),
        array('FileLocation, FileName', 'length', 'max' => 255),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idFile, FileLocation, FileName, '
            . 'FileVisibility, FileTimeStamp',
            'safe', 'on' => 'search'),
        array('file_itself', 'file', 'types' => '
        pdf, rtf, odt, ods, txt, csv,  
        jpg, gif, png, tiff, tif, bmp, jpeg, 
        doc, docx, xls, xlsx, ppt, pptx, 
        html, htm, js, css, 
        zip, rar, 7z, tar, gz'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'documentfiles' => array(self::HAS_MANY, 'DocumentFiles', 'FileID'),
        'user' => array(self::BELONGS_TO, 'User', 'UserID'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idFile' => 'Id File',
        'FileLocation' => 'Фізичне розташування файлу',
        'FileName' => 'Ім\'я файлу',
        'FileVisibility' => 'Доступний для бачення',
        'FileTimeStamp' => 'Час створення',
        'file_itself' => 'Файл',
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

    $criteria->compare('idFile', $this->idFile);
    $criteria->compare('FileLocation', $this->FileLocation, true);
    $criteria->compare('FileName', $this->FileName, true);
    $criteria->compare('FileVisibility', $this->FileVisibility);

    $criteria->compare('FileTimeStamp', $this->FileTimeStamp, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }
  
  /**
   * Метод визначає, чи існує реальний файл поточної моделі
   * @return boolean
   */
  public function FileExists(){
    //визначення шляху, де знаходиться файл
    $file_entity = $this->folder . $this->FileLocation;
    return is_file($file_entity);
  }
  
  /**
   * Метод повертає повний абсолютний шлях до файлу з назвою включно
   * @return string
   */
  public function getFullName(){
    //визначення шляху, де знаходиться файл
    return $this->folder . $this->FileLocation;
  }

}
