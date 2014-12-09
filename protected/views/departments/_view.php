<?php
/* @var $this FunctionsController */
/* @var $data Functions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDepartment')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDepartment), array('view', 'id'=>$data->idDepartment)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DepartmentName')); ?>:</b>
	<?php echo CHtml::encode($data->DepartmentName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FunctionDescription')); ?>:</b>
	<?php echo CHtml::encode($data->FunctionDescription); ?>
	<br />



</div>