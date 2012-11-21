<?php
$this->breadcrumbs=array(
	'Water Demand Formulases'=>array('index'),
	$model->zone,
);

$this->menu=array(
	array('label'=>'List WaterDemandFormulas', 'url'=>array('index')),
	array('label'=>'Create WaterDemandFormulas', 'url'=>array('create')),
	array('label'=>'Update WaterDemandFormulas', 'url'=>array('update', 'id'=>$model->zone)),
	array('label'=>'Delete WaterDemandFormulas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->zone),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterDemandFormulas', 'url'=>array('admin')),
);
?>

<h1>View WaterDemandFormulas #<?php echo $model->zone; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'zone',
		'formula',
	),
)); ?>
