<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'water-requests-form',
		'enableAjaxValidation'=>false,
		'action'=>CController::createUrl('waterRequests/updateStatus',array('id'=>$model->id))
	));
?>
	
<div class="row status_buttons">
	<?php
		foreach(SWHelper::nextStatuslistData($model,false) as $k=>$v) {
			if ($model->status===WaterRequests::SW_NODE(WaterRequests::CANCELLED_STATUS))
				echo CHtml::submitButton(Yii::t('waterrequest', 'Restore'), array('id'=>'save-button','name'=>'save-button','class'=>'restore-button'));
			else
				echo CHtml::submitButton(Yii::t('waterrequest', ucwords(str_replace('_',' ',$v))), array('id'=>$v.'-button','name'=>$v.'-button'));
		}
	?>
</div>

<?php $this->endWidget(); ?>