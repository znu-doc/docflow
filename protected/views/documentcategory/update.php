<?php
/* @var $this DocumentcategoryController */
/* @var $model Documentcategory */

$this->breadcrumbs=array(
	'Documentcategories'=>array('index'),
	$model->idDocumentCategory=>array('view','id'=>$model->idDocumentCategory),
	'Update',
);

$this->menu=array(
	array('label'=>'List Documentcategory', 'url'=>array('index')),
	array('label'=>'Create Documentcategory', 'url'=>array('create')),
	array('label'=>'View Documentcategory', 'url'=>array('view', 'id'=>$model->idDocumentCategory)),
	array('label'=>'Manage Documentcategory', 'url'=>array('admin')),
);
?>

<h1>Update Documentcategory <?php echo $model->idDocumentCategory; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>