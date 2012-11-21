<?php echo '<h2>'.Yii::t('waterrequest', 'Editing Geometry').'' #'.$model->id.'</h2>'; ?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'water-request-geometries-form',
	'action'=>CController::createUrl('//waterRequestGeometries/update',array('id'=>$model->id)),
//	'enableAjaxValidation'=>true,
//	'enableClientValidation'=>true,
//	'clientOptions'=>array('validateOnSubmit'=>true,'validateOnChange'=>true, ),
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'geom'); ?>
		<?php echo $form->textField($model,'geom', array('readonly'=>'true', 'id'=>'inner_geom')); ?>
		<?php echo $form->error($model,'geom'); ?>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::ajaxSubmitButton(
			Yii::t('waterrequest', 'Save'),
			CController::createUrl('waterRequestGeometries/update',array('id'=>$model->id)),
			array('success'=>'function(){new_refresh_geometries_table();doOverlayClose($(\'#'.$form->id.'\'));}'),
			array()
	    );
		?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->