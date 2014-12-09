<?php
/* @var $this FunctionsController */
/* @var $model Functions */

$this->breadcrumbs=array(
	'Departments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список підрозділів', 'url'=>array('index')),
	array('label'=>'Управління записами', 'url'=>array('admin')),
);
?>

<h1>Додати новий підрозділ</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>