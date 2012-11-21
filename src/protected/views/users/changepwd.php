<?php
$this->breadcrumbs=array(
	Yii::t('user', 'View Profile')=>array('view','id'=>$model->username,'redirect'=>'home'),
	Yii::t('user', 'Change password')
);
	
$this->menu=array(
	array('label'=>Yii::t('user', 'View Profile'), 'url'=>array('view', 'id'=>$model->username, 'redirect'=>'home')),
);
?>

<h1><?php  echo Yii::t('user', 'Change password');?></h1>

<?php if(Yii::app()->user->hasFlash('error')){ ?>

	<div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
	
<?php } ?>
	
<div class="form">

<?php 	
	$form=$this->beginWidget('UniActiveForm', array(
		'id'=>'changepwd-form',
		'enableClientValidation'=>false,
		'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'password')
	)); 
	
	echo Chtml::hiddenField('redirect',$redirect); 
?>
		
	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
		
	<div class="jFormComponent" id="password">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div id="password_error"></div>
	
	<div class="jFormComponent" id="repeat_password">
		<?php echo $form->labelEx($model,'repeat_password'); ?>
		<?php echo $form->passwordField($model,'repeat_password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'repeat_password'); ?>
	</div>
	<div id="repeat_password_error"></div>
		
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton(Yii::t('user', 'Change')); ?>
	</div>
		
	<?php $this->endWidget(); ?>
</div><!-- form -->