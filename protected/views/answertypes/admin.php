<?php
/* @var $this AnswertypesController */
/* @var $model Answertypes */

$this->breadcrumbs=array(
  'Answertypes'=>array('index'),
  'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);

?>

<h1>Типи відповідей на розсилки</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'answertypes-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
    'idAnswerType',
    'AnswerTypeName',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
