<?php

/* @var $model Docflowanswers */
/* @var $id integer */
?>
<h4>Перегляд зведення ознайомлення респондентів для документа # 
  <?php echo $id; ?>
 (<?php echo Documents::model()->findByPk($id)->DocumentDescription; ?>)
</h4>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'docflow-watch-grid',
    'dataProvider' => $model->search_answerlist($id),
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'Ініціатор',
            'filter' => false,
            'value' => '$data->docFlow->docFlowGroup->owner->departments[0]->DepartmentName',
            'type' => 'raw',),
        array(
            'name' => 'department.DepartmentName',
            'header' => 'Ознайомлені',
            'filter' => CHtml::activeTextField($model->searchDept, 'DepartmentName'),
            'value' => '$data->department->DepartmentName',
            'type' => 'raw',),
        array(
            'name' => 'docFlow.DocFlowName',
            'filter' => CHtml::activeTextField($model->searchFlow, 'DocFlowName'),
            'value' => '$data->docFlow->DocFlowName',
            'type' => 'raw',),
        array(
            'name' => 'docFlow.Created',
            'header' => 'Розсилка почалась',
            'filter' => CHtml::activeTextField($model->searchFlow, 'Created'),
            'value' => '$data->docFlow->Created',
            'type' => 'raw',),
        array(
            'name' => 'DocFlowAnswerText',
            'header' => 'Коментар'),
        array(
            'name' => 'AnswerTimestamp',
            'header' => 'Дата і час відповіді'),
        array('name' => 't.DocumentID',
            'header' => 'Документ у відповідь',
            'value' => function ($data){ echo ((!$data->DocumentID)? "немає" :
						CHtml::link('Завантажити',
						 Yii::app()->CreateUrl('files/DownloadFile/'.
						   Documents::LastDocVersion($data->DocumentID)
						))); },
            'type' => 'raw',
        ),
    ),
    'htmlOptions' => array('style' => 'font-size: 8pt;'),
));
?>
