<?php
$this->breadcrumbs=array(
	'Water Request Geometry Zone Properties',
);

$this->menu=array(
	array('label'=>'Create WaterRequestGeometryZoneProperties', 'url'=>array('create')),
	array('label'=>'Manage WaterRequestGeometryZoneProperties', 'url'=>array('admin')),
);
?>

<h1>Water Request Geometry Zone Properties</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
