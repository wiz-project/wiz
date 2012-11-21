<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'water-faults-form',
	'enableAjaxValidation'=>false,
	'focus'=>($model->hasErrors()) ? '.error:first' : array($model, 'fault')
)); ?>

<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
	
	<div class="jFormComponent" id="fault">
		<?php echo $form->labelEx($model,'fault'); ?>
		<?php echo $form->textField($model,'fault'); ?>
		<?php echo $form->error($model,'fault'); ?>
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
		<?php echo $form->textField($model,'image',array('size'=>'30px')); ?>
		<div style="display:inline;font-style:italic">Indicare il percorso dell'immagine sul filesystem</div>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div id="image_error"></div>	
		
	<div class="jFormComponent" id="active">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->checkBox($model,'active'); ?>
	</div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('faults','Create') : Yii::t('faults','Save'),array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->