<?php
/* @var $this DocflowstatusController */
/* @var $data Docflowstatus */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocFlowStatus')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocFlowStatus), array('view', 'id'=>$data->idDocFlowStatus)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DocFlowStatusName')); ?>:</b>
	<?php echo CHtml::encode($data->DocFlowStatusName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DocFlowStatusDescription')); ?>:</b>
	<?php echo CHtml::encode($data->DocFlowStatusDescription); ?>
	<br />


</div>