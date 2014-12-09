<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
  'id'=>'".$this->class2id($this->modelClass)."-form',
  'enableAjaxValidation'=>false,
)); ?>\n"; ?>

  <p class="note">Поля із зірочкою <span class="required">*</span> обов'язкові для заповнення.</p>

  <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column){
  if($column->autoIncrement)
    continue;
?>
  <div class="row-fluid">
    <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
    <?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
    <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
  </div>

<?php
}
?>
  <div class="row-fluid" style="text-align: center;">
    <?php echo "<?php 
        \$this->widget(
            \"bootstrap.widgets.TbButton\", array(
              'buttonType' => 'submit',
              'type' => 'success',
              \"size\" => \"large\",
              'loadingText'=>'Зачекайте...',
              'htmlOptions' => array(
                  'id' => 'submit_".$this->class2id($this->modelClass)."_button',
                  'onclick' => 'DOC.ButtonDelay(\"submit_".$this->class2id($this->modelClass)."_button\");',
              ),
              'label' => \$model->isNewRecord ? 'Створити':'Оновити',
            )
        );
    ?>\n"; ?>
  </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->