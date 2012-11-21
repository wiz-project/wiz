<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wr_geometry_id'); ?>
		<?php echo $form->textField($model,'wr_geometry_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'zone'); ?>
		<?php echo $form->textField($model,'zone',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pe'); ?>
		<?php echo $form->textField($model,'pe'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'water_demand'); ?>
		<?php echo $form->textField($model,'water_demand'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->