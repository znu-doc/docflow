<?php
/* @var $this FunctionsController */
/* @var $model Functions */

$this->breadcrumbs=array(
	'Functions'=>array('index'),
	$model->idDepartment,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати новий', 'url'=>array('create')),
	array('label'=>'Оновити', 'url'=>array('update', 'id'=>$model->idDepartment)),
	array('label'=>'Видалити', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDepartment),
     'confirm'=>'Впевненні?')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>View Functions #<?php echo $model->idDepartment; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idDepartment',
		'DepartmentName',
		'FunctionDescription',
	),
)); ?>
