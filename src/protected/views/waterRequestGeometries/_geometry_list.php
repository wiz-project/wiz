<?php
	if ($model->geometries()) {
		foreach ($model->geometries() as $geom) {
			$zones = $geom->zones();
			// Always remember $zone is defined only if isset($zones[0])
			// TODO : Usare un sistema migliore per generare la lista ((isset($zones[0]) non mi piace) 
			if(count($zones)>0)
				$zone = $zones[0];
			$ret = $geom->probable();
			$class = 'wd_ok';
			if (isset($ret['margin'])) {
				if ($ret['margin'] == 0)
					$class = 'wd_notice';
				else if ($ret['margin'] < 0)
					$class = 'wd_ko';
			}
			//Yii::log(print_r($zone->id, true), CLogger::LEVEL_INFO, 'zona - id');

			?>
			<div class="accordion" id="geom_<?php echo $geom->id;?>">
				<h6 class="<?php echo $class; ?>" id="<?php echo $geom->wr->id.'_'.$geom->id.(isset($zones[0])?'_'.$zone->id:'') ?>">
					<?php echo $geom->name; ?>
					&nbsp;&ndash;&nbsp;
					<?php echo Math::wd_round($geom->geom_water_demand).'&nbsp;'.Yii::app()->params['water_demand_unit']?>
					&nbsp;&ndash;&nbsp;
					<?php echo (isset($zones[0])?Math::pe_round($zone->pe).'&nbsp'.Yii::app()->params['pe_abbreviation']:'');?>
					&nbsp;&ndash;&nbsp;
					<?php echo (isset($zones[0])?$zone->zoneDescription:'Tipologia mancante');?>
				</h6>
				<span style="display: inline-block; text-align: right; float:right;">
					<div class="geometry_add_edit_geom">
					<?php
						if (!$view) {
							if(!isset($zones[0]))
								/*
								echo CHtml::ajaxLink(
									'&nbsp;',    
									array('waterRequestGeometryZones/popup', 'wr_id'=>$model->id,'type'=>'zone','geom_or_id'=>$geom->id),
									array(
										'success'=>'function(html) {
											//$("#add_zone_popup").find(".overlayContent").html(html);
											$("#mycontent").html(html);
											doOverlayOpen("zone","'.$geom->id.'");							
										}'
									),
									array('id'=>'add_zone_icon'.$geom->id,'alt'=>'add zone','title'=>'add zone','class'=>'add_zone_icon')
								);*/
								echo CHtml::link(
										'&nbsp;',
										'javascript:void(0);',
										array('id'=>'add_zone_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'add zone'),'class'=>'add_zone_icon', 'onclick'=>'addZone('.$model->id.',\'zone\','.$geom->id.')')
								);
							
							echo CHtml::link(
								'&nbsp;',    
								'javascript:void(0);',
								array('id'=>'edit_geom_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'edit geom'),'class'=>'edit_geom_icon', 'onclick'=>'renameGeom('.$geom->id.',"'.$geom->name.'")')
							);
							
							echo CHtml::link(
								'&nbsp;',    
								'javascript:void(0);',
								array('id'=>'delete_geom_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'delete geom'),'class'=>'delete_geom_icon', 'onclick'=>'newdeleteGeom('.$geom->id.')')
							);
							
						}
						echo CHtml::link(
								'&nbsp;',
								'javascript:void(0);',
								array('id'=>'info_geom_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'info geom'),'class'=>'info_geom_icon', 'onclick'=>'infoGeom($(this));')
						);
						echo $this->renderPartial('//waterRequestGeometries/_info_tooltip',array('model'=>$geom));
						
					?>
					</div>
					<div class="geometry_add_edit_zone">
					<?php 
					if(isset($zones[0]))
					{
						if (!$view) {
							echo CHtml::link(
									'&nbsp;',    
									'javascript:void(0);',
									array('id'=>'edit_zone_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'edit zone'),'class'=>'edit_zone_icon', 'onclick'=>'editZone('.$zone->id.')')
								);
								
							echo CHtml::link(
									'&nbsp;',    
									'javascript:void(0);',
									array('id'=>'delete_zone_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'delete zone'),'class'=>'delete_zone_icon', 'onclick'=>'newdeleteZone('.$zone->id.')')
								);
						}
						echo CHtml::link(
								'&nbsp;',
								'javascript:void(0);',
								array('id'=>'info_zone_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'info zone'),'class'=>'info_zone_icon', 'onclick'=>'infoZone($(this),'.$zone->id.');')
						);
						echo $this->renderPartial('//waterRequestGeometryZones/_info_tooltip',array('model'=>$zone));
					}else
						if (!$view)
							/*
							echo CHtml::ajaxLink(
									'&nbsp;',
									array('waterRequestGeometryZones/popup', 'wr_id'=>$model->id,'type'=>'zone','geom_or_id'=>$geom->id),
									array(
											'success'=>'function(html) {
															//$("#add_zone_popup").find(".overlayContent").html(html);
															$("#mycontent").html(html);
															doOverlayOpen("zone","'.$geom->id.'");
														}'
									),
									array('id'=>'add_zone_icon'.$geom->id,'alt'=>'add zone','title'=>'add zone','class'=>'add_zone_icon')
							);
							*/
							echo CHtml::link(
									'&nbsp;',
									'javascript:void(0);',
									array('id'=>'add_zone_icon'.$geom->id,'title'=>Yii::t('waterrequest', 'add zone'),'class'=>'add_zone_icon', 'onclick'=>'addZone('.$model->id.',\'zone\','.$geom->id.')')
							);
						
					?>
					
					</div>				
					
				</span>

				<div class="wd_check <?php echo $class;?>">
					<?php echo (isset($ret['maximum_water_supply'])) ? $ret['city'].' - '.$ret['sarea'].': '.$ret['maximum_water_supply'].' '.Yii::app()->params['water_demand_unit'] : ''; ?>
					<?php echo $this->renderPartial('//waterRequestGeometries/_scenario_tooltip',array('css_class'=>$class,'scenari'=>$ret)); ?>
				</div>
				
			</div><!-- accordion -->
		<?php	
		}
	}
	else {
		if (!$view) {
			$img = CHtml::image("images/add_geom_icon.png","Add Geometry Icon");
			echo '<p class="hint">'.Yii::t('waterrequest', 'Click on ').$img.Yii::t('waterrequest', ' icon on the map to add a new geometry').'</p>';
		}
	}
?>


<script type="text/javascript">
/* <![CDATA[ */
	function infoGeom(elem) {
		$('.tooltip').hide();
		$(elem).next('.tooltip').css({
			top: $(elem).position().top ,
			left: $(elem).position().left + $(elem).width() + 5,
			display: 'block'});
	}

	function infoZone(elem,zone_id) {
		$('.info_geom_zone').remove();
		var url = <?php echo CJSON::encode(CController::createUrl('waterRequestGeometryZones/infoZone',array('zone_id'=>'-zone_id'))); ?>;
		var _url = url.replace('-zone_id', zone_id);
		$.ajax({
			url: _url,
			cache: false,
			dataType: 'html',
			beforeSend: function() {
				$(elem).addClass('spinner');
			},
			complete: function() {
				$(elem).removeClass('spinner');
			},
			success: function(html) {
				$(elem).after(html);
				$(elem).next(".tooltip").css({
					'top': $(elem).position().top + 2,
					'left': $(elem).position().left + $(elem).width() + 16,
					'display': 'block'});
			}
		});
	}

	function editZone(zone_id) {
		var url = <?php echo CJSON::encode(CController::createUrl('waterRequestGeometryZones/popupEdit',array('zone_id'=>'-zone_id'))); // ho tolto un po' di parametri, forse riserviranno ?>;
		var _url = url.replace('-zone_id', zone_id);
	
		div_name="#myoverlayBox";
				
		$.ajax({
			url: _url,
			cache: false,
			dataType: 'html',
			beforeSend: function() {
				$(div_name).find('.overlayContent').html('');
			},
			success: function(html) {
				$(div_name).find('.overlayContent').html(html);
			}
		});
		//set status to open
		isOpen = true;
		$('#supspan').hide();
		showOverlayBox(div_name);
		$('.bgCover').css({opacity:0}).animate( {opacity:0.5, backgroundColor:'#000'} );
	}
	
	function addZone(wr_id, type, geom_or_id){

		var url = <?php echo CJSON::encode(CController::createUrl('waterRequestGeometryZones/popup',array('wr_id'=>'-wr_id','type'=>'-type','geom_or_id'=>'-geom_or_id')));  ?>;
		var _url = url.replace('-wr_id', wr_id);
		_url = _url.replace('-type', type);
		_url = _url.replace('-geom_or_id', geom_or_id);
						
		$.ajax({
			url: _url,
			cache: false,
			dataType: 'html',
			beforeSend: function() {
				$("#mycontent").html('');
			},
			success: function(html) {
				$("#mycontent").html(html);
				doOverlayOpen("zone", geom_or_id);
			}
		});

	}

	
	function highlightGeometry() {
		$('div.accordion').hover(
  			function () {
  				$(this).addClass('hover');
  			},
  			function () {
    			$(this).removeClass('hover');
  			}
		);
	}
	
	function closeGeometry(id) {
		if (id) {
			$('div#geom_'+id).find('div.geometry_add_edit_zone').hide();
			$('div#geom_'+id).find('div.geometry_add_edit_geom').show();
			$('div#geom_'+id).removeClass('clicked');
			id_open='';
			panelEdit.deactivate();
		}
	}
	
	function expandGeometry(){
		var acquestyle =	{
						        fillColor: "red",
						        strokeColor: "red",
						        strokeOpacity: 1,
						        strokeWidth: 2,
							};    
		var symbolizer = OpenLayers.Feature.Vector.style["default"];
		$('div.accordion h6').click(
			function() {
				accordion = $(this).parent();
				var id = $(this).attr('id').split('_')[1];
				if(id!=id_open){
					closeGeometry(id_open);

					var styleMap = new OpenLayers.StyleMap({"default": symbolizer});
					geoms.styleMap = styleMap;
					geoms.styleMap.styles['default'].addRules([
					                           				new OpenLayers.Rule({
					                        					filter: new OpenLayers.Filter.Comparison({
					                        						type: OpenLayers.Filter.Comparison.EQUAL_TO, property: "id", value: id
					                        					}),
					                        					symbolizer: acquestyle
					                        				}),
					                        				new OpenLayers.Rule({
					                        					filter: new OpenLayers.Filter.Comparison({
					                        						type: OpenLayers.Filter.Comparison.NOT_EQUAL_TO, property: "id", value: id
					                        					}),
					                        					symbolizer: {}
					                        				})
					                        			]);
					geoms.redraw();
					
					// check if correctly loaded
					if(boxes['wr_geom'+'.'+id]==undefined){
						// not loaded, refresh
						check_boxes();
					}
					// this time this must exist, otherwise play dumb..
					if(boxes['wr_geom'+'.'+id]){
						map.zoomToExtent(boxes['wr_geom'+'.'+id]);
					}
										
					id_open = id;

					$('div#geom_'+id).find('div.geometry_add_edit_zone').show();
					$('div#geom_'+id).find('div.geometry_add_edit_geom').hide();
					accordion.addClass('clicked');

					panelEdit.activate();
					}
				else
				{

					var styleMap = new OpenLayers.StyleMap({"default": symbolizer});
					geoms.styleMap = styleMap;
					geoms.redraw();
					//ctrlSelect.unselectAll();
					closeGeometry(id_open);
				}

					
			}
		);
	}
	
	function tooltip() {
		$('.wd_check').each(function () {
            var distance = 10;
            var time = 400;
            var hideDelay = 100;

            var hideDelayTimer = null;

            var beingShown = false;
            var shown = false;
            var info = $('.tooltip', this).css('opacity', 0);
			var top = 0;
			var left = 0;

            $(this).mouseover(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                if (beingShown || shown) {
                    // don't trigger the animation again
                    return;
                } else {
                    // reset position of info box
                    beingShown = true;
					
                    info.css({
                        top: $(this).position().top + 10,
                        left: $(this).position().left + $(this).width() + 6,
                        display: 'block'
                    }).animate({
                        top: '-=' + distance + 'px',
                        opacity: 1
                    }, time, 'swing', function() {
                        beingShown = false;
                        shown = true;
                    });
                }

                return false;
            }).mouseout(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    info.animate({
                        top: '-=' + distance + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        shown = false;
                        info.css('display', 'none');
                    });

                }, hideDelay);

                return false;
            });
        });
	}

	function check_boxes() {
		// This function just adds the new fid/bounds to the existing boxes
		for (var i = 0; i < geoms.features.length; i++) {
		    var geometry = geoms.features[i].geometry;
			//console.log('i='+i+'  geoms.features[i].fid='+geoms.features[i].fid+'  geometry.getBounds()='+ geometry.getBounds());
		    boxes[geoms.features[i].fid] = geometry.getBounds();
		}
		// Non posso rimuovere i fid non presenti in geoms.features perchè potrebbero esistere ma non essere caricati in memoria 
		// boxes può essere completamente ricostruito solo quando siamo sicuri che ogni feature del layer è stata caricata

	}
	
	function init() {
		highlightGeometry();
		expandGeometry();
		tooltip();
		check_boxes();
	}
	
	var id_open='';
	window.onload = init;
/* ]]> */
</script>