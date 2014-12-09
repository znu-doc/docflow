<?php
/* @var $this FunctionsController */
/* @var $model Functions */

$this->breadcrumbs=array(
	'Departments'=>array('index'),
	$model->idDepartment=>array('view','id'=>$model->idDepartment),
	'Update',
);

$this->menu=array(
	array('label'=>'Список підрозділів', 'url'=>array('index')),
	array('label'=>'Додати новий', 'url'=>array('create')),
	array('label'=>'Перегляд', 'url'=>array('view', 'id'=>$model->idDepartment)),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Оновлення інформації про підрозділ <?php echo $model->idDepartment; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>