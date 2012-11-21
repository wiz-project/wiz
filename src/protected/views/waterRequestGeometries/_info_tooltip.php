<div class="tooltip info_geom" style="display: none">
	<div class="tooltip_content">
		<div class="geom_name">
			&nbsp;
			<?php echo CHtml::link(
					Yii::t('waterrequest', 'Close'),
					'javascript:void(0);',
					array('id'=>'close_info_geom','alt'=>'close info geom','title'=>'close info geom','class'=>'close_info_geom', 'onclick'=>'$(this).parents(".tooltip").hide();')
					);
			?>
		</div>
		<div style="padding: 6px;">
			<!--
			<b><?php echo CHtml::encode($model->getAttributeLabel('geom_water_demand')); ?>:</b>
			<?php echo CHtml::encode(Utilities::printWD($model->geom_water_demand)); ?>
			<br />
			-->
			
			<b><?php echo CHtml::encode($model->getAttributeLabel('centroid')); ?>:</b>
			<?php echo CHtml::encode(ucwords(strtolower($model->city_state))); ?>
			<br />
			
			<b><?php echo CHtml::encode($model->getAttributeLabel('elevation')); ?>:</b>
			<?php echo CHtml::encode($model->elevation); ?> m
			<br />
			
			<b><?php echo CHtml::encode($model->getAttributeLabel('sup')); ?>:</b>
			<?php echo CHtml::encode(round($model->sup,2)); ?> m&sup2;
			<br />
			
			<?php
				
			$ret = Yii::app()->params['geoserver']['protocol'].
			Yii::app()->params['geoserver']['ip'].
			':'.
			Yii::app()->params['geoserver']['port'].
			Yii::app()->params['geoserver']['path'].
			'/'.
			Yii::app()->params['geoserver']['workspace'].
			'/ows'.			
			'?service=WFS'.
			//'&version='.Yii::app()->params['geoserver']['version'].  // version dipende dal service
			'&version=1.0.0'.
			'&request=GetFeature'.
			'&typeName='.Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['layer_geom'].
			'&maxFeatures=50'.
			'&outputFormat=SHAPE-ZIP'.
			'&format_options=filename:geom_'.$model->id.'.zip'.
			'&viewparams=id:'.$model->id;				
			echo CHtml::link(Yii::t('waterrequest', 'Download shape for this geometry'), $ret); ?>
		</div>
	</div><!-- tooltip_content -->
</div> <!-- tooltip -->