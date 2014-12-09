<?php
/* @var $this DocflowtypesController */
/* @var $model Docflowtypes */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDocFlowType'); ?>
		<?php echo $form->textField($model,'idDocFlowType'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DocFlowTypeName'); ?>
		<?php echo $form->textField($model,'DocFlowTypeName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->