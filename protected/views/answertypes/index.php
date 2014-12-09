<?php
/* @var $this AnswertypesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Answertypes',
);

$this->menu=array(
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Типи відповідей на розсилки</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
