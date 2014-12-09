<?php
/* @var $this DocflowtypesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docflowtypes',
);

$this->menu=array(
	array('label'=>'Create Docflowtypes', 'url'=>array('create')),
	array('label'=>'Manage Docflowtypes', 'url'=>array('admin')),
);
?>

<h1>Docflowtypes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
