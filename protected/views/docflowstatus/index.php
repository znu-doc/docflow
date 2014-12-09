<?php
/* @var $this DocflowstatusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docflowstatuses',
);

$this->menu=array(
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Статуси документообігу (розсилок)</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
