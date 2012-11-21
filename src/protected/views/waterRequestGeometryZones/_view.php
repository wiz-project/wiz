<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wr_geometry_id')); ?>:</b>
	<?php echo CHtml::encode($data->wr_geometry_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone')); ?>:</b>
	<?php echo CHtml::encode($data->zone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pe')); ?>:</b>
	<?php echo CHtml::encode($data->pe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('water_demand')); ?>:</b>
	<?php echo CHtml::encode($data->water_demand); ?>
	<br />


</div>