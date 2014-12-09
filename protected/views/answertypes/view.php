<?php
/* @var $this AnswertypesController */
/* @var $model Answertypes */

$this->breadcrumbs=array(
	'Answertypes'=>array('index'),
	$model->idAnswerType,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Оновити', 'url'=>array('update', 'id'=>$model->idAnswerType)),
	array('label'=>'Видалити', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idAnswerType),
     'confirm'=>'Ось так, значить, видалити мене збираємось?')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Перегляд типу відповіді #<?php echo $model->idAnswerType; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idAnswerType',
		'AnswerTypeName',
	),
)); ?>
