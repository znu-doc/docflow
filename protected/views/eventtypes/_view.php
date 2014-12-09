<?php
/* @var $this EventtypesController */
/* @var $data Eventtypes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idEventType')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idEventType), array('view', 'id'=>$data->idEventType)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('EventTypeName')); ?>:</b>
	<?php echo CHtml::encode($data->EventTypeName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('EventTypeDescription')); ?>:</b>
	<?php echo CHtml::encode($data->EventTypeDescription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('EventTypeStyle')); ?>:</b>
	<?php echo CHtml::encode($data->EventTypeStyle); ?>
	<br />


</div>