<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'waterrequestformulas-form',
	'enableAjaxValidation'=>false,
	'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'zone')
)); ?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
	
	<div class="jFormComponent" id="zone">
		<?php echo $form->labelEx($model,'zone'); ?>
		<?php echo $form->textField($model,'zone',$model->isNewRecord ? array('size'=>60,'maxlength'=>255) : array('size'=>60,'maxlength'=>255,'readonly'=>true)); ?>
		<?php echo $form->error($model,'zone'); ?>
	</div>
	<div id="zone_error"></div>

	<div class="jFormComponent" id="formula">
		<?php echo $form->labelEx($model,'formula'); ?>
		<?php echo $form->textField($model,'formula',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'formula'); ?>
	</div>
	<div id="formula_error"></div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->