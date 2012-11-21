<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->name)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('searchable')); ?>:</b>
	<?php echo CHtml::encode($data->searchable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_zone_name')); ?>:</b>
	<?php echo CHtml::encode($data->parent_zone_name); ?>
	<br />


</div>