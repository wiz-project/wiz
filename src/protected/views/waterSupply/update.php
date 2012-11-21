<?php
$this->breadcrumbs=array(
	'Water Supplys'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WaterSupply', 'url'=>array('index')),
	array('label'=>'Create WaterSupply', 'url'=>array('create')),
	array('label'=>'View WaterSupply', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WaterSupply', 'url'=>array('admin')),
);
?>

<h1>Update WaterSupply <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>