<?php
$this->breadcrumbs=array(
	'Water Demand Formulases'=>array('index'),
	$model->zone=>array('view','id'=>$model->zone),
	'Update',
);

$this->menu=array(
	array('label'=>'List WaterDemandFormulas', 'url'=>array('index')),
	array('label'=>'Create WaterDemandFormulas', 'url'=>array('create')),
	array('label'=>'View WaterDemandFormulas', 'url'=>array('view', 'id'=>$model->zone)),
	array('label'=>'Manage WaterDemandFormulas', 'url'=>array('admin')),
);
?>

<h1>Update WaterDemandFormulas <?php echo $model->zone; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>