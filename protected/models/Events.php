<?php

/**
 * This is the model class for table "events".
 *
 * The followings are the available columns in table 'events':
 * @property integer $idEvent
 * @property string $EventName
 * @property string $EventDescription
 * @property string $EventPlace
 * @property string $Responsible
 * @property string $DateSmartField
 * @property string $StartTime
 * @property string $FinishTime
 * @property integer $ExternalID
 * @property string $NewsUrl
 * @property integer $UserID
 * @property string $Created
 * @property string $ResponsibleContacts
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Files $attfile
 * @property Eventkinds $eventKind
 * @property Eventdates $eventDates
 */
class Events extends CActiveRecord
{
  public $invited_ids = array();
  public $invited_descrs = array();
  public $invited_seets = array();
  public $organizer_ids = array();
  public $organizer_descrs = array();
  public $event_dates = array();
  public $past;
  public $wdays = array("нд","пн","вт","ср","чт","пт","сб","нд");
  public $wday_alias = array(
     "нд" => "щонеділі",
     "пн" => "щопонеділка",
     "вт" => "щовівторка",
     "ср" => "щосереди",
     "чт" => "щочетверга",
     "пт" => "щоп`ятниці",
     "сб" => "щосуботи",
  );
  public $attachment;
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Events the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'events';
  }
  
    public function afterFind() {
        $this->invited_ids = array();
        $this->invited_descrs = array();
        $inv = Invited::model()->findAll("`EventID` = ".intval($this->idEvent));
        foreach ($inv as $item) {
            $this->invited_ids[] = $item->DeptID;
            $this->invited_descrs[] = $item->InvitedComment;
            $this->invited_seets[] = $item->Seets;
        }
        $this->organizer_ids = array();
        $this->organizer_descrs = array();
        $inv = Eventorganizers::model()->findAll("`EventID` = ".intval($this->idEvent));
        foreach ($inv as $item) {
            $this->organizer_ids[] = $item->DeptID;
            $this->organizer_descrs[] = $item->OrganizerComment;
        }
        $this->event_dates = array();
        $evdts = Eventdates::model()->findAll("`EventID` = ".intval($this->idEvent));
        foreach ($evdts as $item) {
            $this->event_dates[] = $item->EventDate;
        }
        return parent::afterFind();
    }

    public function afterSave() {
        $res = Invited::model()->deleteAll("`EventID` = ".intval($this->idEvent));
        if (!empty($this->invited_ids) && is_array($this->invited_ids) 
          && (count($this->invited_ids) === count($this->invited_descrs))) {
            for ($i = 0; $i < count($this->invited_ids); $i++) {
                $item = new Invited();
                $item->DeptID = $this->invited_ids[$i];
                $item->EventID = $this->idEvent;
                $item->InvitedComment = $this->invited_descrs[$i];
                $item->Seets = $this->invited_seets[$i];
                $item->save();
            }
        }
        $res = Eventorganizers::model()->deleteAll("`EventID` = ".intval($this->idEvent));
        if (!empty($this->organizer_ids) && is_array($this->organizer_ids) 
          && (count($this->organizer_ids) === count($this->organizer_descrs))) {
            for ($i = 0; $i < count($this->organizer_ids); $i++) {
                $item = new Eventorganizers();
                $item->DeptID = $this->organizer_ids[$i];
                $item->EventID = intval($this->idEvent);
                $item->OrganizerComment = $this->organizer_descrs[$i];
                $item->save();
            }
        }
        $res = Eventdates::model()->deleteAll("`EventID` = ".intval($this->idEvent));
        if (!empty($this->event_dates) && is_array($this->event_dates)) {
            foreach ($this->event_dates as $val) {
                $item = new Eventdates();
                $item->EventDate = $val;
                $item->EventID = $this->idEvent;
                $item->save();
            }
        }
        return parent::afterSave();
    }
    
    public function beforeDelete(){
      foreach (Eventorganizers::model()->findAll('EventID='.$this->idEvent) as $orgmodel){
        $orgmodel->delete();
      }
      foreach (Eventdates::model()->findAll('EventID='.$this->idEvent) as $edmodel){
        $edmodel->delete();
      }
      foreach (Invited::model()->findAll('EventID='.$this->idEvent) as $invmodel){
        $invmodel->delete();
      }
      $flow_ids = array();
      foreach (Docflowevents::model()->findAll('EventID='.$this->idEvent) as $eventflow){
        $flow_ids[$eventflow->DocFlowID] = $eventflow->DocFlowID;
        $eventflow->delete();
      }
      if (!empty($flow_ids)){
        Docflows::model()->deleteAll('idDocFlow IN('.implode($flow_ids,',').')');
      }
      return parent::beforeDelete();
    }
    
    public function getInvited(){
      $vals = array();
      $models = Invited::model()->findAll('EventID='.intval($this->idEvent));
      if (empty($models)){
        return '';
      }
      $k = 0;
      foreach ($models as $item){
        $vals[$k]['DeptID'] = $item->DeptID;
        $vals[$k]['InvitedComment'] = $item->InvitedComment;// . (($item->Seets > 0)? ' ('.$item->Seets. ')' : '');
        $vals[$k]['Seets'] = $item->Seets;
        $k++;
      }
      return $vals;
    }
    
    public function getOrganizers(){
      $vals = array();
      $models = Eventorganizers::model()->findAll('EventID='.intval($this->idEvent));
      if (empty($models)){
        return '';
      }
      $k = 0;
      foreach ($models as $item){
        $vals[$k]['DeptID'] = $item->DeptID;
        $vals[$k]['OrganizerComment'] = $item->OrganizerComment;
        $k++;
      }
      return $vals;
    }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('EventName, EventKindID, EventTypeID, UserID','required'),
      
      array('EventKindID, EventTypeID, UserID, ExternalID', 'numerical', 'integerOnly'=>true),
      array('EventName, DateSmartField, EventPlace, Responsible, NewsUrl, ResponsibleContacts', 'length', 'max'=>255),
      array('StartTime,FinishTime', 'length', 'max'=>64),
      array('EventDescription', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('idEvent, EventName, EventDescription, 
        EventPlace, Responsible, ResponsibleContacts, DateSmartField, EventKindID, 
        UserID, Created, NewsUrl, ExternalID, EventTypeID,
        StartTime,
        FinishTime', 'safe', 'on'=>'search'),
    );
  }
  
    
  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'user' => array(self::BELONGS_TO, 'User', 'UserID'),
      'eventKind' => array(self::BELONGS_TO, 'Eventkinds', 'EventKindID'),
      'eventType' => array(self::BELONGS_TO, 'Eventtypes', 'EventTypeID'),
      'attfile' => array(self::BELONGS_TO, 'Files', 'FileID'),
      'eventDates' => array(self::HAS_MANY, 'Eventdates', 'EventID'),
    );
  }
        

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
    'idEvent' => 'Id Event',
    'EventName' => 'Назва заходу (заголовок)',
    'EventDescription' => 'Опис заходу',
    'EventPlace' => 'Місце',
    'Responsible' => 'Відповідальна особа',
    'ResponsibleContacts' => 'Контакти',
    'DateSmartField' => 'Правило формування дат події',
    'EventKindID' => 'Вид заходу',
    'EventTypeID' => 'Рівень заходу',
    'invited' => 'Учасники',
    'organizers' => 'Організатори',
    'UserID' => 'Створив',
    'Created' => 'Створено',
    'NewsUrl' => 'Адреса новини на сайті',
    'ExternalID' => 'Ідентифікатор новини на сайті',
    'StartTime' => 'Час початку',
    'FinishTime' => 'Час завершення',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search()
  {
    return Eventdates::model()->search($this);
  }
}