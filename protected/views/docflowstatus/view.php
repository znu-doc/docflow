<?php
/* @var $this DocflowstatusController */
/* @var $model Docflowstatus */

$this->breadcrumbs=array(
	'Docflowstatuses'=>array('index'),
	$model->idDocFlowStatus,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Створити', 'url'=>array('create')),
	array('label'=>'Оновити', 'url'=>array('update', 'id'=>$model->idDocFlowStatus)),
	array('label'=>'Видалити', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDocFlowStatus),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Перегляд статусу #<?php echo $model->idDocFlowStatus; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idDocFlowStatus',
		'DocFlowStatusName',
		'DocFlowStatusDescription',
	),
)); ?>
