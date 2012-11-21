<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wr_id')); ?>:</b>
	<?php echo CHtml::encode($data->wr_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('geom')); ?>:</b>
	<?php if($data->geom != null) {  ?>
		<a href="javascript:ctrlSelect.clickFeature(geoms.getFeatureByFid('water_request_geometries.<?php echo CHtml::encode($data->id); ?>'));" >Visualizza sulla mappa</a>
	<?php } else {?>
		Non ha coordinate
	<?php }?>
	<br />


</div>