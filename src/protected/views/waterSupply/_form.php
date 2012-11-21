<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'water-supply-form',
	'enableAjaxValidation'=>false,
	'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'city_state')
)); ?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>

	<div class="jFormComponent" id="city_state">
		<?php echo $form->labelEx($model,'city_state'); ?>
		<?php echo $form->textField($model,'city_state',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'city_state'); ?>
	</div>
	<div id="city_state_error"></div>

	<div class="jFormComponent" id="daily_maximum_water_supply">
		<?php echo $form->labelEx($model,'daily_maximum_water_supply'); ?>
		<?php echo $form->textField($model,'daily_maximum_water_supply'); ?>
		<?php echo $form->error($model,'daily_maximum_water_supply'); ?>
	</div>
	<div id="daily_maximum_water_supply_error"></div>

	<div class="jFormComponent" id="yearly_average_water_supply">
		<?php echo $form->labelEx($model,'yearly_average_water_supply'); ?>
		<?php echo $form->textField($model,'yearly_average_water_supply'); ?>
		<?php echo $form->error($model,'yearly_average_water_supply'); ?>
	</div>
	<div id="yearly_average_water_supply_error"></div>

	<div class="jFormComponent" id="scenario">
		<?php echo $form->labelEx($model,'scenario'); ?>
		<?php echo $form->textField($model,'scenario',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'scenario'); ?>
	</div>
	<div id="scenario_error"></div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->