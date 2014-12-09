<?php
/* @var $this DocflowperiodController */
/* @var $model Docflowperiod */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'idDocFlowPeriod'); ?>
		<?php echo $form->textField($model,'idDocFlowPeriod'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PeriodName'); ?>
		<?php echo $form->textField($model,'PeriodName',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PeriodDescription'); ?>
		<?php echo $form->textField($model,'PeriodDescription',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Пошук'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->