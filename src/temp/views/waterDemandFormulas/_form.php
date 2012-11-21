<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'water-demand-formulas-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'zone'); ?>
		<?php echo $form->textField($model,'zone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'zone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'formula'); ?>
		<?php echo $form->textField($model,'formula',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'formula'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->