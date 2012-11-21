<p><b><?php  echo Yii::t('waterrequest', 'Involved Service Areas');?>:</b></p>
<?php
	$cities = array();
	$margin = array();
	foreach($model->geometries() as $geom) {
		$cs = $geom->city_state;
		$sas = Geometry::Get_Service_Area_ByID($geom->id);
		if(count($sas))
			if (array_key_exists('desc_area',$sas[0]))
			{
				$sa = $sas[0]['desc_area'];
				$water_supply = DummySAOperativeMargin::model()->find('lower(area)=:area AND scenario IS NULL',array(':area'=>strtolower($sas[0]['area'])), array('limit'=>1));
				$margin[$sa] = $water_supply->margin;
			}
			else
			{
				$sa = 'unnamed';
				$margin[$sa] = 0;
			}
		else
		{
			$sa = 'unknown';
			$margin[$sa] = 0;
		}
		//$sa = $geom->service_area;
		if (array_key_exists($cs,$cities))
			if (array_key_exists($sa,$cities[$cs]))
				$cities[$cs][$sa]+=$geom->geom_water_demand;
			else
				$cities[$cs][$sa]=$geom->geom_water_demand;			
		else
			$cities[$cs]=array($sa => $geom->geom_water_demand);
	}
//	Yii::log(print_r($cities, true), CLogger::LEVEL_INFO);
//	Yii::log(print_r($margin, true), CLogger::LEVEL_INFO);
	echo '<div>';
	foreach($cities as $k=>$v) {
		if (Yii::app()->user->isWRU) {
			echo '<h6>'.
				CHtml::link(
							ucwords(strtolower($k)),
							CController::createUrl('waterRequests/index', array('municipality'=>ucwords(strtolower($k)))),
							array(
									'id'=>'only_this_municipality',
									'title'=>Yii::t('waterrequest', 'View only requests from Municipality ').ucwords(strtolower($k))
								)
					)
				.': </h6>';
		}else
			echo '<h6>'.ucwords(strtolower($k)).': </h6>';
		echo '<div>';
		foreach($v as $as=>$wd){
			echo ucwords(strtolower($as)).' - '.Yii::t('waterrequest', 'Requested').': '.Utilities::printWD($wd).' - '.Yii::t('waterrequest', 'Currently available').': '.Utilities::printWD($margin[$as]).'<br/>';
		}
		echo '</div>';
		
	}
	echo '</div>';
	
/*	
	foreach($cities as $k=>$v) {
		$ret = WaterRequestGeometries::feasibilityCheck($k,$v);
		$class = 'wd_ok';
		if (isset($ret['margin'])) {
			if ($ret['margin'] == 0)
				$class = 'wd_notice';
			else if ($ret['margin'] < 0)
				$class = 'wd_ko';
		}
		echo '<div>';
		if (isset($ret['maximum_water_supply'])) {
			echo '<h6 class="'.$class.'">'.ucwords(strtolower($k)).' - '.Yii::t('waterrequest', 'Requested').': '.Utilities::printWD($v).' / '.Yii::t('waterrequest', 'Currently available').': '.Utilities::printWD($ret['maximum_water_supply']).'</h6>';
			if ((isset($ret['scenari'])) && ($ret['scenari'] != null)) {
				foreach($ret['scenari'] as $scenario) {
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
		echo '</div>';
		echo '<br/>';
	}
*/
?>