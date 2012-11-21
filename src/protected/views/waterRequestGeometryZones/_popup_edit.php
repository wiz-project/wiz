<?php 
		echo '<h2>'.Yii::t('waterrequest', 'Edit Zone').'</h2>';
?>
<div class="form">

	<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'water-request-geometry-zones-form',
			'action'=>CController::createUrl('waterRequestGeometryZones/update',array('id'=>$model->id)),
			//'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'clientOptions'=>array('validateOnSubmit'=>true)));
	?>
	
	<?php echo $form->errorSummary($model);	?>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_name',array('for'=>'WaterRequestGeometryZones_zone_name')); ?>
		<?php
			echo $form->dropDownList(
                    $model,
                    'zone_name', 
                    CHtml::listData(Zones::model()->findAll('active=:active',array(':active'=>true)),
                    	'name', 
                    	'description'), 
					array('empty'=>Yii::t('waterrequest', 'Select Category'),
							'onChange'=>'javascript:refresh_zone_parameters_form();',
							'id'=>'WaterRequestGeometryZones_zone',
							'class'=>'selector')
					);
		?>
		<?php echo $form->error($model,'zone_name'); ?>
	</div>
	<br/>
	<br/>
	<div id="zone_parameter">
	<?php 
		$prop_model = new WaterRequestGeometryZoneProperties;
		
		$zone = $model->zone_name;
		//Yii::log(print_r($zone, true),CLogger::LEVEL_INFO,'_popup_edit');
		while ($zone!=null) {
			$params = ZonesWaterRequestParameters::model()->active_parameters()->findAll('zone=:zone',array(':zone'=>$zone));
			if ($params)
				break;
			$zone = Zones::parentZone($zone);
		}
		
		echo $this->renderPartial('//waterRequestGeometryZoneProperties/_properties_edit', array('model'=>$prop_model, 'params'=>$params, 'local'=>$model->properties()));
	?>
	</div>

	<div class="row" id="water_demand">
		<?php echo CHtml::label(Yii::t('waterrequest', 'Water Demand'),'water_demand_value'); ?>
		<?php echo CHtml::textField('water_demand_value','',array('disabled'=>true,'class'=>'text')); ?>
		<?php
			echo CHtml::Link(
				Yii::t('waterrequest', 'Calculate'),
				'javascript:void(0);',
				array('onclick'=>'simula();')
		    );
		?>
		<p class='hint' id='water_demand_info'></p>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::ajaxSubmitButton(
			'Save',
			CController::createUrl('waterRequestGeometryZones/update',array('id'=>$model->id)),
			array('success'=>'function(){new_refresh_geometries_table();doOverlayClose($(\'#'.$form->id.'\'));}'),
			array('id'=>'submitta')
	    	);
		?>
	</div>

<script type="text/javascript">
/* <![CDATA[ */
	//jQuery('#water_demand').hide();
	
	function refresh_zone_parameters_form(){
		var zone_type = $('#WaterRequestGeometryZones_zone').find(':selected').val();
		//var zone_type = document.getElementById('WaterRequestGeometryZones_zone').options[document.getElementById('WaterRequestGeometryZones_zone').selectedIndex].value;
		jQuery.ajax(
				{
					'url':'<?php echo CController::createUrl('waterRequestGeometryZoneProperties/showPropsForm');?>&zone_type='+zone_type, //TODO: sistemare URL
					'cache':false,
					'success':function(html){
						jQuery("#zone_parameter").html(html);
						jQuery('#water_demand').show();
						}
				});
		return true;
	}
		
	function simula(){
	
		var my_form = document.getElementById('water-request-geometry-zones-form');
		//var my_form = $('#water-request-geometry-zones-form');
		//var zone = my_form.elements['WaterRequestGeometryZones[zone]'].value;
		var zone = $('#WaterRequestGeometryZones_zone').val(); 
		//var selected_parameter = getCheckedValue(my_form.elements['WaterRequestGeometryZones[ae_choice]']);
		var selected_parameter = $("#water-request-geometry-zones-form input[type='radio']:checked").val();
		
		if(selected_parameter==="") {
				return true;
		}
		
		//var parameter = my_form.elements['WaterRequestGeometryZoneProperties[parameter]'].value;
		//var value = my_form.elements['WaterRequestGeometryZoneProperties[value]'].value;
		var parameter = selected_parameter;
		var value = my_form.elements['WaterRequestGeometryZoneProperties['+selected_parameter+']'].value;
		var geom = <?php echo $model->wr_geometry_id; ?>;
		<?php	$url = CJSON::encode(CController::createUrl('waterRequestGeometryZones/simulateWD',array('zone'=>'-zone','parameter'=>'-parameter','value'=>'-value','geom'=>'-geom'))); ?>
		var url = <?php echo $url; ?>;		
		var _url = url.replace('-zone', zone);
		_url = _url.replace('-parameter', parameter);
		_url = _url.replace('-value', value);
		_url = _url.replace('-geom', geom);
		jQuery.ajax({
					/*'url':'/acque/index.php?r=waterRequestGeometryZones/simulateWD&zone='+zone+'&parameter='+parameter+'&value='+value+'&geom_id='+geom_id,*/
					url: _url,
					cache:false,
					dataType: 'json',
					success:function(json){
						jQuery('#water_demand_value').val(json.water_demand);
						jQuery('#water_demand_value').removeClass('wd_notice wd_ko wd_ok');
						var text='';
						text = json.city + ': ' + json.maximum_water_supply+'. ';
						if (json.margin == 0) {
							jQuery('#water_demand_value').addClass('wd_notice');
							text = text + '<?php  echo Yii::t('waterrequest', 'You are close to the margin.');?>';
						}
						else if (json.margin < 0) {
							jQuery('#water_demand_value').addClass('wd_ko');
							text = text + '<?php  echo Yii::t('waterrequest', 'You have exceeded the margin.');?>';
						}
						else
							jQuery('#water_demand_value').addClass('wd_ok');
						jQuery('#water_demand_info').html(text);
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