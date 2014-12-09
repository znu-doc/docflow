<?php
$this->breadcrumbs=array(
	'Directories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Переглянути записи','url'=>array('index'),'icon'=>"icon-list-alt"),
	array('label'=>'Керування','url'=>array('admin'),'icon'=>"icon-wrench"),
);
?>

<h1>Створення запису довідника "Довідники"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
