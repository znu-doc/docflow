<?php
$this->breadcrumbs=array(
  'Users'=>array('index'),
  'Manage',
);

$this->menu=array(
  //array('label'=>'Перелік користувачів','url'=>array('index'),'icon'=>"icon-list-alt"),
  array('label'=>'Створити користувача','url'=>array('create'),'icon'=>"icon-plus"),
  
);
?>

<h1>Управління користувачами</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
  'id'=>'user-grid',
        'type'=>'striped bordered condensed',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'htmlOptions' => array('style' => 'font-size: 9pt;'),
  'columns'=>array(
    'id',
    'username',
    'email',
    'info',
    array(
        'name' => 'departments.DepartmentName',
        'filter' => CHtml::activeTextField($model->searchDept, 'DepartmentName'),
        'value' => function($data){
          if ($data->id){
            $udept = Userdepartment::model()->findByAttributes(array('UserID'=>$data->id));
            echo (isset($udept->dept)) ? $udept->dept->DepartmentName : "[Не призначено]";
          }
        }
    ),
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
    ),
  ),
)); ?>
