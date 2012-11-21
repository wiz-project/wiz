<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);

Yii::app()->clientScript->registerScript('view_error', "
	jQuery(document).ready(function() {
		$('.jFormComponent').each(function (e) {
			var identifier = $(this).attr('id');
			if($('#'+identifier+' .errorMessage').html()) {
				if($('#'+identifier).hasClass('jFormComponentHighlight'))
					$('#'+identifier).removeClass('jFormComponentHighlight');
				$('#'+identifier).addClass('jFormComponentErrorHighlight');
				if($('#'+identifier+'_error .jFormerTip').length == 0)
					$('#'+identifier+'_error').append('<div class=\"jFormerTip\"><div class=\"tipArrow\"></div><div class=\"tipContent\"><p>'+$('#'+identifier+' .errorMessage').html()+'</p></div></div>');
			}
		});
		$('.jFormComponent :input').blur(function() {
			var divHighlight = $(this).parent('div');
			if(divHighlight.hasClass('jFormComponentErrorHighlight')) {
				divHighlight.removeClass('jFormComponentErrorHighlight');
				divHighlight.addClass('jFormComponentHighlight');
			}
		});
	});
");
?>

<h1>Login</h1>

<?php if(Yii::app()->user->hasFlash('retrieve')): ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('retrieve'); ?>
	</div>

<?php else: 
		if(Yii::app()->user->hasFlash('create')): ?>
			<div class="flash-success">
				<?php echo Yii::app()->user->getFlash('create'); ?>
			</div>
			
<?php   endif;
	
	   endif; 
?>

<p><?php  echo Yii::t('user', 'Please fill out the following form with your login credentials:');?></p>

<div class="form">
	<?php 
		$form=$this->beginWidget('UniActiveForm', array(
			'id'=>'login-form',
			'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'username'),
		)); 
	?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>

	<div class="jFormComponent" id="username">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div id="username_error"></div>

	<div class="jFormComponent" id="password">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div id="password_error"></div>

	<div class="jFormComponent rememberMe" id="rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	<div id="rememberMe_error"></div>

	<div class="jFormComponent button">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>
		
	<div class="jFormComponent">
		<?php  echo Yii::t('form', 'New users?');?> <?php echo CHtml::link(Yii::t('form', 'Register!'),CController::createUrl('users/create',array('redirect'=>'site/login')));?>
	</div>
	<div class="jFormComponent">
		<?php  echo Yii::t('form', 'Forgot your password?');?> <?php echo CHtml::link(Yii::t('form', 'Retrieve password!'),CController::createUrl('site/retrieve'));?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->

