<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idDirecrtory')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDirecrtory),array('view','id'=>$data->idDirecrtory)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DirectoryName')); ?>:</b>
	<?php echo CHtml::encode($data->DirectoryName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DirectoryInfo')); ?>:</b>
	<?php echo CHtml::encode($data->DirectoryInfo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DirectoryLink')); ?>:</b>
	<?php echo CHtml::encode($data->DirectoryLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Visible')); ?>:</b>
	<?php echo CHtml::encode($data->Visible); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Access')); ?>:</b>
	<?php echo CHtml::encode($data->Access); ?>
	<br />


</div>