<div id="parent_percent">
<?php
		$usage = $model->getParentWDUsage();
		if($model->parent_wr->total_water_demand<=0)
			$percentage = 100;
		else 
			$percentage = Math::wd_percentage_round($usage/$model->parent_wr->total_water_demand*100);
		$amount = Math::wd_round($usage).' '.Yii::app()->params['water_demand_unit'].' / '.Math::wd_percentage_round($model->parent_wr->total_water_demand).' '.Yii::app()->params['water_demand_unit'].' ( '.$percentage.'%)';
		if ($percentage < 35)
			$pb_class = 'pb_ok';
		else if ($percentage < 70)
			$pb_class = 'pb_notice';
		else
			$pb_class = 'pb_ko';
			$this->widget('zii.widgets.jui.CJuiProgressBar', array(
												'id'=>'progress',
												'value'=>$percentage, //value in percent
												/*
												'options'=>array(
      													'change'=>'js:function(event, ui) {
      														alert(ui);
      													}',
      													'barImage' => 'images/progress_bar_ok.png',
      												),*/
												'htmlOptions'=>array(
												'style'=>'height:22px;width:300px;float:left; margin-right: 10px;',
												'class'=>$pb_class,
												),
						));
		echo '<div id="amount" style="padding:3px;">'.$amount.'</div>';

?>
</div>