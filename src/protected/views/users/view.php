<?php
if($redirect == 'home') {
	$this->breadcrumbs=array( Yii::t('waterrequest', 'View Profile') );
	$this->menu=array(
		array('label'=>Yii::t('user', 'Update Profile'), 'url'=>array('update', 'id'=>$model->username, 'redirect'=>'home')),
	);
}
else {
	$this->breadcrumbs=array(
		Yii::t('user', 'Manage Users')=>array('admin'),
		$model->username,
	);

	$this->menu=array(
		array('label'=>Yii::t('user', 'Manage Users'), 'url'=>array('admin')),
		array('label'=>Yii::t('user', 'Create User'), 'url'=>array('create')),
		array('label'=>Yii::t('user', 'Update User'), 'url'=>array('update', 'id'=>$model->username)),
	);
}
?>

<h1><?php  echo Yii::t('user', 'User').' #'.$model->username; ?></h1>

<?php if(Yii::app()->user->hasFlash('retrieve')){ ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('retrieve'); ?>
	</div>
	
<?php } else { 
			if(Yii::app()->user->hasFlash('changepwd')){ ?>
				
				<div class="flash-success">
					<?php echo Yii::app()->user->getFlash('changepwd'); ?>
				</div>
			
<?php } } ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'first_name',
		'last_name',
		'municipality',
		'email',
		'username',
		array(
			'label'=>'Password',
            'type'=>'raw',
            'value'=>CHtml::link(Yii::t('user', 'Generate new password'),array('users/retrieve','id'=>$model->username,'redirect'=>'view&id='.$model->username.'&redirect=home')).' / '.CHtml::link(Yii::t('user', 'Change password'),array('users/changepwd','id'=>$model->username,'redirect'=>'view&id='.$model->username.'&redirect=home')),
			'visible'=>!Yii::app()->user->isSysAdmin,
		),
		'active',
		'roleName',
		'approved',
		array(
			'name'=>'last_login',
            'type'=>'raw',
            'value'=>$model->timestampHR,
		),
		array(
			'name'=>'activation_link',
            'type'=>'raw',
            'visible'=>Yii::app()->user->isSysAdmin,
		),
	),
)); ?>
