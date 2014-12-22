<?php
/* @var $model Events */
/* @var $response string */
?>
<style>

h1 {
  text-align: center;
  font-family: Verdana;
  font-weight: normal;
  font-size: 20pt;
  margin-bottom: 0px;
  
}
.labelcontent {
  color: yellow;
}
.eventdetails {
  text-align: left;
  padding-top: 3px;
  padding-bottom: 3px;
}
.eventdescription{

}
.margin5{
  margin-left: 5px;
}
.eventtypetext{
  color: blue;
}
.eventkindtext{
  color: black;
}
</style>
<div class="row-fluid">
  <div class="row-fluid">
  <?php
    if ($model->ExternalID && strlen($model->NewsUrl) > 0){
        Yii::app()->user->setFlash('info', '<a href="'.$model->NewsUrl.'">Також є на сайті ЗНУ.</a>');
      } else {
	if ($response){
	  Yii::app()->user->setFlash('info', $response);
        }
      }
    if ($model->ExternalID && strlen($model->NewsUrl) > 0 || $response){
      $this->widget('bootstrap.widgets.TbAlert', array(
        'fade'=>true, // use transitions?
        'block'=>true,
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), 
            // success, info, warning, error or danger
        ),
        'htmlOptions' => array(
          'class' => 'span12 alert_info',
        )
      )); 
    }
    ?>
  </div>
  <div class="row-fluid">
    <?php
    if (($eflows = Docflowevents::model()->findAll('EventID='.$model->idEvent)) &&
      Yii::app()->user->id == $model->UserID){
        Yii::app()->user->setFlash('success', '<a href="'
	  .Yii::app()->CreateUrl('docflows/index',array('id' => $eflows[0]->DocFlowID))
	  .'">Розсилка запрошеним через документообіг.</a>');
	$this->widget('bootstrap.widgets.TbAlert', array(
	  'fade'=>true, // use transitions?
	  'block'=>true,
	  'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
	  'alerts'=>array( // configurations per alert type
	      'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), 
	      // success, info, warning, error or danger
	  ),
	  'htmlOptions' => array(
	    'class' => 'span12 alert_info',
	  )
	));
      } 
  ?>
  </div>
</div>
<?php
if (Yii::app()->user->checkAccess('showProperties') || Yii::app()->user->checkAccess('asEvent')){
  ?>
  &bullet;
  &bullet;
  <?php
  $this->widget('bootstrap.widgets.TbButton',array(
    'size' => 'mini',
    'type' => 'primary',
    'url' => Yii::app()->CreateUrl('events/update',array('id' => $model->idEvent)),
    'label' => 'Редагувати',
    'icon' => 'pencil white'
  ));
}
?>
&bullet;
&bullet;
<?php
$this->widget('bootstrap.widgets.TbButton',array(
  'size' => 'mini',
  'type' => 'primary',
  'url' => Yii::app()->CreateUrl('events/admin'),
  'label' => 'Перегляд заходів',
  'icon' => 'eye-open white'
));
?>
&bullet;
&bullet;
<?php
if (Yii::app()->user->checkAccess('showProperties') || (
  in_array('EventAdmin',User::model()->findByPk(Yii::app()->user->id)->getRoles())
  && $this->CheckDeptAccess($model->UserID,false)
)){
$this->widget('bootstrap.widgets.TbButton',array(
  'size' => 'mini',
  'type' => 'danger',
  'url' => Yii::app()->CreateUrl('events/delete',array('id' => $model->idEvent)),
  'label' => 'Видалити',
  'icon' => 'trash white',
  'htmlOptions' => array(
    'onclick' => 'if(!confirm("Остаточно?")){return false;}'
  )
));
}
?>
<div class='row-fluid'>
  <div class="span12 dfbox">
    <div class='row-fluid eventhead'>
      <h1 class='span12' >
        <?php echo (!(strpos($model->EventName,'<') === false || strpos($model->EventName,'>') ===false))? 
        htmlspecialchars($model->EventName):$model->EventName; ?>
      </h1>
    </div>
    <div class="row-fluid eventdetails">
      <span class="label label-info margin5"> Місце: 
        <span class="labelcontent">
        <?php 
        if (is_null($model->EventPlace) || (trim($model->EventPlace) === "")){
          echo "не вказано";
        } else{
          echo (!(strpos($model->EventPlace,'<') === false 
                  || strpos($model->EventPlace,'>') ===false))? 
            htmlspecialchars($model->EventPlace)
          : $model->EventPlace; 
        }
        ?>
        </span>
      </span>
      <span class="label label-info margin5"> Дата і час: 
        <span class="labelcontent">
        <?php 
        
        echo preg_replace("/,(\d\d?)(,|$)/i",",$1 числа кожного місяця$2",
          str_replace($this->wdays,$this->wday_alias,  mb_strtolower($model->DateSmartField,'utf8')))
          . " ".(($model->StartTime)? mb_substr($model->StartTime,0,5,"utf-8"): "(час початку не вказано)")
          .(($model->FinishTime)? " - ".mb_substr($model->FinishTime,0,5,"utf-8"): ""); 
        ?>
        </span>
      </span>
      <span
        class="label label-<?php echo $model->eventKind->EventKindStyle; ?> margin5">
        <?php echo $model->eventKind->EventKindName; ?>
      </span>
      <span
        class="label label-<?php echo $model->eventType->EventTypeStyle; ?> margin5">
        <?php echo $model->eventType->EventTypeName; ?>
      </span>
    </div>
  </div>
