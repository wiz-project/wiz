<?php
$this->layout="column1";

$this->breadcrumbs=array(
	'Water Requests'=>array('index'),
	$wr->project=>array('view','id'=>$wr->id),
	'Status Transition'
);

?>

<h1><?php echo $wr->project ?></h1>
	
	<?php echo $this->renderPartial('summary_view',array('model'=>$wr)); ?>
	
	<br/>
	
	
	<div class="form">		
		
		
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'water-requests-history-form',
		)); ?>
		
		<?php if ($action=='approve'): ?>
			<div class="row">
				<?php echo $form->labelEx($wr,'cost'); ?>
				<?php echo $form->textField($wr,'cost'); ?>
				<?php echo $form->error($wr,'cost'); ?>
			</div>
		<?php endif; // approve ?>
		
		<?php if ($action=='completed'): ?>
			<div class="row">
				<?php echo $form->labelEx($wr,'effective_water_demand'); ?>
				<?php echo $form->textField($wr,'effective_water_demand'); ?>
				<?php echo $form->error($wr,'effective_water_demand'); ?>
			</div>
		<?php endif; // completed ?>
		
			<div class="row">
				<?php echo $form->labelEx($model,'comment'); ?>
				<?php echo $form->textArea($model,'comment',array('rows'=>10, 'cols'=>100)); ?>
				<?php echo $form->error($model,'comment'); ?>
			</div>
		
		<?php if ($action=='approve'): ?>
			<noscript>			
				<p>Please enable JavaScript to use file uploader.</p>
			</noscript>
			
			<br/>
			
			<div id="zip-file-uploader">		
				
			</div>


			<div class="row" id="zip-row">
				<?php echo $form->labelEx($wr,'file_link'); ?>
				<?php echo $form->textField($wr,'file_link',array('id'=>'zip-filename','readonly'=>'readonly')); ?>
				<?php echo $form->error($wr,'file_link'); ?>
			</div>
			<?php endif; // approve ?>
			
			<br/>
			
			<div class="row status_buttons">
				<?php
					foreach(SWHelper::nextStatuslistData($wr,false) as $k=>$v) {
						if ($v===$action) {
							echo CHtml::submitButton(Yii::t('waterrequest', ucfirst($v)), array('id'=>$v.'-button','name'=>$v.'-button'));
							break;
						}
					}
				?>
			</div>
		
		<?php $this->endWidget(); ?>

	</div>
<?php if ($action=='approve'): ?>
	
	<script>        
        function createZipUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('zip-file-uploader'),
                action: <?php echo "'".CController::createUrl('waterRequests/zipFileUpload')."'" ?> ,
                debug: false,
                template: '<div class="qq-uploader">' + 
                			'<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                			'<div class="qq-upload-button">Upload zip file</div>' +
                			'<ul class="qq-upload-list"></ul>' + 
             				'</div>',
                onComplete: function(id, fileName, responseJSON){
                	if (('success' in responseJSON)&&(responseJSON['success']==true)) {
 						$('div#zip-file-uploader').hide();
 						//$('#zip-filename').text(fileName);
 						if (('filename' in responseJSON))
 							$('#zip-filename').val(responseJSON['filename']);
 						else
 	 						$('#zip-filename').val(fileName);
                		$('#zip-row').show();
                		//$('input#EpanetForm_filename').val(fileName);
                		//$('div#epanet-parameters').show();
					}
                	/*
                	alert(id);
                	alert(fileName);
                	alert(responseJSON['error']);
                	*/
                },
            });
            $('#zip-row').hide();
        }
        
        window.onload = createZipUploader;     
    </script>
    
<?php endif; // approve ?>
