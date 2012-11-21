<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'water-opinions-form',
	'enableAjaxValidation'=>false
)); ?>

	<p class="note"><?php echo Yii::t('citizen','Fields with <span class="required">*</span> are required.'); ?></p>

	<?php if(Yii::app()->user->hasFlash('warning')) { ?>

			<div class="flash-notice">
				<?php echo Yii::t('citizen',Yii::app()->user->getFlash('warning')); ?>
			</div>

	<?php } else {
				if(Yii::app()->user->hasFlash('error')) { ?>
					
					<div class="flash-error">
						<?php echo Yii::t('citizen',Yii::app()->user->getFlash('error')); ?>
					</div>
	
	<?php } } 
	
		echo Chtml::hiddenField('geom'); 
		
		echo "<div class=\"jFormComponent\">";
		echo Chtml::radioButtonList('geom_type',1,
				array(
					'1'=>Yii::t('citizen','Evaluate the quality of service'),
					'2'=>Yii::t('citizen','Report a fault on the water network'),
				),
				array('labelOptions'=>array('style'=>'display:inline'), 
				'uncheckValue' => 1,
				'separator'=>'<br>',)
			);
		echo "</div>";
	
	?>
	
	<div class="jFormComponent" id="quality">
		<?php echo CHtml::label('Quality <span class="required">*</span>','qualities_list') ?>
		<?php echo CHtml::dropDownList('qualities_list', '', CHtml::listData(WaterQualities::model()->findAll(array('order'=>'priority')), 'id', 'quality'), array('empty'=>Yii::t('citizen','Select Quality'))); ?>
	</div>
	
	<div class="jFormComponent" id="fault">
		<?php echo CHtml::label('Fault <span class="required">*</span>','faults_list') ?>
		<?php echo CHtml::dropDownList('faults_list', '', CHtml::listData(WaterFaults::model()->findAll(array('order'=>'priority')), 'id', 'fault'), array('empty'=>Yii::t('citizen','Select Fault'))); ?>
	</div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton(Yii::t('citizen','Create')); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	function edit_lot_coords(geom){
		document.getElementById('geom').value=geom;
	}
</script>
</div><!-- form -->