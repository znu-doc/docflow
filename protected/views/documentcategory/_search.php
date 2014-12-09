<?php
/* @var $this DocumentcategoryController */
/* @var $model Documentcategory */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDocumentCategory'); ?>
		<?php echo $form->textField($model,'idDocumentCategory'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DocumentCategoryName'); ?>
		<?php echo $form->textField($model,'DocumentCategoryName',array('size'=>60,'maxlength'=>255)); ?>
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