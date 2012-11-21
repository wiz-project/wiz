<?php
$this->breadcrumbs=array(
	'Zones'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Zones', 'url'=>array('index')),
	array('label'=>'Create Zones', 'url'=>array('create')),
	array('label'=>'Update Zones', 'url'=>array('update', 'id'=>$model->name)),
	array('label'=>'Delete Zones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->name),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Zones', 'url'=>array('admin')),
);
?>

<h1>View Zones #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',
		'active',
		'searchable',
		'parent_zone_name',
	),
)); ?>
