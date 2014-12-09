<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */

$this->breadcrumbs=array(
	'Docflowtypes'=>array('index'),
	$model->idDocFlowType,
);

$this->menu=array(
	array('label'=>'List Docflowtypes', 'url'=>array('index')),
	array('label'=>'Create Docflowtypes', 'url'=>array('create')),
	array('label'=>'Update Docflowtypes', 'url'=>array('update', 'id'=>$model->idDocFlowType)),
	array('label'=>'Delete Docflowtypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDocFlowType),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Docflowtypes', 'url'=>array('admin')),
);
?>

<h1>View Docflowtypes #<?php echo $model->idDocFlowType; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idDocFlowType',
		'DocFlowTypeName',
	),
)); ?>
