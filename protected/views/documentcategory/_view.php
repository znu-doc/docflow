<?php
/* @var $this DocumentcategoryController */
/* @var $data Documentcategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocumentCategory')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocumentCategory), array('view', 'id'=>$data->idDocumentCategory)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DocumentCategoryName')); ?>:</b>
	<?php echo CHtml::encode($data->DocumentCategoryName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('info')); ?>:</b>
	<?php echo CHtml::encode($data->info); ?>
	<br />


</div>