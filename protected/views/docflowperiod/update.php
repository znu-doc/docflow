<?php
/* @var $this DocflowperiodController */
/* @var $model Docflowperiod */

$this->breadcrumbs=array(
	'Docflowperiods'=>array('index'),
	$model->idDocFlowPeriod=>array('view','id'=>$model->idDocFlowPeriod),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Перегляд', 'url'=>array('view', 'id'=>$model->idDocFlowPeriod)),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Оновлення запису періодичності <?php echo $model->idDocFlowPeriod; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>