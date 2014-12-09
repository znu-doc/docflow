<?php
/* @var $this EventtypesController */
/* @var $model Eventtypes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'eventtypes-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Поля із зірочкою <span class="required">*</span> обов'язкові для заповнення.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row-fluid">
    <?php echo $form->labelEx($model,'EventTypeName'); ?>
    <?php echo $form->textField($model,'EventTypeName',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'EventTypeName'); ?>
  </div>

  <div class="row-fluid">
    <?php echo $form->labelEx($model,'EventTypeDescription'); ?>
    <?php echo $form->textArea($model,'EventTypeDescription',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'EventTypeDescription'); ?>
  </div>

  <!--div class="row-fluid">
    <?php echo $form->labelEx($model,'EventTypeStyle'); ?>
    <?php echo $form->textField($model,'EventTypeStyle',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'EventTypeStyle'); ?>
  </div-->

  <div class="row-fluid" style="text-align: center;">
    <?php 
        $this->widget(
            "bootstrap.widgets.TbButton", array(
              'buttonType' => 'submit',
              'type' => 'success',
              "size" => "large",
              'loadingText'=>'Зачекайте...',
              'htmlOptions' => array(
                  'id' => 'submit_eventtypes_button',
                  'onclick' => 'DOC.ButtonDelay("submit_eventtypes_button");',
              ),
              'label' => $model->isNewRecord ? 'Створити':'Оновити',
            )
        );
    ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->