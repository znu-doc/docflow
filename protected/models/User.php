<?php

/**
 * This is the model class for table "sys_users".
 *
 * The followings are the available columns in table 'sys_users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $info
 * 
 * The followings are the available model relations:
 * @property Userdepartment[] $userdepartments
 * @propery Documents[] $documents
 * @propery Docflowgroups[] $docflowgroups
 * @propery Docflowanswers[] $docflowanswers
 */
class User extends CActiveRecord {
  public $searchDept;
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return User the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'sys_users';
  }

  protected function beforeSave() {
    parent::beforeSave();
    $this->password = md5($this->password);
    return true;
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('username, password, email, info', 'required'),
        array('username, password', 'length', 'max' => 255),
        array('email, info', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('id, username, password, email, info', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'userdepartments' => array(self::HAS_MANY, 'Userdepartment', 'UserID'),
        'documents' => array(self::HAS_MANY, 'Documents', 'UserID'),
        'docflowgroups' => array(self::HAS_MANY, 'Docflowgroups', 'OwnerID'),
        'docflowanswers' => array(self::HAS_MANY, 'Docflowanswers', 'UserID'),
        'departments' => array(self::HAS_MANY, 'Departments', 'DeptID', 'through' => 'userdepartments'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'id' => 'Код',
        'username' => "Ім'я користувача (логін)",
        'password' => 'Пароль',
        'email' => 'Контактні дані (телефон або e-mail)',
        'info' => 'ПІБ і посада',
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
    $criteria->with = array('departments');
    $criteria->group = 't.id';
    $criteria->together = true;
    
    $criteria->compare('t.id', $this->id);
    $criteria->compare('t.username', $this->username, true);
    $criteria->compare('t.password', $this->password, true);
    $criteria->compare('t.email', $this->email, true);
    $criteria->compare('t.info', $this->info, true);
    $criteria->compare('departments.DepartmentName', $this->searchDept->DepartmentName, true);
    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
        'pagination' => array(
            'pageSize' => 1000,
        )
    ));
  }
  
  /**
   * Метод повертає масив усіх користувачів, які мають групи розсилки документів.
   * @return array ($user->id => $user->info . ' [' . $user->email . ']')
   */
  public static function HasGroupDropDown(){
    $data = array();
    $users = User::model()->with('docflowgroups')->findAll('NOT(ISNULL(docflowgroups.OwnerID))');
    $data[""] = "";
    foreach ($users as $user){
      $data[$user->id] = $user->info . ' [' . $user->email . ']';
    }
    return $data;
  }
  
  /**
   * Метод повертає усі ID підрозділів користувача
   * @return integer[]
   */
  public function getDeptIDs(){
      $depts = $this->departments;
      $dept_ids = array();
      foreach ($depts as $dept){
        $dept_ids [] = $dept->idDepartment;
      }
      return $dept_ids;
  }
}
