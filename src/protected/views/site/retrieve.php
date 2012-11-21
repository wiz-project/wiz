<?php
$this->breadcrumbs=array(
	'Login' => array('site/login'),
	Yii::t('user', 'Retrieve Password'),
);
?>

<h1><?php  echo Yii::t('user', 'Retrieve Password');?></h1>

<?php if(Yii::app()->user->hasFlash('error')){ ?>

	<div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
	
<?php } ?>

<p><?php  echo Yii::t('user', 'Please enter your username; the system will generate a new random password and send it to your e-mail.');?></p>
	
<div class="form">
	<?php $form=$this->beginWidget('UniActiveForm', array(
		'id'=>'retrieve-form',
		'enableClientValidation'=>false,
		'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'username')
	)); ?>
		
	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
		
	<div class="jFormComponent" id="username">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div id="username_error"></div>
		
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton(Yii::t('user', 'Retrieve')); ?>
	</div>
		
	<?php $this->endWidget(); ?>
</div><!-- form -->
