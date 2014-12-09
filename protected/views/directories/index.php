<?php
$this->breadcrumbs=array(
	'Directories',
);

$this->menu=array(
	array('label'=>'Додати запис','url'=>array('create'),'icon'=>"icon-plus"),
	array('label'=>'Керування','url'=>array('admin'),'icon'=>"icon-wrench"),
);
?>

<h1>Довідники</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
