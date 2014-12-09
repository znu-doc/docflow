<?php
/* @var $this DocflowstatusController */
/* @var $model Docflowstatus */

$this->breadcrumbs=array(
	'Docflowstatuses'=>array('index'),
	'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);
/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('docflowstatus-grid', {
data: $(this).serialize()
});
return false;
});
");*/
?>

<h1>Довідник статусів розсилок</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'docflowstatus-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'idDocFlowStatus',
		'DocFlowStatusName',
		'DocFlowStatusDescription',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
