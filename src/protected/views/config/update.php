<?php
$this->breadcrumbs=array(
	Yii::t('config','Manage Params')=>array('index'),
	Yii::t('config','Edit Param'),
);
?>

<h1><?php echo Yii::t('config','Edit Param'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'params_list'=>$params_list,'action'=>'update')); ?>