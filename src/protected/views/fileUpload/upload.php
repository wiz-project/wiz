<script type="text/javascript">
	$(document).ready(function() {
	  <?php if($upload) { ?>
		$("#uploaded_file").removeClass("jFormComponentHighlight");
		$("#uploaded_file").removeClass("jFormComponentErrorHighlight");
		$("#uploaded_file").addClass("jFormComponentSuccessHighlight");
	  <?php } else { ?>
		$("#uploaded_file").addClass("jFormComponentHighlight");
		if($("#uploaded_file .errorMessage").html()) {
			$("#uploaded_file").addClass("jFormComponentErrorHighlight");
			if($("#uploaded_file_error .jFormerTip").length == 0)
				$("#uploaded_file_error").append('<div class=\"jFormerTip\"><div class=\"tipArrow\"></div><div class=\"tipContent\"><p>'+$("#uploaded_file .errorMessage").html()+'</p></div></div>');
		} else {
			if($("#uploaded_file").hasClass("jFormComponentErrorHighlight"))
				$("#uploaded_file").removeClass("jFormComponentErrorHighlight");
			$("#uploaded_file").addClass("jFormComponentHighlight");
		}
	  <?php } ?>
	});
</script>

<?php
$this->breadcrumbs=array(
	Yii::t('excel','Upload Excel File'),
);
?>

<h1><?php echo Yii::t('excel','Upload Excel File'); ?></h1>

<p><?php echo Yii::t('excel','Search on filesystem an .xls file to upload.'); ?></p>

<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
	'id'=>'fileupload-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo Yii::t('excel','The file to upload is required.'); ?></p>
	
	<div class="jFormComponent" id="uploaded_file">
		<?php echo $form->labelEx($model,'uploaded_file'); ?>
		<?php echo $form->fileField($model,'uploaded_file', $upload ? array('disabled'=>'disabled') : ''); ?>
		<?php 
			if(!$upload)
				echo $form->error($model,'uploaded_file'); 
			else
				echo "<div style=\"font-weight:bold;font-style:italic;color:#afbe73\">".Yii::t('excel','Operation completed')."</div>";
		?>
	</div>
	<div class="jFormComponent" id="uploaded_file_error"></div>

	<div class="jFormComponent button">
		<?php 
			if(!$upload)  
				echo CHtml::submitButton(Yii::t('excel','Upload'));
			else
				echo CHtml::Button(Yii::t('excel','Next'),array('submit'=>Yii::app()->createUrl("fileUpload/save", array("filename"=>$filename))));
		?>
	</div>
	
<?php $this->endWidget(); ?>

</div>

