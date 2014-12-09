<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'docflowtypes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'DocFlowTypeName'); ?>
		<?php echo $form->textField($model,'DocFlowTypeName',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'DocFlowTypeName'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Створити' : 'Зберегти'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->