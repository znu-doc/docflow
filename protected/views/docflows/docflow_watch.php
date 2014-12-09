<?php 
/* @var $model StatAll */
/* @var $flowname string */

?>
<div class="well well-small">
  <h4>
  Перегляд таблиці ознайомлення процесу документообігу <?php echo $flowname; ?>
  </h4>
</div>
<?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'docflow-watch-grid-'.$model->idDocFlow,
        'dataProvider' => $model->search(),
        'emptyText' => 'Відсутні',
        'columns' => array(
            array(
                'header' => 'Респондент',
                'filter' => false,
                'value' => 'empty($data->AnswerCreated) ? 
                  "<span style=\'color: red;\'>".$data->RespDeptName."</span>" 
                  :
                  "<span style=\'color: green;\'>".$data->RespDeptName."</span>"',
                'type' => 'raw',),
            array(
                'value' => 'empty($data->AnswerCreated) ? 
                  "<span style=\'color: red;\'>немає</span>" 
                  :$data->AnswerCreated',
                'header' => 'Інформовано',
                'type' => 'raw'),
            array(
                'header' => 'Коментар',
                'value' => '(!empty($data->AnswerComment))? $data->AnswerComment:""'),
            array(
                'header' => 'Док. у відповідь',
                'value' => function ($data){ echo ((!$data->AnswerDocID)? "немає" :
                CHtml::link('переглянути',
                 Yii::app()->CreateUrl('documents/index',
                   array('id'=>$data->AnswerDocID)
                ))); },
                'type' => 'raw',
            ),
            array(
                'header' => 'Затримка',
                'value' => function ($data){ echo ((!$data->AnswerDelay)? "--" :
                  $data->AnswerDelay); 
                 },
                'type' => 'raw',
            ),
        ),
        'htmlOptions' => array('style' => 'font-size: 8pt;'),
    ));
?>