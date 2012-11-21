<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_parameters.name')); ?>:</b>
	<?php echo CHtml::encode($data->request_parameters->name); ?>
	<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/edit.png', 'Edit'), array('update', 'id'=>$data->request_parameters->name,'zone'=>$data->zone)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_parameters.description')); ?>:</b>
	<?php echo CHtml::encode($data->request_parameters->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_parameters.measurement_unit')); ?>:</b>
	<?php echo CHtml::encode($data->request_parameters->measurement_unit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone')); ?>:</b>
	<?php echo CHtml::encode($data->zone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value ? $data->value : 'Not set'); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('required')); ?>:</b>
	<?php echo CHtml::encode($data->required); ?>
	<br />

</div>