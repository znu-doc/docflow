<?php

/**
 * This is the model class for table "monitoring_answers".
 *
 * The followings are the available columns in table 'monitoring_answers':
 * @property integer $id
 * @property integer $q1
 * @property integer $q2
 * @property integer $q3
 * @property integer $q4
 * @property integer $q5
 * @property integer $q6
 * @property integer $q7
 * @property integer $q8
 * @property integer $q9
 * @property string $q10
 * @property integer $q11
 * @property integer $q12
 * @property integer $q13
 * @property integer $UserID
 * @property string $Created
 *
 * The followings are the available model relations:
 * @property SysUsers $user
 */
class Monitoringanswers extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Monitoringanswers the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'monitoring_answers';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('UserID, Created, q1, q2, q3, q4, q5, q6, q7, q8, q9, q11, q12, q13', 'required'),
        array('q1, q2, q3, q4, q5, q6, q7, q8, q9, q11, q12, q13, UserID', 'numerical', 'integerOnly' => true),
        array('q10', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, q11, q12, q13, UserID, Created', 'safe', 'on' => 'search'),
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
        'id' => 'ID',
        'q1' => 'Питання 1',
        'q2' => 'Питання 2',
        'q3' => 'Питання 3',
        'q4' => 'Питання 4',
        'q5' => 'Питання 5',
        'q6' => 'Питання 6',
        'q7' => 'Питання 7',
        'q8' => 'Питання 8',
        'q9' => 'Питання 9',
        'q10' => 'Питання 10',
        'q11' => 'Питання 11',
        'q12' => 'Питання 12',
        'q13' => 'Питання 13',
        'UserID' => 'User',
        'Created' => 'Created',
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

    $criteria->compare('id', $this->id);
    $criteria->compare('q1', $this->q1);
    $criteria->compare('q2', $this->q2);
    $criteria->compare('q3', $this->q3);
    $criteria->compare('q4', $this->q4);
    $criteria->compare('q5', $this->q5);
    $criteria->compare('q6', $this->q6);
    $criteria->compare('q7', $this->q7);
    $criteria->compare('q8', $this->q8);
    $criteria->compare('q9', $this->q9);
    $criteria->compare('q10', $this->q10, true);
    $criteria->compare('q11', $this->q11);
    $criteria->compare('q12', $this->q12);
    $criteria->compare('q13', $this->q13);
    $criteria->compare('UserID', $this->UserID);
    $criteria->compare('Created', $this->Created, true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

  /**
   * Отримання питання за його індексом.
   * @param integer $i
   * @return string
   */
  public function getQuestion($i) {
    $questions = array(
        0 => 'Як часто до Вашого структурного підрозділу надходять документи через електронний документообіг?',
        1 => 'Чи зробила система електронного документообігу зручнішою Вашу роботу з документами?',
        2 => 'Чи ознайомлювались Ви з інструкцією користувача ПЗ "Документообіг"?',
        3 => 'Оцініть, будь-ласка, якість інструкції.',
        4 => 'Чи знаете Ви про можливість налаштування виринаючих вікон при надходженні нового документу '
        . '(зникає необхідність ручного оновлення сторінки для перевірки наявності нових документів)?',
        5 => 'Хто знає Ваш пароль від електронного документообігу?',
        6 => 'Оцініть швидкість роботи ПЗ "Документообіг" (чи довго Ви чекаєте, пока завантажаться сторінки і т.п.)',
        7 => 'Якщо Ви звертались до нас з питаннями чи за допомогою щодо ПЗ "Документообіг", то оцініть, будь-ласка, нашу роботу.',
        8 => 'Оцініть, будь-ласка, зовнішній вигляд, зручність розташування кнопок та т. п.',
        9 => 'Якщо у Вас є конкретні пропозиції по вдосконаленню зовнішнього вигляду та розташуванню окремих елементів (наприклад, збільшіть шрифт), то впишіть їх',
        10 => 'Чи знаете Ви про можливість подачі електронної заявки, в разі виникнення проблем з комп\'ютером, Інтернетом та програмним забезпеченням, до ІТ служб ЗНУ',
        11 => 'Оцініть швидкість доступу до Інтренет в ЗНУ',
        12 => 'Оцініть наявність доступу до Інтренет в ЗНУ'
    );
    return $questions[$i];
  }

  /**
   * Повертає варіант за індексом i або масив варіантів питання з ID=q
   * @param integer $q Індекс питання
   * @param integer $i Індекс варіанта відповіді
   * @return string | array
   */
  public function QDropDown($q, $i = null) {
    $res[0] = array(
        3 => 'кожного дня',
        2 => 'декілька разів на тиждень',
        1 => 'приблизно раз на тиждень',
        0 => 'рідше ніж раз на тиждень',
    );
    $res[1] = array(
        2 => 'так',
        1 => 'ні',
        0 => 'зовсім ні, тільки збільшила обсяг роботи',
    );
    $res[2] = array(
        3 => 'так, читаю кожного дня',
        2 => 'читав/читала',
        1 => 'вона мені не потрібна',
        0 => 'ні, а де це?',
    );
    $res[3] = array(
        3 => 'повна та зрозуміла',
        2 => 'описано не все, що потрібно',
        1 => 'дуже погана, нічого не зрозіміло',
        0 => 'оцінювати не буду, бо не читав/читала',
    );
    $res[4] = array(
        3 => 'так, користуюсь',
        2 => 'так, але не користуюсь',
        1 => 'ні, не хочу користуватись',
        0 => 'налаштуйте мені, буду користуватись',
    );
    $res[5] = array(
        2 => 'тільки я',
        1 => 'я і всі мої колеги (бо, наприклад, він записаний на папірці під клавіатурою)',
        0 => 'я, всі колеги та всі друзі в соціальних мережах, мені немає чого приховувати',
    );
    $res[6] = array(
        2 => 'працює досить швидко',
        1 => 'не досить швидко, але я терпляче чекаю',
        0 => 'дуже повільно',
    );
    $res[7] = array(
        2 => 'все вирішили та допомогли досить оперативно',
        1 => 'все вирішили та допомогли, але прийшлось довго чекати',
        0 => 'нічим не допомогли, хоч і звертався',
    );
    $res[8] = array(
        2 => 'все гарно та зручно',
        1 => 'загалом задовільняє, але дуже далеко до досконалості',
        0 => 'просто жах',
    );
    $res[10] = array(
        1 => 'знаю',
        0 => 'не знаю',
    );
    $res[11] = array(
        2 => 'швидко',
        1 => 'не дуже швидко',
        0 => 'дуже повільно',
    );
    $res[12] = array(
        2 => 'практично завжди є доступ',
        1 => 'інколи доступ відсутній, саме в той момент, коли потрібен',
        0 => 'часто немає доступу до Інтренет',
    );
    if ($i !== null) {
      return $res[$q][$i];
    }
    return $res[$q];
  }

  /**
   * Метод повертає масив усіх користувачів, що дали відповідь на анкетуванні.
   * @return array (UserID => info)
   */
  public function UserDropDown() {
    $model = new Monitoringanswers;
    $criteria = new CDbCriteria();
    $criteria->group = 'UserID';
    $users = array();
    foreach ($model->findAll($criteria) as $m) {
      $user = User::model()->findByPk($m->UserID);
      $users[$m->UserID] = $user->info;
    }
    return $users;
  }

}
