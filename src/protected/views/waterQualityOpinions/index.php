<?php
	$this->breadcrumbs=array(
		Yii::t('citizen','Water Service Evaluations')
	);
?>

<a class="div_link" href="<?php echo CController::createUrl("waterQualityOpinions/view"); ?>">
<div id="quality_view_box">
	<h3><?php echo Yii::t('citizen','Display Evaluations Posted'); ?></h3>
	<p><?php echo Yii::t('citizen','Displays the evaluations expressed by citizens about the quality of water service.'); ?></p>
</div>
</a>

<br/>

<a class="div_link" href="<?php echo CController::createUrl("waterQualityOpinions/create"); ?>">
<div id="quality_create_box">
	<h3><?php echo Yii::t('citizen','Give An Evaluation'); ?></h3>
	<p><?php echo Yii::t('citizen','Gives an evaluation about the quality of water service.'); ?></p>
</div>
</a>