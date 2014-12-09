<?php
/* @var $this DocumentcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Documentcategories',
);

$this->menu=array(
	array('label'=>'Create Documentcategory', 'url'=>array('create')),
	array('label'=>'Manage Documentcategory', 'url'=>array('admin')),
);
?>

<h1>Documentcategories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
