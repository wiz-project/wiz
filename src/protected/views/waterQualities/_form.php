<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'water-qualities-form',
	'enableAjaxValidation'=>false,
	'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'quality')
)); ?>

<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
	
	<div class="jFormComponent" id="quality">
		<?php echo $form->labelEx($model,'quality'); ?>
		<?php echo $form->textField($model,'quality'); ?>
		<?php echo $form->error($model,'quality'); ?>
	</div>
	<div id="quality_error"></div>

	<div class="jFormComponent" id="color">
		<?php echo $form->labelEx($model,'color'); ?>
		<?php echo $form->textField($model,'color',array('class'=>'colorpicker_input')); ?>
		<?php echo $form->error($model,'color'); ?>
	</div>
	<div id="color_error"></div>
	
	<div class="jFormComponent" id="priority">
		<?php echo $form->labelEx($model,'priority'); ?>
		<?php echo $form->textField($model,'priority'); ?>
		<?php echo $form->error($model,'priority'); ?>
	</div>
	<div id="priority_error"></div>
		
	<div class="jFormComponent" id="image">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image'); ?>
		<div style="display:inline;font-style:italic">Indicare il percorso dell'immagine sul filesystem</div>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div id="image_error"></div>	
		
	<div class="jFormComponent" id="active">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->checkBox($model,'active'); ?>
	</div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('qualities','Create') : Yii::t('qualities','Save'),array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->