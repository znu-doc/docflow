<?php
/* @var $this FunctionsController */
/* @var $model Functions */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'functions-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'DepartmentName'); ?>
		<?php echo $form->textField($model,'DepartmentName',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'DepartmentName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FunctionDescription'); ?>
		<?php echo $form->textArea($model,'FunctionDescription',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'FunctionDescription'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Створити' : 'Зберегти'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->