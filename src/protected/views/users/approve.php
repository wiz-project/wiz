<?php
$this->breadcrumbs=array(
	Yii::t('user', 'Manage Users')=>array('admin'),
	$model->username=>array('view','id'=>$model->username),
	Yii::t('user', 'Approve'),
);

$this->menu=array(
	array('label'=>Yii::t('user', 'Manage Users'), 'url'=>array('admin')),
	array('label'=>Yii::t('user', 'Create User'), 'url'=>array('create')),
	array('label'=>Yii::t('user', 'View User'), 'url'=>array('view', 'id'=>$model->username)),
);
?>

<h1><?php  echo Yii::t('user', 'Approve a role for the user');?></h1>

<?php echo $this->renderPartial('_approve', array('model'=>$model)); ?>