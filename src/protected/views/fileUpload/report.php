<?php
$this->breadcrumbs=array(
	Yii::t('excel','Save Excel Data') => array('save', 'filename'=>$filename),
	Yii::t('excel','Transaction Report'),
);
?>

<h1><?php echo Yii::t('excel','Transaction Report'); ?></h1>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('success'); ?>
</div>

<div class="flash-notice">
	<?php echo Yii::app()->user->getFlash('warning'); ?>
</div>

<div class="flash-error">
	<?php echo Yii::app()->user->getFlash('error'); ?>
</div>
