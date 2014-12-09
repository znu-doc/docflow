<?php
/* @var $this DocflowstatusController */
/* @var $model Docflowstatus */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDocFlowStatus'); ?>
		<?php echo $form->textField($model,'idDocFlowStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DocFlowStatusName'); ?>
		<?php echo $form->textField($model,'DocFlowStatusName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DocFlowStatusDescription'); ?>
		<?php echo $form->textArea($model,'DocFlowStatusDescription',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->