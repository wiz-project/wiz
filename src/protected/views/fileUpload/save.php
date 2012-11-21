<?php
$this->breadcrumbs=array(
	Yii::t('excel','Save Excel Data'),
);
?>

<h1><?php echo Yii::t('excel','Save Excel Data'); ?></h1>

<div class="form">

<?php $form=$this->beginWidget('UniActiveForm', array(
		'id'=>'save-form',
		'enableAjaxValidation'=>false,
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider' => $arrayDataProvider,
	    'columns' => $columnsArray,
)); ?>

	<div class="jFormComponent">
	<p class="note"><?php echo Yii::t('excel','Select the table you want to import data'); ?></p>
<?php 
	echo CHtml::radioButtonList('table','',
		array('NotificationCategories'=>'NotificationCategories',
		'Roles'=>'Roles',
		'WaterQualities'=>'WaterQualities',
		'WaterRequestParameters'=>'WaterRequestParameters',
		'Zones'=>'Zones'),
		array('labelOptions'=>array('style'=>'display:inline'), 
		'separator'=>'<br>',
		'onChange'=>CHtml::ajax(array('type'=>'POST', 'url'=>array('fileUpload/updateFields'), 
					'success'=>'function(data){
                        $("#fields").html(data);
                        $("#fields").find("select").uniform();
					}' )), 
	));
	echo Chtml::hiddenField('xlsAttributes', json_encode($xlsAttributes));
	echo Chtml::hiddenField('filename', $filename);
?>
	</div>

	<div id="fields" style="margin-top:40px"></div>
	
	<div class="jFormComponent button">
		<?php echo CHtml::submitButton(Yii::t('excel','Save')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->