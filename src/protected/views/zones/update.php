<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest','Zones')=>array('index'),
	$model->name=>array('view','id'=>$model->name),
	Yii::t('waterrequest','Edit Zone'),
);

$this->menu=array(
	array('label'=>Yii::t('waterrequest','List Zones'), 'url'=>array('index')),
	array('label'=>Yii::t('waterrequest','Create Zone'), 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('waterrequest','Edit Zone')." ".$model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>