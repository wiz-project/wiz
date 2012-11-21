<div class="form">

<?php 
	$form=$this->beginWidget('UniActiveForm', array(
		'id'=>'config-form',
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'focus'=>($model->hasErrors()) ? '.error:first' : $action=='create' ? array($model, 'param_name') : array($model, 'param_value')
	)); 
?>

	<?php if(Yii::app()->user->hasFlash('error')) { ?>
			<div class="flash-error">
				<?php echo Yii::t('config',Yii::app()->user->getFlash('error')); ?>
			</div>
	<?php } ?>

	<p class="note"><?php echo Yii::t('config','Fields with <span class="required">*</span> are required.<br> Enter the value 1 (true) or 0 (false) if the parameter is a boolean.'); ?></p>
	
	<div class="jFormComponent" id="param_name">
		<?php echo $form->labelEx($model,'param_name'); ?>
		<?php echo $form->textField($model,'param_name',array('size'=>60,$action=='create' ? '' : 'readonly'=>true)); ?>
		<?php echo $form->error($model,'param_name'); ?>
	</div>
	<div id="param_name_error"></div>

	<div class="jFormComponent" id="param_value">
		<?php echo $form->labelEx($model,'param_value'); ?>
		<?php echo $form->textField($model,'param_value',array('size'=>60)); ?>
		<?php echo $form->error($model,'param_value'); ?>
	</div>
	<div id="param_value_error"></div>
	
	<div class="jFormComponent" id="param_parent">
		<?php echo CHtml::label(Yii::t('config','Parent Param'),'param_parent'); ?>
		<?php 
			if($action=='create')
				echo CHtml::dropDownList('param_parent', null, $params_list, array('empty'=>Yii::t('config','- None -'))); 
			else {
				echo CHtml::dropDownList('param_parent_list', 0, $params_list, array('empty'=>Yii::t('config','- None -'),'disabled'=>true)); 
				echo Chtml::hiddenField('param_parent', !empty($params_list) ? $params_list[0] : ""); 
			}
		?>
	</div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton($action=='create' ? Yii::t('config','Create') : Yii::t('config','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->