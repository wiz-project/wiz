<?php 

$this->widget('zii.widgets.CMenu',array(
	'id'=>'waterRequests-create-operation',
	'items'=>array(
		array('label'=>'','url'=>'','itemOptions'=>array('id'=>'drawing-map'), 'linkOptions'=>array('alt'=>'Disegna sulla mappa','title'=>'Disegna sulla mappa')),
		array('label'=>'','url'=>array('waterRequests/upload','id'=>$model->id),'itemOptions'=>array('id'=>'shape-upload'), 'linkOptions'=>array('alt'=>'Carica uno shape','title'=>'Carica uno shape')),
	),
)); ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'water-requests-form',
	'action'=>CController::createUrl('waterRequests/update',array('id'=>$model->id)),
	'enableAjaxValidation'=>false,
));

$edit=$model->isEditable();
$geom_already_exist = false;
?>

	<p class="note"><?php  echo Yii::t('form', 'Fields with ');?><span class="required">*</span><?php  echo Yii::t('form', ' are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo CHtml::encode($model->id); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
			if ($model->status == WaterRequests::SW_NODE(WaterRequests::TEMP_STATUS))
				echo CHtml::encode($model->statusHR);
			else
				echo $model->statusIcon;
		?>
	</div>	
		
	<div class="row">
		<?php echo $form->labelEx($model,'phase'); ?>
		<?php echo CHtml::encode($model->phaseHR); ?>
	</div>
	
	<div class="row">
		<?php
			if (/*($model->phase==2) AND*/(isset($model->parent_phase))) {
				echo $form->labelEx($model,'parent');
				echo CHtml::link($model->parent_wr->id.' - '.$model->parent_wr->project,array('waterRequests/view','id'=>$model->parent_wr->id),array('target'=>'_blank'));
			}
		?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project'); ?>
		<?php echo $form->textField($model,'project',array('size'=>60,'maxlength'=>500, 'disabled'=>!$edit)); ?>
		<?php echo $form->error($model,'project'); ?>
	</div>

	<?php if ($model->phase==2): ?>
		<div class="row">
			<?php echo $form->labelEx($model,'expiration_date'); ?>
			<?php //echo $form->textField($model,'expiration_date',array('disabled'=>!$edit)); ?>
			<?php $this->widget('CJuiDateTimePicker',array(
		                'language'=>Yii::app()->params['language'],
		                'model'=>$model,                                
		                'attribute'=>'expiration_date', 
		                'mode'=>'date',                     
		                'options'=>array(),                     
		                'htmlOptions'=>array('size'=>6,'disabled'=>!$edit)
	        	  ));                             
	        ?> 
			<?php echo $form->error($model,'expiration_date'); ?>
		</div>
	<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'disabled'=>!$edit)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50, 'disabled'=>!$edit)); ?>
		<?php //echo $form->textField($model,'note',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
		
	<div class="row">
		<?php
			if (/*($model->phase==2) AND */(isset($model->parent_phase))) {
				echo $form->labelEx($model,'parent_water_demand_usage');
				$this->renderPartial('parent_percent', array('model'=>$model));
			}
		?>
	</div>
	<br/>

	<div class="row">
		<?php echo $form->labelEx($model,'rounded_water_demand'); ?>
		<?php echo $form->textField($model,'rounded_water_demand',array( 'disabled'=>'disabled'));  // the textfield is sent as input of the form if enabled
			  //echo CHtml::encode($model->rounded_water_demand); ?> l/s
		<?php echo $form->error($model,'rounded_water_demand'); ?>
	</div>
	<br/>
	
	<?php echo $form->labelEx($model,'geometries'); ?>
	<div id="geometry_list">
		<?php
			//if ($model->phase==2) 
			//	echo $this->renderPartial('//waterRequestGeometries/_geometry_list', array('model'=>$model,'view'=>!$edit));
			//else
				echo $this->renderPartial('//waterRequestGeometries/_geometry_list_detailed', array('model'=>$model,'view'=>!$edit));
			?>
	</div>

	<div class="row status_buttons">
		<?php	foreach(SWHelper::nextStatuslistData($model,false) as $k=>$v) {
					echo CHtml::submitButton(Yii::t('waterrequest', ucfirst($v)), array('id'=>$v.'-button','name'=>$v.'-button'));
		 		}		 		
		?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php if($edit) { ?>
<div class="bgCover">&nbsp;</div>
<div class="overlayBox" id="myoverlayBox">
	<!--the close button-->
	<a href="javascript:void(0);" class="closeLink"><?php  echo Yii::t('waterrequest', 'Close');?></a>
	<div class="overlayContent" id="mycontent">
		<!-- content-->
	</div>
	<span style="float:right" id="supspan">
	<?php  echo Yii::t('waterrequest', 'Estimated SUP');?>: <span id="estimatedSUP">&nbsp;</span> m&sup2;
	</span>
	
</div>

<script type="text/javascript">
/* <![CDATA[ */

function updateEstimatedSUP(type, geom_or_id){
	if (type=='geom' || type=='update_geom') {
		//$('#add_geometry_popup').find('#estimatedSUP').html(Math.round(geom_or_id.getArea()*100)/100);
		$('#supspan').find('#estimatedSUP').html(Math.round(geom_or_id.getArea()*100)/100);
		$('#supspan').show();
	}	
}

function doOverlayOpen(type,geom_or_id) {
	if (geom_or_id) {
		var div_name = '';
		if (type=='geom' || type=='update_geom') {
			//div_name = '#add_geometry_popup';
			div_name = '#myoverlayBox';
			var url = <?php echo CJSON::encode(CController::createUrl('waterRequestGeometryZones/popup',array('wr_id'=>$model->id,'type'=>'-type','geom_or_id'=>'-geom_or_id'))); ?>;
			var _url = url.replace('-geom_or_id', geom_or_id);
			_url = _url.replace('-type', type);
			//var data = '';
			//if(type=='update_geom')
			var	data = {'geom_id': id_open }; // se è aperto ha il valore giusto, altrimenti è '' e va bene lo stesso.
				
		
			$.ajax({
				type: 'POST',
				url: _url,
				data: data,
				cache: false,
				dataType: 'html',
				beforeSend: function() {
					$('#mycontent').html('');
				},
				success: function(html) {
					//$(div_name).find('div').html(html);
					$(div_name).find('div').html(html);
					$('#inner_geom').val(geom_or_id);  // implicit cast .toString(), so is WKT.
					updateEstimatedSUP(type, geom_or_id); // actual object "geometry"
				}
			});
		}
		else {
			div_name = '#myoverlayBox';
			$('#supspan').hide();
			
			
		}

		//set status to open
		isOpen = true;
		showOverlayBox(div_name);
		$('.bgCover').css({opacity:0}).animate( {opacity:0.5, backgroundColor:'#000'} );
	}
}

function new_refresh_geometries_table(){
	jQuery.ajax({  
		'url':'<?php echo CController::createUrl('WaterRequests/showgeoms',array('id'=>$model->id)); ?>',
		'cache':false,
		'success':function(html){
			jQuery("#geometry_list").html(html);
			init();
		}
	});

	var elem = "#WaterRequests_rounded_water_demand";
	$.ajax({
		url: '<?php echo CController::createUrl('WaterRequests/getrwd',array('id'=>$model->id)); ?>',
		cache: false,
		dataType: 'json',
		beforeSend: function() {
			$(elem).addClass('spinner');
		},
		complete: function() {
			$(elem).removeClass('spinner');
		},
		success: function(ris) {
			if(typeof ris.status == 'undefined' || ris.status!= 'ok'){
				return;
			}
			if(typeof ris.rwd == 'undefined'){
				return;
			}
			else
			{
				$(elem).val(ris.rwd);
			}
		},
		error: function(response){console.log(response);}
	});

<?php
	if (/*($model->phase==2) AND */(isset($model->parent_phase))) {
?>
	$.ajax({
		url: '<?php echo CController::createUrl('WaterRequests/parentpercent',array('id'=>$model->id)); ?>',
		cache: false,
		//dataType: 'json',
		beforeSend: function() {
			$('#parent_percent').addClass('spinner');
		},
		complete: function() {
			$('#parent_percent').removeClass('spinner');
		},
		success: function(ris) {
			$('#parent_percent').html(ris);
		},
		error: function(response){console.log(response);}
	});
<?php } ?>
	// neither redraw() nor refresh() work
	geoms.setVisibility(false);
	geoms.setVisibility(true);
	return false;
}
	
/* ]]> */
</script>
<?php 

Yii::app()->clientScript->registerScriptFile(
    Yii::app()->baseUrl.'/js/newboxfunctions.js',
    CClientScript::POS_END
);

/*
Yii::app()->clientScript->registerScript('scriptId', "
	var count = 0;
	var step  = 10;
	var speed = 500;
	function progress() {
		$('#amount').text(count+'%');
		$('#progress').progressbar('option', 'value', count);
		if(count < 100) {
			count = count+step;
			setTimeout(progress, speed);
		}
	}
	progress();
", CClientScript::POS_LOAD);*/

} ?>
