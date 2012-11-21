<?php
$this->breadcrumbs=array(
	'Zones'=>array('index'),
	$model->name=>array('view','id'=>$model->name),
	'Update',
);

$this->menu=array(
	array('label'=>'List Zones', 'url'=>array('index')),
	array('label'=>'Create Zones', 'url'=>array('create')),
	array('label'=>'View Zones', 'url'=>array('view', 'id'=>$model->name)),
	array('label'=>'Manage Zones', 'url'=>array('admin')),
);
?>

<h1>Update Zones <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>