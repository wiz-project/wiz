<?php
class Utilities extends CApplicationComponent
{

	public static function printWD($wd)
	{
		if ($wd)
			return Math::wd_round($wd).' '.Yii::app()->params['water_demand_unit'];
		return '0 '.Yii::app()->params['water_demand_unit'];
	}
}