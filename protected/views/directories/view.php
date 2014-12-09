<?php
$this->breadcrumbs=array(
	'Directories'=>array('index'),
	$model->idDirecrtory,
);

$this->menu=array(
	array('label'=>'Переглянути записи','url'=>array('index'),'icon'=>"icon-wrench"),
	array('label'=>'Додати запис','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Змінити запис','url'=>array('update','id'=>$model->idDirecrtory),'icon'=>" icon-pencil"),
	array('label'=>'Delete Directories','url'=>'#','icon'=>"icon-trash",'linkOptions'=>array('submit'=>array('delete','id'=>$model->idDirecrtory),'confirm'=>'Ви впевнені, що хочете видалити цей елемент?')),
	array('label'=>'Керування','url'=>array('admin')),
);
?>

<h1>Перегляд запису довідника #<?php echo $model->idDirecrtory; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
        'type'=>array('bordered', 'condensed','striped'),
	'attributes'=>array(
		'idDirecrtory',
		'DirectoryName',
		'DirectoryInfo',
		'DirectoryLink',
		'Visible',
		'Access',
	),
)); ?>
