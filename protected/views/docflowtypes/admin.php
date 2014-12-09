<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */

$this->breadcrumbs=array(
	'Docflowtypes'=>array('index'),
	'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);
/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('docflowtypes-grid', {
data: $(this).serialize()
});
return false;
});
");*/
?>

<h1>Довідник типів документообігу</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'docflowtypes-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'idDocFlowType',
		'DocFlowTypeName',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
