<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest','Zones')=>array('index'),
	Yii::t('waterrequest','Create Zone'),
);

$this->menu=array(
	array('label'=>Yii::t('waterrequest','List Zones'), 'url'=>array('index')),
);
?>

<h1>Create Zone</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>