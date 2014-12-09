<?php
/* @var $this DocflowperiodController */
/* @var $model Docflowperiod */

$this->breadcrumbs=array(
	'Docflowperiods'=>array('index'),
	$model->idDocFlowPeriod,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Оновити', 'url'=>array('update', 'id'=>$model->idDocFlowPeriod)),
	array('label'=>'Видалити', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDocFlowPeriod),
     'confirm'=>'Я маю видалитись?')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Перегляд запису періодичності #<?php echo $model->idDocFlowPeriod; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idDocFlowPeriod',
		'PeriodName',
		'PeriodDescription',
	),
)); ?>
