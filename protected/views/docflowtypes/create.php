<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */

$this->breadcrumbs=array(
	'Docflowtypes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Docflowtypes', 'url'=>array('index')),
	array('label'=>'Manage Docflowtypes', 'url'=>array('admin')),
);
?>

<h1>Create Docflowtypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>