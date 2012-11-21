<div class="form">

<?php

$this->breadcrumbs=array(
	Yii::t('settings','Settings')=>array('index'),
	Yii::t('settings','Update')
);

?>

<h1><?php echo Yii::t('settings','Settings'); ?></h1>

<?php 
	$form = $this->beginWidget('UniActiveForm', array(
		'id'=>'settings-form'
	)); 
?>
	
	<div class="flash-success"><?php echo Yii::t('settings','Settings were updated successfully.'); ?></div>
	
<?php	
	while(list($key,$value) = each($categories)) 
	{
?>
	<div class="jFormComponent">
		<?php echo $form->labelEx($categories[$key]['model'],'notification_category_ptr'); ?>
		<?php echo ucfirst($categories[$key]['category'])." ".$categories[$key]['type']."  ".$form->checkBox($categories[$key]['model'],'send_mail',array("disabled"=>true));?>
	</div>
<?php
	}
?>	
	
<?php	
	$this->endWidget(); 
?>
</div>
