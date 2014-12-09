<?php

/**
 * This is the model class for table "directories".
 *
 * The followings are the available columns in table 'directories':
 * @property integer $idDirecrtory
 * @property string $DirectoryName
 * @property string $DirectoryInfo
 * @property string $DirectoryLink
 * @property integer $Visible
 * @property integer $Access
 */
class Directories extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Directories the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }
  
	/**
	 * @return array menu list (id=>name)
	 */
  public static function listMenu() {
    $list = self::model()->findAll();
    $arr = array();
    foreach ($list as $value) {
      if ($value->Visible) {
        $tmarr = array(
           'label' => $value->DirectoryName, 
           'url' => yii::app()->createUrl($value->DirectoryLink),
           'visible' =>
                Yii::app()->user->checkAccess('showDirectiries') || 
                ((strpos($value->DirectoryName, 'заход'))? 
                  Yii::app()->user->checkAccess('asEvent') : false) ||
                ((strpos($value->DirectoryName, 'докуме'))? 
                  Yii::app()->user->checkAccess('asOffice') : false)
        );
        array_push($arr, $tmarr);
      }
    }
    return $arr;
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'directories';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('DirectoryName, DirectoryInfo, DirectoryLink, Visible ', 'required'),
        array('Access', 'safe'),
        array('Visible', 'numerical', 'integerOnly' => true),
        array('DirectoryName, DirectoryLink', 'length', 'max' => 255),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idDirecrtory, DirectoryName, DirectoryInfo, DirectoryLink, Visible', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idDirecrtory' => 'Id Direcrtory',
        'DirectoryName' => 'Назва',
        'DirectoryInfo' => 'Заальна інформація',
        'DirectoryLink' => 'Посилання',
        'Visible' => 'Відображати при виборі',
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

    $criteria->compare('idDirecrtory', $this->idDirecrtory);
    $criteria->compare('DirectoryName', $this->DirectoryName, true);
    $criteria->compare('DirectoryInfo', $this->DirectoryInfo, true);
    $criteria->compare('DirectoryLink', $this->DirectoryLink, true);
    $criteria->compare('Visible', $this->Visible);
    $criteria->compare('Access', $this->Access);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

}
