<?php
/* @var $this PropositionbookController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Proposition Books',
);

$this->menu=array(
	array('label'=>'Create Propositionbook', 'url'=>array('create')),
	array('label'=>'Manage Propositionbook', 'url'=>array('admin')),
);
?>

<h1>Proposition Books</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
