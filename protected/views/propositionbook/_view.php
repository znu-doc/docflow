<?php
/* @var $this PropositionbookController */
/* @var $data Propositionbook */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idProposition')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idProposition), array('view', 'id'=>$data->idProposition)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Proposition')); ?>:</b>
	<?php echo CHtml::encode($data->Proposition); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('UserID')); ?>:</b>
	<?php echo CHtml::encode(User::model()->findByPk($data->UserID)->username); ?>
	<br />


</div>