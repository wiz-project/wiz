<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zone Properties'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WaterRequestGeometryZoneProperties', 'url'=>array('index')),
	array('label'=>'Manage WaterRequestGeometryZoneProperties', 'url'=>array('admin')),
);
?>

<h1>Create WaterRequestGeometryZoneProperties</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'zone_type'=>$zone_type)); ?>