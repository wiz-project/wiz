<?php
$this->breadcrumbs=array(
	'Water Supplys',
);

$this->menu=array(
	array('label'=>'Create WaterSupply', 'url'=>array('create')),
	array('label'=>'Manage WaterSupply', 'url'=>array('admin')),
);
?>

<h1>Water Supplys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
