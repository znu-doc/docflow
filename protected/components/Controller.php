<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

  /**
   * @var string the default layout for the controller view. Defaults to '//layouts/column1',
   * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
   */
  public $layout = '//layouts/column1';
  /* @var $My_depts string - моделі підрозділів, до яких належить користувач */
  public $My_depts = array();
  /* @var $my_dept_ids string - ідентифікатори підрозділів, до яких належить користувач */
  public $my_dept_ids = array();
  /* @var $docflow_widget_mode string - стан перегляду розсилок (вхідні, вихідні тощо) */
  public $docflow_widget_mode = null;

  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu = array();

  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs = array();

  /**
   * Ініціатор усіх контролерів 
   */
  public function init() {
    parent::init();
    if (Yii::app()->request->getIsAjaxRequest()) {
      $this->layout = '//layouts/clear';
    }
    //якщо авторизовано
    if (Yii::app()->user->checkAccess('asOperatorStart')){
      $udmodels = Userdepartment::model()->findAll('UserID='.Yii::app()->user->id);
      foreach ($udmodels as $udmodel){
        /* @var $udmodel Userdepartment */
        $this->My_depts [] = $udmodel->dept;
        $this->my_dept_ids [] = $udmodel->DeptID;
      }
    }
  }

  /**
   * Метод перевіряє, чи є заданий ІД користувача зараз тим, хто увійшов у систему.
   * @param integer $UserID ID : sys_users.id
   * @param boolean $throw to throw exception and stop or not
   * @return boolean UserID == ID of user who loged on
   * @throws CHttpException
   */
  public function CheckAccess($UserID,$throw = true) {
    if ($UserID != Yii::app()->user->id) {
      if ($throw){
        throw new CHttpException(403, 'Ви не маєте достатньо прав для операції.');
      }
      return false;
    }
    return true;
  }
  
  /**
   * Метод перевіряє, чи співпадають підрозділи користувача в системі і користувача UserID
   * @param integer $UserID : sys_users.id
   * @param bool $throw to throw exception and stop or not
   * @throws CHttpException
   */
  public function CheckDeptAccess($UserID,$throw=true) {
    $udcount = Userdepartment::model()->count('DeptID IN ('.implode(',',$this->my_dept_ids).') AND UserID = '. $UserID);
    $is_own = ($udcount > 0);
    if (!$is_own && $throw) {
      throw new CHttpException(403, 'Ви не маєте достатньо прав для операції.');
    }
    return $is_own;
  }

  /**
   * Метод перераховує і зберігає квоту 
   * (допустиме місце на серверній стороні) для користувача.
   * @param integer $UserID ID : sys_users.id
   * @param float $add_to додати до квоти (МБ), може бути від'ємним
   * @return float
   * @throws CHttpException
   */
  public function RecountUserQuota($UserID, $add_to) {
    $udmodel = Userdepartment::model()->find('UserID=' . $UserID);
    if (!$udmodel) {
      $udmodel = new Userdepartment();
      $udmodel->quota = 1000;
      $udmodel->UserID = $UserID;
      $udmodel->DeptID = 1;
    }
    $udmodel->quota += $add_to;
    if ($udmodel->quota <= 0.0) {
      throw new CHttpException(403, 'Ви не можете завантажувати цей файл. Перевірте кількість вільного місця (квоту).');
    }
    if (!$udmodel->save()){
      throw new CHttpException(404, 'Помилка переобчислення квоти.');
    }
    return (float)$udmodel->quota;
  }
  
  /**
   * Оновлює статус документообігу (розсилки) з ідентифікатором $id.
   * @param integer $id ID : docflows.idDocFlow
   * @return int ID статусу розсилки
   */
  public static function UpdateDocflowStatus($id) {
    $model = Docflows::model()->findByPk($id);
    if (!$model){
      return ;
    }
    $docflowgroupdepts = $model->docFlowGroup->docflowgroupdepts;
    $time_is_up = false;
    // якщо контроль - це правильна дата (d-m-Y)
    if (!empty($model->ControlDate) && 
            preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $model->ControlDate)) {
      $control_time = strtotime($model->ControlDate . ' 23:59:59');
      $time_is_up = ($control_time - time() <= 0);
    } elseif (!empty($model->ExpirationDate) && 
            $model->ExpirationDate != '0000-00-00') {
      $expiration_time = strtotime($model->ExpirationDate . ' 23:59:59');
      $time_is_up = ($expiration_time - time() <= 0);
    }
    $everybody_has_been_answered = true;
    //визначається, чи всі надали відповідь
    foreach ($docflowgroupdepts as $docflowgroupdept) {
      $temp_model = Docflowanswers::model()->findByAttributes(
              array('DeptID' => $docflowgroupdept->DeptID, 
                  'DocFlowID' => $model->idDocFlow));
      if ($temp_model == null) {
        $everybody_has_been_answered = false;
        break;
      }
    }
    $model->Finished = date('Y-m-d h:i:s');
    if (!$everybody_has_been_answered && $time_is_up) {
      $model->DocFlowStatusID = 6; //zaversheno
      $model->save();
      return 6;
    }
    if (!$everybody_has_been_answered && !$time_is_up) {
      $model->DocFlowStatusID = 1; //new
      $model->Finished = null;
      $model->save();
      return 1;
    }
    //якщо всі надали відповідь і тип не ознайомлення
    if ($everybody_has_been_answered && $model->DocFlowTypeID != 3) {
      //якщо знайшлась відповідь типу "відхилення"
      $entities = DocflowAnswers::model()->findAll('DocflowID=' . $model->idDocFlow 
              . ' AND AnswerTypeID=2');
      if (!empty($entities)) {
        $model->DocFlowStatusID = 4; //vidhyleno
        $model->save();
        return 4;
      }
      //якщо знайшлась відповідь типу "ознайомлення"
      $info_entities = DocflowAnswers::model()->findAll('DocflowID=' . $model->idDocFlow 
              . ' AND AnswerTypeID=3');
      if (!empty($info_entities)) {
        $model->DocFlowStatusID = 5; //oznajomleno
        $model->save();
        return 5;
      }
      //в інших випадках
      $model->DocFlowStatusID = 3; //ukhavaleno
      $model->save();
      return 3;
    }
    //якщо всі надали відповідь і тип розсилки - ознайомлення
    if ($everybody_has_been_answered && $model->DocFlowTypeID == 3) {
      $model->DocFlowStatusID = 5; //oznajomleno
      $model->save();
      return 5;
    }
    return $model->DocFlowStatusID;
  }
  
  protected function CrudServiceDocflows($params,$operation){
    if (!isset($params['idDocFlow'])){
      $params['idDocFlow'] = NULL;
    }
    if (!isset($params['DocFlowName'])){
      $params['DocFlowName'] = '';
    }
    if (!isset($params['DocFlowDescription'])){
      $params['DocFlowDescription'] = '';
    }
    if (!isset($params['ControlDate'])){
      $params['ControlDate'] = '';
    }
    if (!isset($params['ExpirationDate'])){
      $params['ExpirationDate'] = date('Y-m-d 12:00:00',strtotime('tomorrow'));
    }
    if (!isset($params['DocFlowTypeID'])){
      $params['DocFlowTypeID'] = 
        ((mb_strlen($params['ControlDate'],'utf-8') > 0))? 4 : 3;
    }
    if (!isset($params['DocFlowStatusID'])){
      $params['DocFlowStatusID'] = 1;
    }
    if (!isset($params['Created'])){
      $params['Created'] = date('Y-m-d H:i:s');
    }
    if (!isset($params['Finished'])){
      $params['Finished'] = NULL;
    }
    if (!isset($params['DocFlowPeriodID'])){
      $params['DocFlowPeriodID'] = 1;
    }
    if (!isset($params['DocFlowGroupID'])){
      $params['DocFlowGroupID'] = 0;
    }
    
    $response = 1;
    if (!isset($params['group'])){
      $response;
      
    } elseif (is_array($params['group'])) {
      if (!isset($params['group']['DocflowGroupName'])){
        $params['group']['DocflowGroupName'] = '';
        if (!is_array($params['group']['dept_ids'])){
          $response;
          
        } elseif (empty($params['group']['dept_ids'])){
          $response;
        }
      }
    }
    $model->DocFlowName = htmlspecialchars($params['DocFlowName']);
    $model->DocFlowDescription = $params['DocFlowDescription'];
    if (!isset($params['DocFlowTypeID'])){
      $model->DocFlowTypeID = (isset($params['ControlDate']))? 4 : 3;
    } else {
      $model->DocFlowTypeID = intval($params['DocFlowTypeID']);
    }
    $model->DocFlowStatusID = inval($params['DocFlowStatusID']);
    switch ($operation){
      case 'create':
      break;
    }
  }
  
  /**
   * Повертає ID групи розсилки або створює і повертає ID.
   * @param integer[] $group_depts IDs of departments
   * @param string $group_name name of group for save
   * @return integer | boolean
   */
  protected function getDocFlowGroupID($group_depts = array(), $group_name = '') {
    $docflow_group_id = 0;
    //унікальний ідентифікатор для групи
    $group_ident = implode(';', $group_depts) . ' - ' . Yii::app()->user->id;
    $existing_group = Docflowgroups::model()->findByAttributes(
            array('Responsibility' => $group_ident)
    );
    if ($existing_group != null) {
      //якщо вже такий ідентификатор знайдено, то групу зберігати не потрібно,
      // але повернути її ID
      $docflow_group_id = $existing_group->idDocFlowGroup;
    } else {
      //інакше зберегти групу (опціонально з ім'ям)
      $docflow_group_model = new Docflowgroups();
      if ($group_name == '') {
        $docflow_group_model->DocflowGroupName = 'Група розсилки ' .
                date('Y-m-d H:i:s');
      } else {
        $docflow_group_model->DocflowGroupName = $group_name;
      }
      $docflow_group_model->OwnerID = Yii::app()->user->id;
      $docflow_group_model->Responsibility = $group_ident;
      if ($docflow_group_model->save()) {
        //ID збереженої групи
        $docflow_group_id = $docflow_group_model->getPrimaryKey();
      }
      if (!$docflow_group_id) {
        throw new CHttpException(400, 'Помилка створення групи для розсилки');
      } else {
        //у створено групу вносяться підрозділи
        $this->createDocFlowGroupDepts($docflow_group_id, $group_depts);
      }
    }
    return $docflow_group_id;
  }

  /**
   * Вносить підрозділи в існуючу групу.
   * @return boolean
   * @param integer[] $group_dept_ids IDs of departments
   * @param integer $group_id the ID of existsing group
   */
  protected function createDocFlowGroupDepts($group_id, $group_dept_ids = array()) {
    foreach ($group_dept_ids as $group_dept_id) {
      $model = new Docflowgroupdepts;
      $model->DocFlowGroupID = $group_id;
      $model->DeptID = $group_dept_id;
      if (!$model->save()) {
        throw new CHttpException(400, 'Помилка створення групи для розсилки: неможливо '
        . 'внести підрозділ №'
        . $group_dept_id);
      }
    }
    return true;
  }

  /*
  Author: Daniel Kassner
  Website: http://www.danielkassner.com
  */
  public function getOS($userAgent) {
    // Create list of operating systems with operating system name as array key 
    $oses = array (
      'iPhone' => '(iPhone)',
      'Windows 3.11' => 'Win16',
      'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
      'Windows 98' => '(Windows 98)|(Win98)',
      'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
      'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
      'Windows 2003' => '(Windows NT 5.2)',
      'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
      'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
      'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
      'Windows ME' => 'Windows ME',
      'Open BSD'=>'OpenBSD',
      'Sun OS'=>'SunOS',
      'Linux'=>'(Linux)|(X11)',
      'Safari' => '(Safari)',
      'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
      'QNX'=>'QNX',
      'BeOS'=>'BeOS',
      'OS/2'=>'OS/2',
      'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
    );

    foreach($oses as $os=>$pattern){ // Loop through $oses array
      // Use regular expressions to check operating system type
      if(eregi($pattern, $userAgent)) { // Check if a value in $oses array matches current user agent.
        return $os; // Operating system was matched so return $oses key
      }
    }
    return 'Unknown'; // Cannot find operating system so return Unknown
  }

}
