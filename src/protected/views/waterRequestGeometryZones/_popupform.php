<?php 
$editing = isset($zone_model);

    if ($editing)
    {
		echo '<h2>'.Yii::t('waterrequest', 'Edit Zone').'</h2>';
		$model = $zone_model;
    }
   	else 
		if ($geom_already_exist)
			echo '<h2>'.Yii::t('waterrequest', 'Add Zone').'</h2>';
		else 
			echo '<h2>'.Yii::t('waterrequest', 'Add Geometry').'</h2>';
?>
<div class="form">

	<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'water-request-geometry-zones-form',
			'action'=>($editing)?
						CController::createUrl('waterRequestGeometryZones/update',array('id'=>$model->id)):
						CController::createUrl('waterRequestGeometryZones/create',array('id'=>$geom_model->id)),
			//'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'clientOptions'=>array('validateOnSubmit'=>true)));
	?>
	
	<?php echo $form->errorSummary($model);
		  if (isset($geom_model))
		  	echo $form->errorSummary($geom_model); 
	?>

<?php if(!$editing):?>
	<div class="row">
		<!-- hidden field used to store wr_id and geometry (from OpenLayers) -->
		<?php echo $form->hiddenField($geom_model,'wr_id'); ?>
		<!-- inner_geom can store the geometry or geometry_id -->
		<?php echo $form->hiddenField($geom_model,'geom', array('id'=>'inner_geom')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($geom_model,'name'); ?>
		<?php if ($geom_already_exist)
				echo $form->textField($geom_model,'name',array('disabled'=>true,'class'=>'text') );
		      else 
			  	echo $form->textField($geom_model,'name',array('class'=>'text') );
		?>
		<?php echo $form->error($geom_model,'name'); ?>
	</div>
<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_name',array('for'=>'WaterRequestGeometryZones_zone_name')); ?>
		<?php
			$zone_list = ($editing)?
							Zones::zonesList($model->geometry->wr->phase):
							Zones::zonesList($geom_model->wr->phase);
			echo $form->dropDownList(
                    $model,
                    'zone_name',
					$zone_list, 
					array('empty'=>Yii::t('waterrequest', 'Select Category'),
							'onChange'=>'javascript:refresh_zone_parameters_form();',
							'id'=>'WaterRequestGeometryZones_zone_name',
							'class'=>'selector')
					);
		?>
		<?php echo $form->error($model,'zone_name'); ?>
	</div>
	
<?php if(!$editing): ?>
	<div class="zone_parameter">&nbsp;</div>
<?php else: ?>
	<div class="zone_parameter">
	<?php 
		$zone = $model->zone_name;

		while ($zone!=null) {
			$params = ZonesWaterRequestParameters::model()->active_parameters()->findAll('zone=:zone',array(':zone'=>$zone));
			if ($params)
				break;
			$zone = Zones::parentZone($zone);
		}
		
		echo $this->renderPartial('//waterRequestGeometryZoneProperties/_properties_edit', array( 'params'=>$params, 'local'=>$model->properties()));
	?>
	</div>
<?php endif; ?>
	
	<div class="row buttons">
		<?php 
		//echo CHtml::submitButton(Yii::t('waterrequest', 'Save changes'))  
		
		echo CHtml::ajaxSubmitButton(
				$model->isNewRecord ? Yii::t('waterrequest', 'Add') : Yii::t('waterrequest', 'Save changes'),
				($editing)?
				CController::createUrl('waterRequestGeometryZones/update',array('id'=>$model->id)):
				CController::createUrl('waterRequestGeometryZones/create',array('id'=>$geom_model->id)),
				array(
						'dataType'=> 'json',
						'success'=>'function(response){newWRGZresponseHandler(response);}',
						'error'=>'function(response){console.log(response);}',
				),
				array('id'=>'submitta')
	    	);
		?>
	</div>

<script type="text/javascript">
/* <![CDATA[ */
	//jQuery('#water_demand').hide();
	
	function newWRGZresponseHandler(ris){
		if(typeof ris.status == 'undefined'){
			alert('Il server ha risposto in modo non valido, controllare i dati inseriti');
			return;
		}
		if(ris.status!= 'ok'){
			alert(ris.status);
		}else
		{
			new_refresh_geometries_table();
	<?php echo 'doOverlayClose($(\'#'.$form->id.'\'));' ?>
		}
	}
	
	function evento(valore) {
		//console.log($('input[value='+valore+']:radio'));
		$('input[value='+valore+']:radio').prop('checked', true);
	}
	
	function refresh_zone_parameters_form() {
		var zone_type = $('#WaterRequestGeometryZones_zone_name').find(':selected').val();
		//var zone_type = document.getElementById('WaterRequestGeometryZones_zone').options[document.getElementById('WaterRequestGeometryZones_zone').selectedIndex].value;
		jQuery.ajax(
				{
					'url':'<?php echo CController::createUrl('waterRequestGeometryZoneProperties/showPropsForm');?>&zone_type='+zone_type,
					'cache':false,
					'success':function(html){
						jQuery(".zone_parameter").html(html);
						//jQuery('#water_demand').show();
						}
				});
		return true;
	}
		
	function simula() {
	
		var my_form = document.getElementById('water-request-geometry-zones-form');
		//var my_form = $('#water-request-geometry-zones-form');
		//var zone = my_form.elements['WaterRequestGeometryZones[zone]'].value;
		var zone = $('#WaterRequestGeometryZones_zone_name').val(); 
		//var selected_parameter = getCheckedValue(my_form.elements['WaterRequestGeometryZones[ae_choice]']);
		var selected_parameter;
		var parameter;
		var value;
		var geom;
		
		try {
			selected_parameter = $("#water-request-geometry-zones-form input[type='radio']:checked").val();
			//var parameter = my_form.elements['WaterRequestGeometryZoneProperties[parameter]'].value;
			//var value = my_form.elements['WaterRequestGeometryZoneProperties[value]'].value;
			parameter = selected_parameter;
			value = my_form.elements['WaterRequestGeometryZoneProperties['+selected_parameter+']'].value;
			<?php if (!$editing): ?>
			geom = document.getElementById('inner_geom').value;
			<?php endif ?>
		}
		catch (e) {
			jQuery('#water_demand_value').val(e);
			return true;	
		}
		
		<?php
			if ($editing)
				$url = CJSON::encode(CController::createUrl('waterRequestGeometryZones/simulateWD',array('zone'=>'-zone','parameter'=>'-parameter','value'=>'-value','geom'=>$model->id)));
			else
				if ($geom_already_exist)
					$url = CJSON::encode(CController::createUrl('waterRequestGeometryZones/simulateWD',array('zone'=>'-zone','parameter'=>'-parameter','value'=>'-value','geom'=>$geom_model->id)));
				else
					$url = CJSON::encode(CController::createUrl('waterRequestGeometryZones/simulateWD',array('zone'=>'-zone','parameter'=>'-parameter','value'=>'-value','geom'=>'-geom')));
						?>
		var url = <?php echo $url; ?>;		
		var _url = url.replace('-zone', zone);
		_url = _url.replace('-parameter', parameter);
		_url = _url.replace('-value', value);
		<?php
			if (!$editing && !$geom_already_exist) : ?>
				_url = _url.replace('-geom', geom);
		<?php endif; ?>
		
		jQuery.ajax({
					/*'url':'<?php echo CController::createUrl('waterRequestGeometryZones/simulateWD');?>&zone='+zone+'&parameter='+parameter+'&value='+value+'&geom_id='+geom_id,*/
					url: _url,
					cache:false,
					dataType: 'json',
					success:function(json){
						jQuery('#water_demand_value').val(json.water_demand);
						jQuery('#water_demand_value').removeClass('wd_notice wd_ko wd_ok');
						var text='';
						text = json.city + ' - ' + '<?php  echo Yii::t('waterrequest', 'Service Area');?> ' + json.service_area + ': ' + json.maximum_water_supply+'. ';
						if (json.margin == 0) {
							jQuery('#water_demand_value').addClass('wd_notice');
							text = text + '<?php  echo Yii::t('waterrequest', 'You are close to the operational margin.');?>';
						}
						else if (json.margin < 0) {
							jQuery('#water_demand_value').addClass('wd_ko');
							text = text + '<?php  echo Yii::t('waterrequest', 'You have exceeded the operational margin!');?>';
							if (json.max_parameter)
								if (json.max_parameter.value)
									text = text + '<?php  echo Yii::t('waterrequest', ' Use ');?>'+ Math.round(json.max_parameter.value) +'<?php  echo Yii::t('waterrequest', ' as the maximum value.');?>';
						}
						else
							jQuery('#water_demand_value').addClass('wd_ok');
						jQuery('#water_demand_info').html(text);
						
						var c;
						text = '';
						for(s in json.scenari) {
							if (json.scenari[s].margin == 0)
								c='scenario_notice';
							else if (json.scenari[s].margin < 0)
								c='scenario_ko';
							else
								c='scenario_ok';
							text = text + json.scenari[s].scenario + ': ' + json.scenari[s].maximum_water_supply + '<span class="' + c + '"></span><br/>';
						}
						jQuery('#scenari').html('<div class="scenario">' + text + '</div>');
					}
				});
		return true;
		}
	// return the value of the radio button that is checked
	// return an empty string if none are checked, or
	// there are no radio buttons
	function getCheckedValue(radioObj) {
		if(!radioObj)
			return "";
		var radioLength = radioObj.length;
		if(radioLength == undefined)
			if(radioObj.checked)
				return radioObj.value;
			else
				return "";
		for(var i = 0; i < radioLength; i++) {
			if(radioObj[i].checked) {
				return radioObj[i].value;
			}
		}
		return "";
	}
/* ]]> */
</script>
<?php $this->endWidget(); ?>
</div><!-- form -->