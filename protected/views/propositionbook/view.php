<?php
/* @var $this PropositionbookController */
/* @var $model Propositionbook */

$this->breadcrumbs=array(
	'Proposition Books'=>array('index'),
	$model->idProposition,
);

$this->menu=array(
	array('label'=>'List Propositionbook', 'url'=>array('index')),
	array('label'=>'Create Propositionbook', 'url'=>array('create')),
	array('label'=>'Update Propositionbook', 'url'=>array('update', 'id'=>$model->idProposition)),
	array('label'=>'Delete Propositionbook', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idProposition),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Propositionbook', 'url'=>array('admin')),
);
?>

<h1>View Propositionbook #<?php echo $model->idProposition; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idProposition',
		'Proposition',
		'UserID',
	),
)); ?>
