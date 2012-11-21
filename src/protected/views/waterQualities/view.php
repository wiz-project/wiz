<?php
$this->breadcrumbs=array(
	Yii::t('qualities','Water Qualities')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('qualities','List Qualities'), 'url'=>array('index')),
	array('label'=>Yii::t('qualities','Create Quality'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('qualities','View Quality'); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'quality',
		'color',
		'priority',
		'image',
		'active',
	),
)); ?>