<div class="wide form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city_state'); ?>
		<?php echo $form->textField($model,'city_state',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'daily_maximum_water_supply'); ?>
		<?php echo $form->textField($model,'daily_maximum_water_supply'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yearly_average_water_supply'); ?>
		<?php echo $form->textField($model,'yearly_average_water_supply'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'scenario'); ?>
		<?php echo $form->textField($model,'scenario'); ?>
	</div>

	<div class="advanced_search">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->