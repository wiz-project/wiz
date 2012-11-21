<div class="tooltip info_geom_zone" style="display: none">
	<div class="tooltip_content">
		<div class="geom_name">
			&nbsp;
			<?php echo CHtml::link(
					Yii::t('waterrequest', 'Close'),
					'javascript:void(0);',
					array('id'=>'close_info_geom_zone','alt'=>'close info geom zone','title'=>'close info geom zone','class'=>'close_info_geom_zone', 'onclick'=>'$(this).parents(".tooltip").remove();')
					);
			?>
		</div>
		<div style="padding: 6px;">
			
			<!-- parametro utilizzato per il calcolo degli AE -->
			<?php
				$info_wd = false; 
				foreach ($model->properties as $property) {
					if ( $property->use4ae === true ): ?>
						<b> <?php echo CHtml::encode(ucwords($property->description));?>:</b>
						<?php echo CHtml::encode($property->value); ?>
						<br/>
						&#8659;
						<br/>
						<b> <?php echo CHtml::encode($model->getAttributeLabel('pe'));?>:</b>
						<?php echo CHtml::encode(Math::pe_round($model->pe)); ?>
						<br/>
						&#8659;
						<br/>
						<b> <?php echo CHtml::encode($model->getAttributeLabel('water_demand')); ?>:</b>
						<?php echo CHtml::encode(Utilities::printWD($model->water_demand)); ?>				
					<?php 
						$info_wd = true;
						break;
				 	endif;
				}
				
				foreach ($model->properties as $property) {
					if ( $property->use4ae === true )
						continue;
					else { 
						if ($info_wd === true) {
							echo '<br/><br/>';
							echo '<b>Other info:</b><br/>';
							$info_wd = false;
						}
						?>
						
						&nbsp;&nbsp;<b> <?php echo CHtml::encode(ucwords($property->description));?>:</b>
						<?php echo CHtml::encode($property->value);?>
						<br/>
					<?php
					}
				}
			
			
			?>
		</div>
	</div><!-- tooltip_content -->
</div> <!-- tooltip -->