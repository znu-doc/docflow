<?php
/* @var $this DocflowstatusController */
/* @var $model Docflowstatus */

$this->breadcrumbs=array(
	'Docflowstatuses'=>array('index'),
	$model->idDocFlowStatus=>array('view','id'=>$model->idDocFlowStatus),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Огляд', 'url'=>array('view', 'id'=>$model->idDocFlowStatus)),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Оновлення статусу розсилок <?php echo $model->idDocFlowStatus; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>