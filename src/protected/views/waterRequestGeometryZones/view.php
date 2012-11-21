<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WaterRequestGeometryZones', 'url'=>array('index')),
	array('label'=>'Create WaterRequestGeometryZones', 'url'=>array('create')),
	array('label'=>'Update WaterRequestGeometryZones', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WaterRequestGeometryZones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterRequestGeometryZones', 'url'=>array('admin')),
);
?>
<div class="span-13" id="map-sidebar">
	<div id="content">

<h1>View WaterRequestGeometryZones #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'wr_geometry_id',
		'zone',
		'pe',
		'water_demand',
	),
)); ?>

	</div><!-- content -->
</div>
<div class="span-25">
		<div id="map-frame">

<?php echo $this->renderPartial('//maps/_map', array('model'=>$model,'edit'=>false)); ?>		
		</div><!-- map-frame -->
</div>
