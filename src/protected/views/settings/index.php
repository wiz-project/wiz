<script type="text/javascript">
	function setCheck(obj,index) {
		if($(obj).attr("checked"))
			$('#send_mail'+index).attr("value",true);
		else
			$('#send_mail'+index).attr("value",false);
	}
</script>

<div class="form">

<?php

$this->breadcrumbs=array(
	Yii::t('settings','Settings'),
);

?>

<h1><?php echo Yii::t('settings','Settings'); ?></h1>

<?php 
	$form = $this->beginWidget('UniActiveForm', array(
		'id'=>'settings-form',
		'enableAjaxValidation'=>false
	)); 
?>
	<p class="note"><?php echo Yii::t('settings','Disable the field if you do not want to receive email notification associated.'); ?></p>

<?php	
	while(list($key,$value) = each($categories)) 
	{
?>
	<div class="jFormComponent">
		<?php echo $form->labelEx($categories[$key]['model'],'notification_category_ptr'); ?>
		<?php echo ucfirst($categories[$key]['category'])." ".$categories[$key]['type']."  ".$form->checkBox($categories[$key]['model'],'send_mail',array('uncheckValue' => false,'onclick' => 'javascript:setCheck(this,'.$key.')'));?>
		<?php echo Chtml::hiddenField('send_mail'.$key); ?>
	</div>
<?php
	}
?>

	<div class="jFormComponent button">
		<?php echo CHtml::button(Yii::t('settings','Salva'), array('class'=>'btn','submit' => array('settings/update'))); ?>
	</div>
	
<?php	
	$this->endWidget(); 
?>
</div>
