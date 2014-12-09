<?php
/* @var $this DocumenttypeController */
/* @var $data Documenttype */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocumentType')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocumentType), array('view', 'id'=>$data->idDocumentType)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DocumentTypeName')); ?>:</b>
	<?php echo CHtml::encode($data->DocumentTypeName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('info')); ?>:</b>
	<?php echo CHtml::encode($data->info); ?>
	<br />


</div>