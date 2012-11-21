<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WaterRequestParameters', 'url'=>array('index')),
	array('label'=>'Create WaterRequestParameters', 'url'=>array('create')),
	array('label'=>'Update WaterRequestParameters', 'url'=>array('update', 'id'=>$model->name)),
	array('label'=>'Delete WaterRequestParameters', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->name),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterRequestParameters', 'url'=>array('admin')),
);
?>

<h1>View WaterRequestParameters #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',
		'measurement_unit',
	),
)); ?>
