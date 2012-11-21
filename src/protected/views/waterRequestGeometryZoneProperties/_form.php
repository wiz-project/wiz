<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'water-request-geometry-zone-properties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'geometry_zone'); echo $model->geometry_zone;?>
		<?php echo $form->hiddenField($model,'geometry_zone'); ?>
		<?php echo $form->error($model,'geometry_zone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parameter'); ?>
		<?php //echo $form->textField($model,'parameter',array('size'=>60,'maxlength'=>255)); 
				echo $form->dropDownList(
                    $model,
                    'parameter', 
                    CHtml::listData(ZonesWaterRequestParameters::model()->findAll('zone=:zone_type AND active=:active',array(':active'=>true,':zone_type'=>$zone_type)),
                    	'parameter', 
                    	'parameter'), 
					array('empty'=>'Select Parameter')
					);
		?>
		<?php echo $form->error($model,'parameter'); ?>
	</div>
	<br/>
	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->