<?php
$this->breadcrumbs=array(
	'Directories'=>array('index'),
	$model->idDirecrtory=>array('view','id'=>$model->idDirecrtory),
	'Update',
);

$this->menu=array(
	array('label'=>'Переглянути записи','url'=>array('index'),'icon'=>"icon-wrench"),
	array('label'=>'Додати запис','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Переглянути запис','url'=>array('view','id'=>$model->idDirecrtory),'icon'=>"icon-eye-open"),
	array('label'=>'Керування','url'=>array('admin'),'icon'=>"icon-wrench"),
);
?>

<h1>Змінити запис довідника<?php echo $model->idDirecrtory; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
