<?php
/* @var $this EventkindsController */
/* @var $model Eventkinds */

$this->menu=array(
  array('label'=>'Перегляд', 'url'=>array('admin')),
  array('label'=>'Створити', 'url'=>array('create')),
);
?>

<h1>Форма оновлення запису #<?php echo $model->idEventKind; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>