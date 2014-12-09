<?php
/* @var $this DocumenttypeController */
/* @var $model Documenttype */

$this->breadcrumbs=array(
	'Documenttypes'=>array('index'),
	$model->idDocumentType=>array('view','id'=>$model->idDocumentType),
	'Update',
);

$this->menu=array(
	array('label'=>'List Documenttype', 'url'=>array('index')),
	array('label'=>'Create Documenttype', 'url'=>array('create')),
	array('label'=>'View Documenttype', 'url'=>array('view', 'id'=>$model->idDocumentType)),
	array('label'=>'Manage Documenttype', 'url'=>array('admin')),
);
?>

<h1>Update Documenttype <?php echo $model->idDocumentType; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>