<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zone Properties'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WaterRequestGeometryZoneProperties', 'url'=>array('index')),
	array('label'=>'Create WaterRequestGeometryZoneProperties', 'url'=>array('create')),
	array('label'=>'Update WaterRequestGeometryZoneProperties', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WaterRequestGeometryZoneProperties', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterRequestGeometryZoneProperties', 'url'=>array('admin')),
);
?>

<h1>View WaterRequestGeometryZoneProperties #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'geometry_zone',
		'parameter',
		'value',
	),
)); ?>
