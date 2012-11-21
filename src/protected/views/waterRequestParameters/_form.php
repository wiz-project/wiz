<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'waterrequestparameters-form',
	'enableAjaxValidation'=>false,
	'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'name')
)); ?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
	
	<div class="jFormComponent" id="name">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',$model->isNewRecord ? array('size'=>60,'maxlength'=>255) : array('size'=>60,'maxlength'=>255,'readonly'=>true)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div id="name_error"></div>

	<div class="jFormComponent" id="description">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<div id="description_error"></div>

	<div class="jFormComponent" id="measurement_unit">
		<?php echo $form->labelEx($model,'measurement_unit'); ?>
		<?php echo $form->textField($model,'measurement_unit',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'measurement_unit'); ?>
	</div>
	<div id="measurement_unit_error"></div>
	
	<div class="jFormComponent" id="zone">
		<?php echo $form->labelEx($model->zone_request_parameters,'zone'); ?>
		<?php echo $form->dropDownList($model->zone_request_parameters,'zone',CHtml::listData(Zones::zonesList(null), 'name', 'name'), array('empty'=>'Select Area')); ?>
		<?php echo $form->error($model->zone_request_parameters,'zone'); ?>
	</div>
	<div id="zone_error"></div>
	
	<div class="jFormComponent" id="value">
		<?php echo $form->labelEx($model->zone_request_parameters,'value'); ?>
		<?php echo $form->textField($model->zone_request_parameters,'value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model->zone_request_parameters,'value'); ?>
	</div>
	<div id="value_error"></div>
	
	<?php
		if(!$model->isNewRecord) { ?>
		
	<div class="jFormComponent" id="active">
		<?php echo $form->labelEx($model->zone_request_parameters,'active'); ?>
		<?php echo $form->checkBox($model->zone_request_parameters,'active'); ?>
	</div>
	
	<?php
		} ?>
	
	<div class="jFormComponent" id="required">
		<?php echo $form->labelEx($model->zone_request_parameters,'required'); ?>
		<?php echo $form->checkBox($model->zone_request_parameters,'required'); ?>
	</div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->