<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Створити користувача','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Управління користувачами','url'=>array('admin'),'icon'=>"icon-user"),
);
?>

<h1>Users</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
