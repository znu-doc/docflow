<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */

$this->breadcrumbs=array(
	'Docflowtypes'=>array('index'),
	$model->idDocFlowType=>array('view','id'=>$model->idDocFlowType),
	'Update',
);

$this->menu=array(
	array('label'=>'List Docflowtypes', 'url'=>array('index')),
	array('label'=>'Create Docflowtypes', 'url'=>array('create')),
	array('label'=>'View Docflowtypes', 'url'=>array('view', 'id'=>$model->idDocFlowType)),
	array('label'=>'Manage Docflowtypes', 'url'=>array('admin')),
);
?>

<h1>Update Docflowtypes <?php echo $model->idDocFlowType; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>