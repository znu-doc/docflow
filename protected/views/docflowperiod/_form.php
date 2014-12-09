<?php
/* @var $this DocflowperiodController */
/* @var $model Docflowperiod */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'docflowperiod-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PeriodName'); ?>
		<?php echo $form->textField($model,'PeriodName',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'PeriodName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PeriodDescription'); ?>
		<?php echo $form->textField($model,'PeriodDescription',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'PeriodDescription'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Створити' : 'Зберегти'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->