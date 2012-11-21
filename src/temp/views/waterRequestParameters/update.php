<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	$model->name=>array('view','id'=>$model->name),
	'Update',
);

$this->menu=array(
	array('label'=>'List WaterRequestParameters', 'url'=>array('index')),
	array('label'=>'Create WaterRequestParameters', 'url'=>array('create')),
	array('label'=>'View WaterRequestParameters', 'url'=>array('view', 'id'=>$model->name)),
	array('label'=>'Manage WaterRequestParameters', 'url'=>array('admin')),
);
?>

<h1>Update WaterRequestParameters <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>