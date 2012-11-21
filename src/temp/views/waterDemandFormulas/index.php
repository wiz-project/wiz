<?php
$this->breadcrumbs=array(
	'Water Demand Formulases',
);

$this->menu=array(
	array('label'=>'Create WaterDemandFormulas', 'url'=>array('create')),
	array('label'=>'Manage WaterDemandFormulas', 'url'=>array('admin')),
);
?>

<h1>Water Demand Formulases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
