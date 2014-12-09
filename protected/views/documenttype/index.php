<?php
/* @var $this DocumenttypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Documenttypes',
);

$this->menu=array(
	array('label'=>'Create Documenttype', 'url'=>array('create')),
	array('label'=>'Manage Documenttype', 'url'=>array('admin')),
);
?>

<h1>Documenttypes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
