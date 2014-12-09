<?php
/* @var $this DocflowperiodController */
/* @var $data Docflowperiod */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocFlowPeriod')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocFlowPeriod), array('view', 'id'=>$data->idDocFlowPeriod)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PeriodName')); ?>:</b>
	<?php echo CHtml::encode($data->PeriodName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PeriodDescription')); ?>:</b>
	<?php echo CHtml::encode($data->PeriodDescription); ?>
	<br />


</div>