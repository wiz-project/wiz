<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zone Properties'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WaterRequestGeometryZoneProperties', 'url'=>array('index')),
	array('label'=>'Create WaterRequestGeometryZoneProperties', 'url'=>array('create')),
	array('label'=>'View WaterRequestGeometryZoneProperties', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WaterRequestGeometryZoneProperties', 'url'=>array('admin')),
);
?>

<h1>Update WaterRequestGeometryZoneProperties <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'zone_type'=>$zone_type)); ?>