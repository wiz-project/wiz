<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest','Zones')=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('waterrequest','List Zones'), 'url'=>array('index')),
	array('label'=>Yii::t('waterrequest','Create Zone'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('waterrequest','View Zone'); ?> #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',
		'parent_zone_name',
		'active',
		'searchable'
	),
)); ?>