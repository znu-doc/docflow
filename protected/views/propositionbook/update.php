<?php
/* @var $this PropositionbookController */
/* @var $model Propositionbook */

$this->breadcrumbs=array(
	'Proposition Books'=>array('index'),
	$model->idProposition=>array('view','id'=>$model->idProposition),
	'Update',
);

$this->menu=array(
	array('label'=>'List Propositionbook', 'url'=>array('index')),
	array('label'=>'Create Propositionbook', 'url'=>array('create')),
	array('label'=>'View Propositionbook', 'url'=>array('view', 'id'=>$model->idProposition)),
	array('label'=>'Manage Propositionbook', 'url'=>array('admin')),
);
?>

<h1>Update Propositionbook <?php echo $model->idProposition; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>