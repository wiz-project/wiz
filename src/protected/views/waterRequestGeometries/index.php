<?php
$this->breadcrumbs=array(
	'Water Request Geometries',
);

$this->menu=array(
	array('label'=>'Create WaterRequestGeometries', 'url'=>array('create')),
	array('label'=>'Manage WaterRequestGeometries', 'url'=>array('admin')),
);
?>

<h1>Water Request Geometries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
