<?php
	$ret = array();
	foreach ($model->zones() as $zone) {
		$z = array('wr_id'=>$model->wr->id,'geom_id'=>$model->id,'id'=>$zone->id,'zone'=>$zone->zoneDescription,'pe'=>Math::pe_round($zone->pe),'wd'=>Math::wd_round($zone->water_demand). ' '.Yii::app()->params['water_demand_unit']);
		array_push($ret,$z);
	}
	echo htmlspecialchars(json_encode($ret), ENT_NOQUOTES);
?>