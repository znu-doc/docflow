<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Перелік користувачів','url'=>array('index'),'icon'=>"icon-list-alt"),
	array('label'=>'Створити користувача','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Переглянути користувача','icon'=>'icon-info-sign','url'=>array('view','id'=>$model->id)),
	array('label'=>'Управління користівачами','url'=>array('admin'),'icon'=>"icon-user"),

        
);
?>

<h1>Оновлення користувача <?php echo $model->username; ?></h1>
<div class ='well form'>
<?php echo $this->renderPartial('_form',array('model'=>$model,'deptmodel'=>$deptmodel)); ?>
</div>