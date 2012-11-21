<div class="wide form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'measurement_unit'); ?>
		<?php echo $form->textField($model,'measurement_unit',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="advanced_search">
		<?php echo CHtml::submitButton(Yii::t('waterrequest', 'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->