<?php
$this->breadcrumbs=array(
	'Water Request Formulas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Formulas', 'url'=>array('index')),
);
?>

<h1>Create Water Request Formula</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>