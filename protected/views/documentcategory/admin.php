<?php
/* @var $this DocumentcategoryController */
/* @var $model Documentcategory */

$this->breadcrumbs=array(
	'Documentcategories'=>array('index'),
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
$.fn.yiiGridView.update('documentcategory-grid', {
data: $(this).serialize()
});
return false;
});
");*/
?>

<h1>Довідник категорій документів</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'documentcategory-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'idDocumentCategory',
		'DocumentCategoryName',
		'info',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
