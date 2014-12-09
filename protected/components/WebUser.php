<?php
class WebUser extends CWebUser {
    
    public function getGroup(){
        if (!Yii::app()->user->hasState("group")){
            Yii::app()->user->setState("group","");
        }
        return Yii::app()->user->getState("group");
    }
    public function getUserModel(){
        return User::model()->findByPk($this->id);
    }

}
?>