</div>

<div class="row-fluid">
  <div class="span12 dfbox">

    <div class="row-fluid">
      <div class="span12 dfheader">
        Опис заходу
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12 dfbox">
      <?php
        echo (!(strpos($model->EventDescription,'<') === false || strpos($model->EventDescription,'>') ===false))? 
          htmlspecialchars($model->EventDescription)
          :$model->EventDescription;
      ?>
      </div>
    </div>
    
    <div class="row-fluid">
      <div class="span4">
        <div class="row-fluid">
          <div class="span12 dfheader">
            Прикріплений файл
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12 dfbox">
          <?php
	    if ($model->FileID){
	      echo CHtml::link("[завантажити]",Yii::app()->CreateUrl('events/attachment',array('id' => $model->idEvent)));
            } else {
	      echo "Відсутній";
            }
          ?>
          </div>
        </div>
      </div>
      <div class="span4">
        <div class="row-fluid">
          <div class="span12 dfheader">
            Контактні дані
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12 dfbox">
          <?php
            echo (!(strpos($model->ResponsibleContacts,'<') === false || strpos($model->ResponsibleContacts,'>') ===false))? 
              htmlspecialchars($model->ResponsibleContacts)
              :$model->ResponsibleContacts;
          ?>
          </div>
        </div>
      </div>
      <div class="span4">
        <div class="row-fluid">
          <div class="span12 dfheader">
            Відповідальні особи
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12 dfbox">
          <?php
            echo (!(strpos($model->Responsible,'<') === false || strpos($model->Responsible,'>') ===false))? 
              htmlspecialchars($model->Responsible)
              :$model->Responsible;
          ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<div class="row-fluid">
  <div class="span12 dfbox">
    <div class="span6">
      <div class="row-fluid">
        <div class="span12 dfheader">
          Запрошені
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12 dfbox">
          <ul>
          <?php $vals = $model->getInvited(); 
	    if (!$model->isAllFacultiesInvited()){
	      for ($i = 0; ($i < count($vals) && is_array($vals)); $i++){
		echo '<li>'.$vals[$i]['InvitedComment'] . (($vals[$i]['Seets'] > 0)? ' ('.$vals[$i]['Seets']. ')' : '') .'</li>';
	      }
            } else {
	      echo "<li>Усі факультети ЗНУ</li>";
            }
          ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="span6">
      <div class="row-fluid">
        <div class="span12 dfheader">
          Організатори
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12 dfbox">
          <ul>
          <?php $vals = $model->getOrganizers(); 
            for ($i = 0; ($i < count($vals) && is_array($vals)); $i++){
              echo '<li>'.$vals[$i]['OrganizerComment'].'</li>';
            }
          ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>


