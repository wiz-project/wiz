<?php
$this->breadcrumbs=array(
	'Water Demand Formulases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WaterDemandFormulas', 'url'=>array('index')),
	array('label'=>'Manage WaterDemandFormulas', 'url'=>array('admin')),
);
?>
<div class="span-13" id="map-sidebar">
	<div id="content">

<h1>Create WaterDemandFormulas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- content -->
</div>
<div class="span-25">
		<div id="map-frame">

<?php echo $this->renderPartial('//maps/_map', array('model'=>$model)); ?>
		
		</div><!-- map-frame -->
</div>
		