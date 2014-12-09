<?php
/*
 @var $form TbActiveForm
*/
?>
<div class="form well ">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'directories-form',
	'enableAjaxValidation'=>false,
)); 
//$form = new TbActiveForm();
?>
<p class="note">Поля, відмічені <span class="required">*</span> обов'язкові для заповнення!</p>
<?php echo $form->errorSummary($model); ?>
<?php //------------------------------------------------------------------------------------------------------------------------------------//?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'DirectoryName',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'DirectoryInfo',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'DirectoryLink',array('class'=>'span5','maxlength'=>255)); ?>
        <?php echo $form->labelEx($model,'Visible'); ?>

        <div class="switch" data-on-label="Так" data-off-label="Ні">
        <?php echo $form->checkBox($model,'Visible'); ?>
        </div> 

	<?php //echo $form->checkBoxRow($model,'Visible',array('class'=>'span1')); ?>

	<?php //echo $form->checkBoxListRow($model,'Access', UAccess::checkList(), array('class'=>'span1')); ?>

<?php //------------------------------------------------------------------------------------------------------------------------------------//?>
<hr>    
<div class="row-fluid">
    <?php $this->widget("bootstrap.widgets.TbButton", array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        "size"=>"null",
			'label'=>$model->isNewRecord ? 'Створити' : 'Зберегти',
                        )); 
    ?>
</div>
<?php $this->endWidget(); ?>
</div>
