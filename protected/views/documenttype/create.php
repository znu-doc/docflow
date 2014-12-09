<?php
/* @var $this DocumenttypeController */
/* @var $model Documenttype */

$this->breadcrumbs=array(
	'Documenttypes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Documenttype', 'url'=>array('index')),
	array('label'=>'Manage Documenttype', 'url'=>array('admin')),
);
?>

<h1>Create Documenttype</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>