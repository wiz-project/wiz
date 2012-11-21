<?php
$this->breadcrumbs=array(
	'Water Request Formulas',
);

$this->menu=array(
	array('label'=>'Create Formula', 'url'=>array('create')),
);
?>

<h1>Water Request Formulas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
