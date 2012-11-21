<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	$model->name=>array('view','id'=>$model->name),
	'Update',
);

$this->menu=array(
	array('label'=>'List Parameters', 'url'=>array('index')),
	array('label'=>'Create Parameter', 'url'=>array('create')),
);
?>

<h1>Update Parameter #<?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>