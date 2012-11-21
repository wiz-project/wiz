<?php

if($redirect == 'home') {
	$this->breadcrumbs=array(
		Yii::t('user', 'View Profile')=>array('view','id'=>$model->username,'redirect'=>'home'),
		Yii::t('user', 'Update Profile')
	);
	
	$this->menu=array(
		array('label'=>Yii::t('user', 'View Profile'), 'url'=>array('view', 'id'=>$model->username, 'redirect'=>'home')),
	);
} else {
	$this->breadcrumbs=array(
		Yii::t('user', 'Manage Users')=>array('admin'),
		$model->username=>array('view','id'=>$model->username),
		Yii::t('user', 'Update'),
	);
	
	$this->menu=array(
		array('label'=>Yii::t('user', 'Manage Users'), 'url'=>array('admin')),
		array('label'=>Yii::t('user', 'Create User'), 'url'=>array('create')),
		array('label'=>Yii::t('user', 'View User'), 'url'=>array('view', 'id'=>$model->username)),
	);
}


?>

<h1><?php  echo Yii::t('user', 'Update User');?> <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'redirect'=>$redirect)); ?>