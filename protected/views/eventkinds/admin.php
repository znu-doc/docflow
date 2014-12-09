<?php
/* @var $this EventkindsController */
/* @var $model Eventkinds */

$this->menu=array(
  array('label'=>'Додати запис', 'url'=>array('create')),
);
?>
<h1>
Довідник видів заходів</h1>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
  'id'=>'eventkinds-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'idEventKind',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
      'htmlOptions' => array(
        'class' => 'span1'
      ),
    ),
    
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'EventKindName',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'EventKindDescription',
      'editable' => array( //editable section
        'type'     => 'textarea',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'template' => '{update} {delete}',
    ),
  ),
)); ?>
