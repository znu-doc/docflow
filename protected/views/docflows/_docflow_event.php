<?php
/* @var $event Events */

?>
<div class="dfeventrow row-fluid">
  <div class="row-fluid">
    <div class="span6">
      <div class="row-fluid">
        <span class="dfminicontenthead">Дата і час: </span>
        <span class="dfminicontent">
        <?php 
        $datetime_rule = preg_replace("/,(\d\d?)(,|$)/i",",$1 числа кожного місяця$2",
          str_replace($event->wdays,$event->wday_alias,  mb_strtolower($event->DateSmartField,'utf8')))
          . " ".(($event->StartTime)? mb_substr($event->StartTime,0,5,"utf-8"): "(час початку не вказано)")
          .(($event->FinishTime)? " - ".mb_substr($event->FinishTime,0,5,"utf-8"): "");
        $time_to_event = -1;
        if (!$event->StartTime){
          $event->StartTime = "00:00:00";
        }
        for ($i = 0; ($time_to_event < 0 && $i < count($event->eventDates)); $i++){
          $time_to_event = strtotime($event->eventDates[$i]->EventDate.' '.$event->StartTime) -
            strtotime(date('Y-m-d H:i:s'));
        }
        echo $datetime_rule;
        ?>
        </span>
        <?php
          echo (($time_to_event < 0)?
          '<div class="dfminicontenthead"> (подія вже відбулась) </div>'
            :
          ' <div class="dfminicontenthead"> залишилось' . ((count($event->eventDates) > 1)? 
	      ' до найближчої події':'') . '</div>' 
            . '<div class="dfminicontent"> днів: ' . (floor($time_to_event / (24.0*3600.0)))
            . ', годин: ' . (floor($time_to_event / (3600.0)) % 24)
            . '</div>'
	  );
        ?>
      </div>
      <div class="row-fluid">
        <span class="dfminicontenthead">Місце проведення: </span>
        <span class="dfminicontent">
        <?php echo (($event->EventPlace)? 
        $event->EventPlace:'дані відсутні'); ?>
        </span>
      </div>
    </div>
    <div class="span3">

    </div>
    <div class="span3">
      <?php echo CHtml::link('<i class="icon-share"></i>захід',
          Yii::app()->CreateUrl('/events/index',array('id' => $event->idEvent)), array(
            'title'=>'Перейти на перегляд/редагування заходу',
            'target' => '_blank',
            )
          ); ?>
    </div>
  </div>
  <div class="row-fluid dfdescr">
      <?php 
        echo $event->EventName; 
      ?>
  </div>
</div>