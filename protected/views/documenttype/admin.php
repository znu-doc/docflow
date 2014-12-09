<?php
/* @var $this DocumenttypeController */
/* @var $model Documenttype */

$this->breadcrumbs=array(
	'Documenttypes'=>array('index'),
	'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);

/*Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('documenttype-grid', {
data: $(this).serialize()
});
return false;
});
");*/
?>

<h1>Довідник типів документів</h1>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'documenttype-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'idDocumentType',
		'DocumentTypeName',
		'info',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
