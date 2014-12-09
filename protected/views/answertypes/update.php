<?php
/* @var $this AnswertypesController */
/* @var $model Answertypes */

$this->breadcrumbs=array(
	'Answertypes'=>array('index'),
	$model->idAnswerType=>array('view','id'=>$model->idAnswerType),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Додати', 'url'=>array('create')),
	array('label'=>'Перегляд', 'url'=>array('view', 'id'=>$model->idAnswerType)),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Оновлення тип відповіді з ідентифікатором <?php echo $model->idAnswerType; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>