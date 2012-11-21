<?php

if($redirect == 'site/login')
	$this->breadcrumbs=array(
		'Login' => array('site/login'),
		Yii::t('user', 'Create User'),
	);
else
	$this->breadcrumbs=array(
		Yii::t('user', 'Manage Users')=>array('admin'),
		Yii::t('user', 'Create User'),
	);

if(!$redirect == 'site/login')	
	$this->menu=array(
		array('label'=>Yii::t('user', 'Manage Users'), 'url'=>array('admin')),
	);
?>

<h1><?php  echo Yii::t('user', 'Create User');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'redirect'=>$redirect)); ?>