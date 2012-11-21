<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WaterRequestParameters', 'url'=>array('index')),
	array('label'=>'Manage WaterRequestParameters', 'url'=>array('admin')),
);
?>

<h1>Create WaterRequestParameters</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>