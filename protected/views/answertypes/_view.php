<?php
/* @var $this AnswertypesController */
/* @var $data Answertypes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idAnswerType')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idAnswerType), array('view', 'id'=>$data->idAnswerType)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('AnswerTypeName')); ?>:</b>
	<?php echo CHtml::encode($data->AnswerTypeName); ?>
	<br />


</div>