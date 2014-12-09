<?php
/* @var $this DocumenttypeController */
/* @var $model Documenttype */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDocumentType'); ?>
		<?php echo $form->textField($model,'idDocumentType'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DocumentTypeName'); ?>
		<?php echo $form->textField($model,'DocumentTypeName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'info'); ?>
		<?php echo $form->textField($model,'info',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->