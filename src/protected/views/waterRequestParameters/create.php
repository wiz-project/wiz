<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Parameters', 'url'=>array('index')),
);
?>

<h1>Create Parameter</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>