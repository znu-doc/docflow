<?php
/* @var $this FunctionsController */
/* @var $model Functions */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDepartment'); ?>
		<?php echo $form->textField($model,'idDepartment'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DepartmentName'); ?>
		<?php echo $form->textField($model,'DepartmentName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FunctionDescription'); ?>
		<?php echo $form->textArea($model,'FunctionDescription',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->