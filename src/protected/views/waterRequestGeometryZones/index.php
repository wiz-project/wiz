<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zones',
);

$this->menu=array(
	array('label'=>'Create WaterRequestGeometryZones', 'url'=>array('create')),
	array('label'=>'Manage WaterRequestGeometryZones', 'url'=>array('admin')),
);
?>

<h1>Water Request Geometry Zones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
