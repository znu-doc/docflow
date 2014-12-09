<?php
// Це дуже прикро...
/**
 * This is the model class for table "docflow_watch".
 *
 * The followings are the available columns in table 'docflow_watch':
 * @property integer $idDocFlow
 * @property string $DocFlowName
 * @property string $DocFlowDescription
 * @property integer $DocFlowTypeID
 * @property integer $DocFlowStatusID
 * @property integer $DocFlowGroupID
 * @property string $ExpirationDate
 * @property string $ControlDate
 * @property string $Created
 * @property string $Finished
 * @property integer $DocFlowPeriodID
 * @property string $DocFlowStatusName
 * @property integer $ToDeptID
 * @property string $ToDeptName
 * @property string $ToDeptInfo
 * @property integer $ToUserID
 * @property string $ToUserInfo
 * @property string $ToUserContacts
 * @property string $DocFlowGroupName
 * @property string $Owner
 * @property integer $OwnerID
 * @property string $Documents
 * @property string $DocumentDescriptions
 * @property integer $idDocFlowAnswer
 * @property integer $AnswerDeptID
 * @property integer $AnswerUserID
 * @property integer $AnswerTypeID
 * @property string $AnswerTimestamp
 * @property integer $AnswerDocumentID
 */
class DocflowWatch extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DocflowWatch the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'docflow_watch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DocFlowName, DocFlowTypeID, DocFlowStatusID, DocFlowGroupID', 'required'),
			array('idDocFlow, DocFlowTypeID, DocFlowStatusID, DocFlowGroupID, '
         . 'DocFlowPeriodID, ToDeptID, ToUserID, OwnerID, idDocFlowAnswer, '
         . 'AnswerDeptID, AnswerUserID, AnswerTypeID, '
         . 'AnswerDocumentID', 'numerical', 'integerOnly'=>true),
			array('DocFlowName, ControlDate, DocFlowStatusName, '
         . 'ToDeptName, ToUserContacts, DocFlowGroupName', 'length', 'max'=>255),
			array('DocFlowDescription, ExpirationDate, Created, Finished, '
         . 'ToDeptInfo, ToUserInfo, Owner, Documents, '
         . 'AnswerTimestamp, DocumentDescriptions', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idDocFlow, DocFlowName, DocFlowDescription, DocFlowTypeID, '
         . 'DocFlowStatusID, DocFlowGroupID, ExpirationDate, '
         . 'ControlDate, Created, Finished, DocFlowPeriodID, '
         . 'DocFlowStatusName, ToDeptID, ToDeptName, ToDeptInfo, '
         . 'ToUserID, ToUserInfo, ToUserContacts, DocFlowGroupName, '
         . 'Owner, OwnerID, Documents, idDocFlowAnswer, AnswerDeptID, '
         . 'AnswerUserID, AnswerTypeID, AnswerTimestamp, AnswerDocumentID, '
         . 'DocumentDescriptions', 
         'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// Це ж в'юха! Їй не потрібні стосунки.
		return array(
		);
	}
        

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
    'idDocFlow' => 'ID розсилки',
    'DocFlowName' => 'Назва документообігу',
    'DocFlowDescription' => 'Особливості процесу документообігу',
    'DocFlowTypeID' => 'Тип документообігу (ID)',
    'DocFlowStatusID' => 'Статус документообігу (ID)',
    'DocFlowGroupID' => 'Група розсилки (ID)',
    'ExpirationDate' => 'Дата закінчення',
    'ControlDate' => 'Контроль',
    'Created' => 'Створено',
    'Finished' => 'Завершено',
    'DocFlowPeriodID' => 'Періодичність',
    'DocFlowStatusName' => 'Статус документообігу',
    'ToDeptID' => 'Підрозділ-респондент (ID)',
    'ToDeptName' => 'Підрозділ-респондент',
    'ToDeptInfo' => 'Підрозділ-респондент (info)',
    'ToUserID' => 'Відповідальний за відповідь (ID)',
    'ToUserInfo' => 'Вповноважений надати відповідь',
    'ToUserContacts' => 'Контакти респондента',
    'DocFlowGroupName' => 'Група розсилки',
    'Owner' => 'Ініціатор розсилки',
    'OwnerID' => 'Ініціатор розсилки (ID)',
    'Documents' => 'Документи',
    'DocumentDescriptions' => 'Зміст документів',
    'idDocFlowAnswer' => 'Коментар',
    'AnswerDeptID' => 'Відповідь надано підрозділом',
    'AnswerUserID' => 'Відповідь надано користувачем',
    'AnswerTypeID' => 'Ознайомлення',
    'AnswerTimestamp' => 'Дата і час відповіді',
    'AnswerDocumentID' => 'Документ у відповідь',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Це пошук звичайний. Юзабіліті ледве-ледве досягається.

		$criteria=new CDbCriteria;

		$criteria->compare('idDocFlow',$this->idDocFlow);
		$criteria->compare('DocFlowName',$this->DocFlowName,true);
		$criteria->compare('DocFlowDescription',$this->DocFlowDescription,true);
		$criteria->compare('DocFlowTypeID',$this->DocFlowTypeID);
		$criteria->compare('DocFlowStatusID',$this->DocFlowStatusID);
		$criteria->compare('DocFlowGroupID',$this->DocFlowGroupID);
		$criteria->compare('ExpirationDate',$this->ExpirationDate,true);
		$criteria->compare('ControlDate',$this->ControlDate,true);
		$criteria->compare('Created',$this->Created,true);
		$criteria->compare('Finished',$this->Finished,true);
		$criteria->compare('DocFlowPeriodID',$this->DocFlowPeriodID);
		$criteria->compare('DocFlowStatusName',$this->DocFlowStatusName,true);
		$criteria->compare('ToDeptID',$this->ToDeptID);
		$criteria->compare('ToDeptName',$this->ToDeptName,true);
		$criteria->compare('ToDeptInfo',$this->ToDeptInfo,true);
		$criteria->compare('ToUserID',$this->ToUserID);
		$criteria->compare('ToUserInfo',$this->ToUserInfo,true);
		$criteria->compare('ToUserContacts',$this->ToUserContacts,true);
		$criteria->compare('DocFlowGroupName',$this->DocFlowGroupName,true);
		$criteria->compare('Owner',$this->Owner,true);
		$criteria->compare('OwnerID',$this->OwnerID);
		$criteria->compare('Documents',$this->Documents,true);
    $criteria->compare('DocumentDescriptions',$this->DocumentDescriptions,true);
		$criteria->compare('idDocFlowAnswer',$this->idDocFlowAnswer);
		$criteria->compare('AnswerDeptID',$this->AnswerDeptID);
		$criteria->compare('AnswerUserID',$this->AnswerUserID);
		$criteria->compare('AnswerTypeID',$this->AnswerTypeID);
		$criteria->compare('AnswerTimestamp',$this->AnswerTimestamp,true);
		$criteria->compare('AnswerDocumentID',$this->AnswerDocumentID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
         'pageSize' => 10
      )
		));
	}
}