<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'idDirecrtory',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'DirectoryName',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'DirectoryInfo',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'DirectoryLink',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'Visible',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'Access',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
