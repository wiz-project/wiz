<?php
$this->breadcrumbs=array(
	'Zones Water Request Parameters'=>array('/zonesWaterRequestParameters'),
	'Create',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'zones-water-request-parameters-create-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parameter'); ?>
		<?php echo $form->textField($model,'parameter'); ?>
		<?php echo $form->error($model,'parameter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone'); ?>
		<?php echo $form->textField($model,'zone'); ?>
		<?php echo $form->error($model,'zone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->