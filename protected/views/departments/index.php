<?php
/* @var $this FunctionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Departments',
);

$this->menu=array(
	array('label'=>'Список підрозділів', 'url'=>array('index')),
	array('label'=>'Управління записами', 'url'=>array('admin')),
);
?>

<h1>Functions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
