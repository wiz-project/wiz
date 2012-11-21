<div class="wide form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<!--
	<div class="row">
		<?php //echo $form->label($model,'description'); ?>
		<?php //echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
	</div>
	-->

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enable'); ?>
		<?php echo $form->checkBox($model,'enable',array('uncheckValue' => 0,"checked" => true)); ?>
	</div>


	<div class="advanced_search">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->