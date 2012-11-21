<div class="form">

<?php 
	$form=$this->beginWidget('UniActiveForm', array(
		'id'=>'users-form',
		'enableAjaxValidation'=>false,
		'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'first_name')
	)); 
	
	echo Chtml::hiddenField('redirect',$redirect); 
?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
	
	<div class="jFormComponent" id="first_name">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>
	<div id="first_name_error"></div>

	<div class="jFormComponent" id="last_name">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>
	<div id="last_name_error"></div>

	<div class="jFormComponent" id="municipality">
		<?php echo $form->labelEx($model,'municipality'); ?>
		<?php echo $form->textField($model,'municipality',array('size'=>60,'maxlength'=>255,$model->isNewRecord ? '' : 'readonly'=>true)); ?>
		<?php echo $form->error($model,'municipality'); ?>
	</div>
	<div id="municipality_error"></div>

	<div class="jFormComponent" id="organisation">
		<?php echo $form->labelEx($model,'organisation'); ?>
		<?php echo $form->textField($model,'organisation',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'organisation'); ?>
	</div>
	<div id="organisation_error"></div>

	<div class="jFormComponent" id="title">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<div id="title_error"></div>

	<div class="jFormComponent" id="email">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div id="email_error"></div>

	<div class="jFormComponent" id="username">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255,(!$redirect=='home' || $model->isNewRecord) ? '' : 'readonly'=>true)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div id="username_error"></div>

	<?php if($model->isNewRecord) { ?>
	
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

	<?php } ?>
	
	<div class="jFormComponent" id="role_name">
		<?php echo $form->labelEx($model,'role_name'); ?>
		<?php 
			if($redirect == 'site/login')
				echo $form->dropDownList($model, 'role_name', Roles::registrationList(), array('empty'=>Yii::t('user', 'Select Role')));
			else
				if($redirect == 'home')
					echo $form->dropDownList($model, 'role_name', Roles::searchableList(), array('empty'=>Yii::t('user', 'Select Role'),'disabled'=>true));
				else
					echo $form->dropDownList($model, 'role_name', Roles::searchableList(), array('empty'=>Yii::t('user', 'Select Role')));
		?>
		<?php echo $form->error($model,'role_name'); ?>
	</div>
	<div id="role_name_error"></div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->