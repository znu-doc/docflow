<?php
/* @var $this EventkindsController */
/* @var $model Eventkinds */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'eventkinds-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Поля із зірочкою <span class="required">*</span> обов'язкові для заповнення.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row-fluid">
    <?php echo $form->labelEx($model,'EventKindName',array('class'=>'span12')); ?>
    <?php echo $form->textField($model,'EventKindName',array('size'=>60,'maxlength'=>128, 'class'=>'span12')); ?>
    <?php echo $form->error($model,'EventKindName',array('class'=>'span12')); ?>
  </div>
  <hr/>
  <div class="row-fluid">
    <?php echo $form->labelEx($model,'EventKindDescription',array('class'=>'span12')); ?>
    <?php echo $form->textArea($model,'EventKindDescription',array('cols'=>50, 'class'=>'span12')); ?>
    <?php echo $form->error($model,'EventKindDescription',array('class'=>'span12')); ?>
  </div>
  <!--hr/>
  <div class="row-fluid">
    <?php echo $form->labelEx($model,'EventKindStyle',array('class'=>'span12')); ?>
    <?php echo $form->textField($model,'EventKindStyle',array('size'=>60,'maxlength'=>255, 'class'=>'span12')); ?>
    <?php echo $form->error($model,'EventKindStyle',array('class'=>'span12')); ?>
  </div>
  <hr/ -->
  <hr/>
  <div class="row-fluid" style="text-align: center;">
    <?php 
        $this->widget(
            "bootstrap.widgets.TbButton", array(
              'buttonType' => 'submit',
              'type' => 'success',
              "size" => "large",
              'loadingText'=>'Зачекайте...',
              'htmlOptions' => array(
                  'id' => 'submit_eventkinds_button',
                  'onclick' => 'DOC.ButtonDelay("submit_eventkinds_button");',
              ),
              'label' => $model->isNewRecord ? 'Створити':'Оновити',
            )
        );
    ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->