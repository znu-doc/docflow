<?php
/* @var $this DocumenttypeController */
/* @var $model Documenttype */

$this->breadcrumbs=array(
	'Documenttypes'=>array('index'),
	$model->idDocumentType,
);

$this->menu=array(
	array('label'=>'List Documenttype', 'url'=>array('index')),
	array('label'=>'Create Documenttype', 'url'=>array('create')),
	array('label'=>'Update Documenttype', 'url'=>array('update', 'id'=>$model->idDocumentType)),
	array('label'=>'Delete Documenttype', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDocumentType),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Documenttype', 'url'=>array('admin')),
);
?>

<h1>View Documenttype #<?php echo $model->idDocumentType; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idDocumentType',
		'DocumentTypeName',
		'info',
	),
)); ?>
