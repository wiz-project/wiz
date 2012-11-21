<?php
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/proj4js-combined.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG3003.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG32232.js', CClientScript::POS_HEAD);

$this->breadcrumbs=array(
	'Water Request Update'=>array('update', 'id'=>$wr_id),
	'Water Request Geometries Upload',
);
?>
<h1><?php  echo Yii::t('waterrequest', 'Upload Geometries for WaterRequest #').$wr_id; ?></h1>

<?php  
$form=$this->beginWidget('UniActiveForm', array(
    'id'=>'upload-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'), // ADD THIS
)); 

?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'shpfile'); ?>
		<?php echo $form->fileField($model,'shpfile'); ?>
		<?php echo $form->error($model,'shpfile'); ?>
	</div>
<?php 
/*
	<div class="row">
		<?php echo $form->labelEx($model,'shxfile'); ?>
		<?php echo $form->fileField($model,'shxfile'); ?>
		<?php echo $form->error($model,'shxfile'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'dbffile'); ?>
		<?php echo $form->fileField($model,'dbffile'); ?>
		<?php echo $form->error($model,'dbffile'); ?>
	</div>
*/
?>
	<div class="row">
		<?php echo $form->labelEx($model,'fileproj'); // TODO: Deve diventare un ulteriore file .prj o no? ?>
		<?php echo $form->dropDownList($model,'fileproj', array()); ?>
		<?php echo $form->error($model,'fileproj'); ?>
	</div>
<br>
	<div class="row jFormComponent buttons">
	<?php echo CHtml::submitButton('Vai'); ?>
	</div>

<br>
	
<?php 
$this->endWidget();

echo CHtml::link('Torna alla Water Request #'.$wr_id, array('waterRequests/update', 'id'=>$wr_id));

?>
<script type="text/javascript">
// Imposta la dropdown con le proiezioni caricate in Proj4js 
function setprojchoice(){
	var last = 0;
	var options = $('#WaterRequests_fileproj').prop('options');
	for(proj in Proj4js.defs){
		if (proj.indexOf("EPSG") !=-1) {
			options[options.length] = new Option(proj, proj.replace('EPSG:', '')); // defaultSelected = false, selected = false
			last =  proj.replace('EPSG:', '');
		}
	}
	// Seleziona l'ultima proiezione trovata 
	if(last){
		$('#WaterRequests_fileproj').val(last);
		$.uniform.update("#WaterRequests_fileproj");
	}
}

window.onload=setprojchoice;
</script>