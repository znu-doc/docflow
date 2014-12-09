<?php
$this->breadcrumbs=array(
	'Directories'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'Перелі Directories','url'=>array('index')),
	array('label'=>'Створити','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('directories-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управління Довідниками</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'directories-grid',
	'dataProvider'=>$model->search(),
        'type'=>'striped bordered condensed',
	'filter'=>$model,
	'columns'=>array(
		//'idDirecrtory',
		'DirectoryName',
		'DirectoryInfo',
		//'DirectoryLink',
		
		array( 'name'=>'Visible', 'filter'=>array(1=>"Так", 0=>"Ні"),'value'=>'($data->Visible) ? "Так":"Ні"', 'htmlOptions'=>array("width"=>"100")),
              ///Access
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
