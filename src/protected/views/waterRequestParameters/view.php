<?php
$this->breadcrumbs=array(
	'Water Request Parameters'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Parameters', 'url'=>array('index')),
	array('label'=>Yii::t('waterrequest', 'Create Parameter'), 'url'=>array('create')),
);
?>

<h1>View Parameter #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>