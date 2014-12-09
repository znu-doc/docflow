<?php
/* @var $this PropositionbookController */
/* @var $model Propositionbook */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idProposition'); ?>
		<?php echo $form->textField($model,'idProposition'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Proposition'); ?>
		<?php echo $form->textArea($model,'Proposition',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'UserID'); ?>
		<?php echo $form->textField($model,'UserID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->