<div class="view water_request <?php echo CHtml::encode(strtolower($data->statusHR)); if($gridview) echo ' square';?>">

	<div class="title">
		<?php echo CHtml::link($data->project, array('view', 'id'=>$data->id)); ?>
	</div>
	
	<?php echo $data->statusIconHistory; //echo count($data->geometries()); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('phase')); ?>:</b>
	<?php echo CHtml::encode($data->phaseHR); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->timestampHR); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->user->getAttributeLabel('first_name')); ?>:</b>
	<?php echo CHtml::encode($data->user->first_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->user->getAttributeLabel('last_name')); ?>:</b>
	<?php echo CHtml::encode($data->user->last_name); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->user->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->user->title); ?>
	<br />
			
	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('total_water_demand')); ?>:</b>
	<?php echo CHtml::encode(Math::wd_round($data->total_water_demand).' '.Yii::app()->params['water_demand_unit']); ?>
	<br />
	
	<?php 
		if (Yii::app()->user->checkAccess('updateWaterDemandInWaterRequest')): ?>
			<b><?php echo CHtml::encode($data->getAttributeLabel('effective_water_demand')); ?>:</b>
			<?php echo CHtml::encode(Math::wd_round($data->effective_water_demand).' '.Yii::app()->params['water_demand_unit']); ?>
			<br />
	<?php
		endif;
	?>
	
	
	<?php if (($data->phase==1) AND ($data->status==WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS) AND (Yii::app()->user->isPlanner)))
		echo CHtml::link(Yii::t('waterrequest', 'Move on Executive Phase'), array('waterRequests/create', 'phase'=>2,'parent'=>$data->id));
	?>

	<div class="water_request_operation">
		<?php
			if (Yii::app()->user->checkAccess('pdfWaterRequest', array('waterRequest'=>$data))) {
				$img = CHtml::image('images/document_pdf.png');
				echo CHtml::link($img, array('pdf', 'id'=>$data->id), array('id'=>'pdf-link', 'title'=>Yii::t('waterrequest', 'Generate PDF')));
			}
			
			if (Yii::app()->user->checkAccess('epanetWaterRequest', array('waterRequest'=>$data))) {
				$img = CHtml::image('images/document_epanet.png');
				echo CHtml::link($img, array('epanet', 'id'=>$data->id), array('id'=>'epanet-link', 'title'=>Yii::t('waterrequest', 'Generate Epanet file')));
			}
		?>
	</div>
	
</div>