<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div id="error_message">
	<h2>Error <?php echo $code; ?></h2>
	<?php echo CHtml::encode($message); ?>
</div>