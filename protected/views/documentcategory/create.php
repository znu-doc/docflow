<?php
/* @var $this DocumentcategoryController */
/* @var $model Documentcategory */

$this->breadcrumbs=array(
	'Documentcategories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Documentcategory', 'url'=>array('index')),
	array('label'=>'Manage Documentcategory', 'url'=>array('admin')),
);
?>

<h1>Create Documentcategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>