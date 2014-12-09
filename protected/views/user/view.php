<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Перелік користувачів','url'=>array('index'),'icon'=>"icon-list-alt"),
	array('label'=>'Створити користувача','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Оновити користувача','url'=>array('update','id'=>$model->id),'icon'=>" icon-pencil"),
	array('label'=>'Видалити користувача','icon'=>"icon-trash",'url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Ви впевнені, що бажаєте видвлити запис?')),
	array('label'=>'Управління користівачами','url'=>array('admin'),'icon'=>"icon-user"),
);
?>

<h1>Інформація про користувача #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
        'type'=>'striped bordered condensed',
	'attributes'=>array(
		'id',
		'username',
		//'password',
		'email',
		'info',
	),
)); ?>
