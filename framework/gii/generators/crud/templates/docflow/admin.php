<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->menu=array(
  array('label'=>'Додати запис', 'url'=>array('create')),
);
?>
<h1>
Новий довідник <?php echo $this->getModelClass(); ?>
</h1>
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbGridView', array(
  'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    <?php
    $count = 0;
    foreach ($this->tableSchema->columns as $column) {
        if (++$count == 7){
          echo "    /*\n";
        }
    ?>
    array(
      'class' => 'editable.EditableColumn',
      'name' => '<?php echo $column->name; ?>',
      'editable' => array( //editable section
        'type'     => 'text',
        'url' => Yii::app()->CreateUrl(Yii::app()->controller->id.'/xupdate'),
        'placement' => 'right',
      ),
    ),
    <?php
    echo "\n";
    }
    if ($count >= 7){
      echo "    */\n";
    }
    ?>
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'template' => '{update} {delete}',
    ),
  ),
)); ?>
