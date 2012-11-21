<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('geometry_zone')); ?>:</b>
	<?php echo CHtml::encode($data->geometry_zone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parameter')); ?>:</b>
	<?php echo CHtml::encode($data->parameter); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />


</div>