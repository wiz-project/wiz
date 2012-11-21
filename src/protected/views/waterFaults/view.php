<?php
$this->breadcrumbs=array(
	Yii::t('faults','Water Faults')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('faults','List Faults'), 'url'=>array('index')),
	array('label'=>Yii::t('faults','Create Fault'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('faults','View Fault'); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fault',
		'color',
		'priority',
		'image',
		'active',
	),
)); ?>