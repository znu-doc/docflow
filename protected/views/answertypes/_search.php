<?php
/* @var $this AnswertypesController */
/* @var $model Answertypes */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idAnswerType'); ?>
		<?php echo $form->textField($model,'idAnswerType'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AnswerTypeName'); ?>
		<?php echo $form->textField($model,'AnswerTypeName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->