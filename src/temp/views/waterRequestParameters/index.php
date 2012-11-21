<?php
$this->breadcrumbs=array(
	'Water Request Parameters',
);

$this->menu=array(
	array('label'=>'Create WaterRequestParameters', 'url'=>array('create')),
	array('label'=>'Manage WaterRequestParameters', 'url'=>array('admin')),
);
?>

<h1>Water Request Parameters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
