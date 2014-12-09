<?php
/* @var $this DocflowtypesController */
/* @var $data Docflowtypes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocFlowType')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocFlowType), array('view', 'id'=>$data->idDocFlowType)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DocFlowTypeName')); ?>:</b>
	<?php echo CHtml::encode($data->DocFlowTypeName); ?>
	<br />


</div>