<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'zones-form',
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
	
	<div class="jFormComponent" id="parent_zone_name">
		<?php echo $form->labelEx($model,'parent_zone_name'); ?>
		<?php echo $form->dropDownList($model,'parent_zone_name',CHtml::listData(Zones::zonesListAll(), 'name', 'name'), array('empty'=>Yii::t('waterrequest','Select Area'))); ?>
		<?php echo $form->error($model,'parent_zone_name'); ?>
	</div>
	<div id="parent_zone_name_error"></div>

	<?php
		if(!$model->isNewRecord) { ?>
		
	<div class="jFormComponent" id="active">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->checkBox($model,'active'); ?>
	</div>
	
	<div class="jFormComponent" id="searchable">
		<?php echo $form->labelEx($model,'searchable'); ?>
		<?php echo $form->checkBox($model,'searchable'); ?>
	</div>
	
	<?php
		} ?>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->