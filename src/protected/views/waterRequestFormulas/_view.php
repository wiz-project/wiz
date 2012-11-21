<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone')); ?>:</b>
	<?php echo CHtml::encode($data->zone); ?>
	<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/edit.png', 'Edit'), array('update', 'id'=>$data->zone)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('formula')); ?>:</b>
	<?php echo CHtml::encode($data->formula); ?>
	<br />

</div>