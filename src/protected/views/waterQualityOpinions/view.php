<?php
$this->breadcrumbs=array(
	Yii::t('citizen','Water Service Evaluations')=>array('index'),
	Yii::t('citizen','Display Evaluations Posted'),
);

?>

<div class="span-25" style="margin-left:7px">
    <div style="padding:12px">
		<h1><?php echo Yii::t('citizen','Display Evaluations Posted'); ?></h1>
	</div>
	<div id="map-frame">
		<?php echo $this->renderPartial('//maps/_quality_map', array('qualities_property'=>$qualities_property, 'faults_property'=>$faults_property)); ?>	
	</div><!-- map-frame -->
</div>