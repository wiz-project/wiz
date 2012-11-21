<div class="form">

	<?php $form=$this->beginWidget('UniActiveForm', array(
		'id'=>'users-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<p class="note"><?php  echo Yii::t('user', 'Approve the role chosen by the user, or select and approve a new role.');?></p>

	<?php 
	
		$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'first_name',
			'last_name',
			'municipality',
			'email',
			'username',
			'password',
			'active',
			'activation_link',
		),
	)); 
	
	?>
	
	<div class="jFormComponent" id="role_name">
		<?php echo $form->labelEx($model,'role_name'); ?>
		<?php echo $form->dropDownList($model, 'role_name', Roles::registrationList(), array('empty'=>'Select Role')); ?>
		<?php echo $form->error($model,'role_name'); ?>
	</div>
	<div id="role_name_error"></div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton(Yii::t('user', 'Approve'),array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->