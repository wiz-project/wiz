<?php
$this->breadcrumbs=array(
	'Water Requests'=>array('index'),
	$wr->project=>array('view','id'=>$wr->id),
	'EPANET'
);

/*
$this->menu=array(
	array('label'=>'Create WaterRequests', 'url'=>array('create')),
	array('label'=>'Manage WaterRequests', 'url'=>array('admin')),
);*/
?>

<h1><?php echo CHtml::link($wr->project, array('view', 'id'=>$wr->id)); ?></h1>
	<noscript>			
		<p>Please enable JavaScript to use file uploader.</p>
		<!-- or put a simple form for upload here -->
	</noscript>
	
	<?php echo $this->renderPartial('summary_view',array('model'=>$wr)); ?>
	
	<br/>
	
	<div id="epanet-file-uploader">		
		
	</div>
	
	<div id="epanet-parameters" class="form" style="display:none">		
		
		
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'epanet-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

			<div class="row">
				<?php echo $form->label($model,'filename'); ?>
				<span id="filename"></span>
			</div>

			<p class="hint">
				Setting parameters to generate a new inp file including information about this Water Requets. <br />
				You can use free text or parameterized values. Parameterized values available are:
				<ul style="color: #999;">
				<?php foreach ($replaceable_parameters as $param=>$help_text): ?>
					<li><?php echo $replaceable_parameters_marker.$param?>: <?php echo $help_text ?></li>
				<?php endforeach; ?>
				</ul>
			</p>
				<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>
			<div class="row">
				<?php echo $form->hiddenField($model,'filename'); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($model,'tag'); ?>
				<?php echo $form->textField($model,'tag'); ?>
				<?php echo $form->error($model,'tag'); ?>
			</div>		

			<div class="row">
				<?php echo $form->labelEx($model,'demand_pattern'); ?>
				<?php echo $form->textField($model,'demand_pattern'); ?>
				<?php echo $form->error($model,'demand_pattern'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'demand_categories'); ?>
				<?php echo $form->textField($model,'demand_categories'); ?>
				<?php echo $form->error($model,'demand_categories'); ?>
			</div>			

			<div class="row">
				<?php echo $form->labelEx($model,'emitter_coeff'); ?>
				<?php echo $form->textField($model,'emitter_coeff'); ?>
				<?php echo $form->error($model,'emitter_coeff'); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($model,'initial_quality'); ?>
				<?php echo $form->textField($model,'initial_quality'); ?>
				<?php echo $form->error($model,'initial_quality'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'source_quality'); ?>
				<?php echo $form->textField($model,'source_quality'); ?>
				<?php echo $form->error($model,'source_quality'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'srid');  ?>
				<?php echo $form->dropDownList(
												$model,
												'srid',
												Yii::app()->user->srids ,
												array(
			                                          'onChange'=>'sridchanged(this);',
			                                          )
			                                   /*, array('options' => array('3003'=>array('selected'=>true)))*/
											); ?>
				<?php echo $form->error($model,'srid'); ?>
			</div>
			
			<div class="row" id="other_srid" style="display:none">
				<?php echo $form->labelEx($model,'other_srid'); ?>
				<?php echo $form->textField($model,'other_srid'); ?>
				<?php echo $form->error($model,'other_srid'); ?>
			</div>
			
			<div class="row buttons">
				<?php echo CHtml::submitButton('Generate .inp file'); ?>
			</div>
			
		
		<?php $this->endWidget(); ?>

	</div>

    <script>        
    /* <![CDATA[ */
        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('epanet-file-uploader'),
                action: <?php echo "'".CController::createUrl('waterRequests/epanetFileUpload')."'" ?> ,
                debug: false,
                template: '<div class="qq-uploader">' + 
                			'<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                			'<div class="qq-upload-button">Upload inp file</div>' +
                			'<ul class="qq-upload-list"></ul>' + 
             				'</div>',
                onComplete: function(id, fileName, responseJSON){
                	if (('success' in responseJSON)&&(responseJSON['success']==true)) {
 						$('div#epanet-file-uploader').hide();
                		$('div#epanet-parameters span#filename').text(fileName);
                		$('input#EpanetForm_filename').val(fileName);
                		$('div#epanet-parameters').show();
					}
                },
            });
        }
        function sridchanged(sel) {
            var value = sel.options[sel.selectedIndex].value;  
            if(value == "other")
            	$('div#other_srid').show();
            else
            	$('div#other_srid').hide();
        }
        window.onload = createUploader;     
    /* ]]> */
    </script>
    