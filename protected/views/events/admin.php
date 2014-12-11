<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/events.css" />
<?php 
/* @var $model Events */

$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'events-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'rowHtmlOptionsExpression' => 'array(
      "id"=>"data-row-".$data->event->idEvent
    )',
    'columns'=>array(
      array(
        'header' => '#',
        'name' => 'past',
        'filter' => array(0 => "буде",1 => "було",2 => "зараз"),
        'headerHtmlOptions' => array(
          'style' => 'font-size: 9pt; width: 50px;'
        ),
        'filterHtmlOptions' => array(
          'style' => 'font-size: 9pt; width: 50px;'
        ),
        'htmlOptions' => array(
          'style' => 'width: 50px;'
        ),
        "value" => function ($data){
          if($data->past == 0){
            echo "<span class='label label-success'>буде</span>";
          }
          if($data->past == 1){
            echo "<span class='label label-red'>було</span>";
          }
          if($data->past == 2){
            echo "<span class='label label-warning'>зараз</span>";
          }
        },
        'type' => 'raw'
      ),
      array(
        'header' => 'Дата/час',
        'headerHtmlOptions' => array(
          'style' => 'font-size: 9pt;'
        ),
        'value' => 'date("d.m.Y",strtotime($data->EventDate))'
          . '.(($data->event->StartTime)? " ".mb_substr($data->event->StartTime,0,5,"utf-8"):"")',
        'type' => 'raw',
      ),
      array(
        'header' => 'Назва заходу',
        'headerHtmlOptions' => array(
          'style' => 'font-size: 9pt;'
        ),
        'name' => 'EventName',
        'value' => 'CHtml::link((!(strpos($data->event->EventName,"<") === false' 
          .'|| strpos($data->event->EventName,">") ===false))? '
          .'htmlspecialchars($data->event->EventName) : $data->event->EventName,'
          .'Yii::app()->CreateUrl("events/index",array("id"=>$data->event->idEvent)))'
          .'.(($data->event->NewsUrl)?CHtml::link("<i class=\'icon-share\'></i>",$data->event->NewsUrl):"")',
        'type' => 'raw'
      ),
      array(
        'header' => 'Місце',
        'headerHtmlOptions' => array(
          'style' => 'font-size: 9pt;'
        ),
        'name' => 'EventPlace',
        'value' => '(empty($data->event->EventPlace))? 
          "<span class=\'label\'>Не вказано</span>"
          : (!(strpos($data->event->EventPlace,"<") === false || strpos($data->event->EventPlace,">") ===false))? 
        htmlspecialchars($data->event->EventPlace):$data->event->EventPlace',
        'type' => 'raw'
      ),
      array(
        'filter' => false,
        'headerHtmlOptions' => array(
          'style' => 'font-size: 9pt; width: 185px;'
        ),
        'filterHtmlOptions' => array(
          'style' => 'font-size: 9pt; width: 185px;'
        ),
        'header' => 'Вид і рівень заходу',
        'value' => '"<span class=\'label label-".$data->event->eventKind->EventKindStyle."\' '
          . 'style=\'margin-left: 2px; margin-top: 2px;\'>"'
          . '.$data->event->eventKind->EventKindName."</span>".'
          .'"<span class=\'label label-".$data->event->eventType->EventTypeStyle."\' '
          . 'style=\'margin-left: 2px; margin-top: 2px;\'>"'
          . '.$data->event->eventType->EventTypeName."</span>"',
        'type' => 'raw',
      ),
      array(
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => (Yii::app()->user->checkAccess('showProperties') || Yii::app()->user->checkAccess('asEvent')) ? 
          '{update}' : '',
        //'deleteButtonUrl' => 'Yii::app()->CreateUrl("events/eventdatedelete",array("id"=>$data->idEventDate))',
        'updateButtonUrl' => 'Yii::app()->CreateUrl("events/update",array("id"=>$data->EventID))'
        //'filter' => '',
      ),
    ),
  )
); 

?>
<script>
$('.datepicker').datepicker({format: "dd.mm.yyyy", weekStart: 1});
</script>