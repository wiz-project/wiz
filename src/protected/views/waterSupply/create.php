<?php
$this->breadcrumbs=array(
	'Water Supplys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WaterSupply', 'url'=>array('index')),
	array('label'=>'Manage WaterSupply', 'url'=>array('admin')),
);
?>

<h1>Create WaterSupply</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>