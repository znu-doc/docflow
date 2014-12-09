<?php
/* @var $this PropositionbookController */
/* @var $model Propositionbook */

$this->breadcrumbs=array(
	'Proposition Books'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Propositionbook', 'url'=>array('index')),
	array('label'=>'Manage Propositionbook', 'url'=>array('admin')),
);
?>

<h1>Створення</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>