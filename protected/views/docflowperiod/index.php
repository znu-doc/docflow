<?php
/* @var $this DocflowperiodController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docflowperiods',
);

$this->menu=array(
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Періодичність документообігу</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
