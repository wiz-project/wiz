<?php
$this->breadcrumbs=array(
	Yii::t('citizen','Informative Map'),
);

?>

<div class="span-25" style="margin-left:7px">
    <div style="padding:12px">
		<h1><?php echo Yii::t('citizen','Informative Map'); ?></h1>
	</div>
	<div id="map-frame">
		<?php echo $this->renderPartial('//maps/_info_map'); ?>	
	</div><!-- map-frame -->
</div>