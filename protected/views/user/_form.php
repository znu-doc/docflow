<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
    
)); 
$form = new TbActiveForm();
?>

	<p class="help-block">Поля з <span class="required">*</span> є обов'язковими для заповнення.</p>

	

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>255)); ?>
          
	<?php echo $form->textAreaRow($model,'info',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
  
    <?php               
    echo $form->dropDownListRow($deptmodel,'idDepartment',Departments::model()->DropDown(),
              array('class'=>'span8'));
    ?>

        
        <?php echo $form->errorSummary($model); ?>
	<hr/>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Створити' : 'Зберегти',
		)); ?>
	

<?php $this->endWidget(); ?>
