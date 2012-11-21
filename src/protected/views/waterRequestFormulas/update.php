<?php
$this->breadcrumbs=array(
	'Water Request Formulas'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Formulas', 'url'=>array('index')),
	array('label'=>'Create Formula', 'url'=>array('create')),
);
?>

<h1>Update Water Request Formula <?php echo $model->zone; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>