<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_state')); ?>:</b>
	<?php echo CHtml::encode($data->city_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('daily_maximum_water_supply')); ?>:</b>
	<?php echo CHtml::encode($data->daily_maximum_water_supply); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yearly_average_water_supply')); ?>:</b>
	<?php echo CHtml::encode($data->yearly_average_water_supply); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('scenario')); ?>:</b>
	<?php echo CHtml::encode($data->scenario ? $data->scenario : 'Not set'); ?>
	<br />

</div>