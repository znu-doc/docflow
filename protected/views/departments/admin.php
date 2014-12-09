<?php
/* @var $this FunctionsController */
/* @var $model Functions */

$this->breadcrumbs=array(
  'Departments'=>array('index'),
  'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('functions-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Довідник підрозділів для документообігу</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'functions-grid',
'dataProvider'=>$model->search(),
'htmlOptions' => array('style' => 'font-size: 9pt;'),
'filter'=>$model,
'columns'=>array(
    'idDepartment',
    'DepartmentName',
    'FunctionDescription',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
