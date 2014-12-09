<?php
/* @var $this EventtypesController */
/* @var $model Eventtypes */

$this->menu=array(
  array('label'=>'Перегляд', 'url'=>array('admin')),
);
?>

<h1>Форма створення</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>