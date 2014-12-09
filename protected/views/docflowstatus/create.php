<?php
/* @var $this DocflowstatusController */
/* @var $model Docflowstatus */

$this->breadcrumbs=array(
	'Docflowstatuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Створення нового статусу документообігу</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>