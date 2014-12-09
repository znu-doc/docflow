<?php
/* @var $this AnswertypesController */
/* @var $model Answertypes */

$this->breadcrumbs=array(
	'Answertypes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Додати новий тип відповіді на розсилки</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>