<div class="tooltip <?php echo $css_class;?>" style="display: none">
	<div class="tooltip_content">
		<?php
			if (isset($scenari['maximum_water_supply'])) {
				echo '<div class="'.$css_class.'">'.Yii::t('waterrequest', 'Currently available').': '.Utilities::printWD($scenari['maximum_water_supply']).' </div>';
				if ((isset($scenari['scenari'])) && ($scenari['scenari'] != null)) {
					foreach($scenari['scenari'] as $scenario) {
						if (isset($scenario['maximum_water_supply'])) {
							$c = 'scenario_ok';
							if (isset($scenario['margin'])) {
								if ($scenario['margin'] == 0)
									$c = 'scenario_notice';
								else if ($scenario['margin'] < 0)
									$c = 'scenario_ko';
							}
							echo '<div class="scenario">'.$scenario['scenario'].': '.Utilities::printWD($scenario['maximum_water_supply']).'<span class="'.$c.'"></span></div>';
						}
					}
				}
			}
		?>
	</div><!-- tooltip_content -->
</div> <!-- tooltip -->