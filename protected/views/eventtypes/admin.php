<?php
/* @var $this EventtypesController */
/* @var $model Eventtypes */

$this->menu=array(
  array('label'=>'Додати запис', 'url'=>array('create')),
);
?>
<h1>
Довідник рівнів заходів</h1>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
  'id'=>'eventtypes-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
        array(
      'class' => 'editable.EditableColumn',
      'name' => 'idEventType',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'EventTypeName',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'EventTypeDescription',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    
    /*
    array(
      'class' => 'editable.EditableColumn',
      'name' => 'EventTypeStyle',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    */
    
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'template' => '{update} {delete}',
    ),
  ),
)); ?>
