<?php
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/OpenLayers/OpenLayers.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/proj4js-combined.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG3003.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG32232.js', CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/mylayerswitcher.js', CClientScript::POS_HEAD);


$layerslist= array(
		// identificatore => array( Nome, layer );
		'service_areas' => array('nome'=>'Aree di servizio', 'layer'=>'service_areas', 'visibility'=>'true' )
		,'fi' => array('nome'=>'Captazione da Corsi di Acqua', 'layer'=>'fi', 'visibility'=>'false' , 'group'=>'Fonti')
		,'la' => array('nome'=>'Captazione da Laghi - Serbatoi', 'layer'=>'la', 'visibility'=>'false' , 'group'=>'Fonti')
		,'po' => array('nome'=>'Captazione da Campi - Pozzi', 'layer'=>'po', 'visibility'=>'false' , 'group'=>'Fonti')
		,'so' => array('nome'=>'Captazione da Sorgenti', 'layer'=>'so', 'visibility'=>'false' , 'group'=>'Fonti')
		,'pt' => array('nome'=>'Impianti di Potabilizzazione', 'layer'=>'pt', 'visibility'=>'false' , 'group'=>'Impianti')
		,'ad' => array('nome'=>'Adduttrici', 'layer'=>'ad', 'visibility'=>'false' , 'group'=>'Impianti')
		,'ac' => array('nome'=>'Opere di Accumulo', 'layer'=>'ac', 'visibility'=>'false' , 'group'=>'Impianti')
		,'pg' => array('nome'=>'Impianti di Pompaggio', 'layer'=>'pg', 'visibility'=>'false' , 'group'=>'Impianti')
		//,'di' => array('nome'=>'Rete di Distribuzione', 'layer'=>'di')
		,'rete' => array('nome'=>'Rete di Distribuzione', 'layer'=>'rete', 'visibility'=>'false' , 'visibility'=>'false')

);

?>
    <script type="text/javascript">
    /* <![CDATA[ */
		OpenLayers.IMAGE_RELOAD_ATTEMPTS = 1;
        // make OL compute scale according to WMS spec
        OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

        // allow testing of specific renderers via "?renderer=Canvas", etc
        var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
        renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

		// map ad layers
        var map, gmap, info, water_source_tool;

        function init_map(){
			var sphericalMercator = new OpenLayers.Projection("EPSG:900913");
            var wgs84 = new OpenLayers.Projection("EPSG:4326");
            var monte_mario = new OpenLayers.Projection("EPSG:3003");
            var options = {
                projection: sphericalMercator,
                displayProjection: wgs84,
                units: "m",
                maxExtent: new OpenLayers.Bounds(1140994.62343, 5374364.43607, 1272014.63947, 5467561.27361),
                controls:  [	new OpenLayers.Control.Navigation()     // abilita eventi del mouse
                			,	new OpenLayers.Control.MousePosition()     // posizione del mouse
                			,	new OpenLayers.Control.ArgParser()      // url parser
               				,	new OpenLayers.Control.Scale() // visualizza scala attuale
                          ]
 
            };

			$('#map').height('750px');
			map = new OpenLayers.Map('map', options);
			// BACKGROUND LAYER OpenStreetmap 
 			gmap = new OpenLayers.Layer.OSM(null, null, { transitionEffect: 'resize'});
			
		    // Geoscopio come BaseLayer
			var ortofoto = new OpenLayers.Layer.WMS(
			        "Ortofoto 10k",
	                //"http://web.rete.toscana.it/sgrwms/com.rt.wms.RTmap?",
			        "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>",
	                {
			            layers: 'otf10k10',  // 3003
			            version:'1.1.0',
			            //srs:'EPSG:3003',
			            //transparent: 'true',
			            format: 'image/png'
			        },
			        { 
			        	isBaseLayer:true
			        	//,projection:monte_mario
	                    ,transitionEffect: 'resize'
                    	,resolutions: [	/*156543.03390625, 78271.516953125, 39135.7584765625,
		                				19567.87923828125, 9783.939619140625, 4891.9698095703125,
		                				2445.9849047851562, 1222.9924523925781, 611.4962261962891,
		                				305.74811309814453, 152.87405654907226, 76.43702827453613,
		                				38.218514137268066, 19.109257068634033, 9.554628534317017,*/
		                				4.202797730489227, 2.7999984880008166, 2.2399987904006537,
        		                    	1.3999992440004083, 0.6999996220002042, 0.5599996976001634,
        		                    	0.2799998488000817, 0.13999992440004086],
                		serverResolutions: [	4.202797730489227, 2.7999984880008166, 2.2399987904006537,
                		                    	1.3999992440004083, 0.6999996220002042, 0.5599996976001634,
                		                    	0.2799998488000817, 0.13999992440004086]		  
				    }
			    );
		    // Geoscopio come BaseLayer
			var ctr10 = new OpenLayers.Layer.WMS(
			        "CTR 10k",
	                //"http://web.rete.toscana.it/sgrwms/com.rt.wms.RTmap?",
			        "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>",
	                {
			        	//layers: 'rst2k_liv3',  // 3003
			        	layers: 'idrst10k',  // 3003
			            version:'1.1.0',
			            format: 'image/png'
				            
			        },
			        { 
			        	isBaseLayer:true
			        	//,projection:monte_mario
	                    ,transitionEffect: 'resize'
		                ,resolutions: [	/*156543.03390625, 78271.516953125, 39135.7584765625,
		                				19567.87923828125, 9783.939619140625, 4891.9698095703125,
		                				2445.9849047851562, 1222.9924523925781, 611.4962261962891,
		                				305.74811309814453, 152.87405654907226, 76.43702827453613,
		                				38.218514137268066, 19.109257068634033, 9.554628534317017,*/
		                				3.0827983352888992, 2.7999984880008166, 2.2399987904006537,
        		                    	1.3999992440004083, 0.6999996220002042, 0.5599996976001634,
        		                    	0.2799998488000817, 0.13999992440004086],
                		serverResolutions: [	3.0827983352888992, 2.7999984880008166, 2.2399987904006537,
                		                    	1.3999992440004083, 0.6999996220002042, 0.5599996976001634,
                		                    	0.2799998488000817, 0.13999992440004086]		                				
				    }
			    );

			    // Da qui partono gli Overlay
			var confini = new OpenLayers.Layer.WMS(
			        "Confini Comunali",
			        "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>",
			        //"/geoserver/wms",
			        {
			            layers: 'comuni_supply',  // 3003
			            //layers: 'confini_comunali',  // 3003
			            transparent: 'true',
			            format: 'image/png'
			        },
			        { opacity: .5
			         // ,displayOutsideMaxExtent:true
			        	,transitionEffect: 'resize'
					}
			    );

            map.addLayers([	gmap ,ortofoto ,ctr10 ,confini ]);

<?php foreach($layerslist as $l_id => $l) {	?>
			var layer_<?php echo $l['layer']; ?> = new OpenLayers.Layer.WMS(
					"<?php echo $l['nome'] ?>",
    				"<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>",
		    		{
				        layers: "<?php echo $l['layer'] ?>",  // 3003
				        transparent: 'true',
				        format: 'image/png'
				    },
				    { 
				    	<?php if (array_key_exists('visibility', $l))  echo 'visibility:'.$l['visibility'].',';  ?>				        
			    		id:"<?php echo $l_id ; ?>"
				    	<?php if (array_key_exists('group', $l))
				    	echo ', group:\''.$l['group'].'\'' ; 
				    	 ?>
					}
				);
			map.addLayer(layer_<?php echo $l['layer']; ?>);

<?php }   	?>
        
			// Insert macro_area sources (Fonti)
			var fonti = new OpenLayers.Layer.Vector("Fonti", {
				displayInLayerSwitcher: false,
				visibility: false,
                strategies: [new OpenLayers.Strategy.BBOX()],
                protocol: new OpenLayers.Protocol.WFS({
                    url:  "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wfs']; ?>",
                    featureType: "fonti",
                    featureNS: "http://www.acque.net/",
                   // srsName: "EPSG:3003"
                }),
                projection: monte_mario,
                /*
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style({
                        pointRadius: 16
                    }),
                    "select": new OpenLayers.Style({
                        pointRadius: 20
                    })
                })*/
                
                filter: new OpenLayers.Filter.Logical({
                    type: OpenLayers.Filter.Logical.OR,
                    filters: [
                        new OpenLayers.Filter.Comparison({
                            type: OpenLayers.Filter.Comparison.EQUAL_TO,
                            property: "CODICE_ATO",
                            value: "FI00001"
                        }),
                        new OpenLayers.Filter.Comparison({
                            type: OpenLayers.Filter.Comparison.EQUAL_TO,
                            property: "CODICE_ATO",
                            value: "LA00017"
                        })
                    ]
                })
            });
            
            map.addLayer(fonti);
            //map.setLayerIndex(fonti, 99); // bring layer on top

            var panel = new OpenLayers.Control.Panel(
                    {displayClass: 'olControlEditingToolbar'}
                );

            panel.addControls([new OpenLayers.Control.Navigation(), new OpenLayers.Control.ZoomBox()]);

            map.addControl(new OpenLayers.Control.MyCustomLayerSwitcher({title: '<?php echo Yii::t('waterrequest', 'Choose Layers'); ?>'}));  // LayerSwitcher non usa il parametro Pixel per la draw()
            map.addControl(panel, new OpenLayers.Pixel(0,10));
            map.addControl(new OpenLayers.Control.PanZoom(), new OpenLayers.Pixel(4,10));

			vectorLayer = new OpenLayers.Layer.Vector("<?php echo Yii::t('waterrequest', 'Search result'); ?>",{visibility: false});
			pointstyle  = {externalGraphic:"./images/arrow.png", graphicHeight: 22, graphicWidth: 25, graphicXOffset: -25, graphicYOffset: -21};
			Point = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point(0,0),null,pointstyle);
			vectorLayer.addFeatures([Point]);
			map.addLayer(vectorLayer);

            map.zoomToExtent(
     			 new OpenLayers.Bounds(1140994.62343, 5374364.43607, 1272014.63947, 5467561.27361)
			);
  
            // ******************** INFO WMS ****************
            var AutoSizeAnchored = OpenLayers.Class(OpenLayers.Popup.Anchored, {
		    	'autoSize': true
			});
            
    		info = new OpenLayers.Control.WMSGetFeatureInfo({
				url: '<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>', 
					    title: 'Identify features by clicking',
					    layers: [<?php $p=''; foreach($layerslist as $l_id => $l){ echo $p.'layer_'.$l['layer']; $p=',';} ?>],
					    queryVisible: true,
					    //infoFormat: 'application/vnd.ogc.gml',
					    //hover:true,  // disabilitato fino a che non cambio il format 
					    eventListeners: {
					        getfeatureinfo: function(event) {
								if((new RegExp('caption class', 'i')).test(event.text))
								{
									while(map.popups.length > 0){
										map.removePopup(map.popups[0]);
									}
						            map.addPopup(new AutoSizeAnchored(
							                "pollo", //id
							                map.getLonLatFromPixel(event.xy), //lonlat
							                null, //contentSize
							                event.text, // contentHTML
							                null, // anchor
							                true  // closeBox
							            ));
								}
					        }
					    }
			});
	    	panel.addControls([info]);
			info.activate();
			// ***********  FINE INFO WMS  ********************

			// *** water_source_tool ***
			var macro_area = new OpenLayers.Layer.WMS(
					"M",
    				"<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>",
		    		{
				        layers: "macro_area",  // 3003
				        transparent: 'true',
				        format: 'image/png'
				    }
				);
						
    		water_source_tool = new OpenLayers.Control.WMSGetFeatureInfo({
				url: '<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wms']; ?>', 
			    title: 'Show where your water comes from',
			    layers: [macro_area],
			    queryVisible: false,
			    maxFeatures: 1,
			    //infoFormat: 'application/vnd.ogc.gml',
			    //hover:true,  // disabilitato fino a che non cambio il format 
			    eventListeners: {
			        getfeatureinfo: function(event) {
						if(event.text!='')
						{
							while(map.popups.length > 0){
								map.removePopup(map.popups[0]);
							}

							var url = <?php echo CJSON::encode(CController::createUrl('macroArea/query',array('desc_macro'=>'-d_m'))); ?>;
							var _url = url.replace('-d_m', event.text);
							var testo_popup = 'pluto';
				            $.ajax({
								url: _url,
								cache: false,
								dataType: 'html',
								
								success: function(html) {
									map.addPopup(new AutoSizeAnchored(
					                "pollo", //id
					                map.getLonLatFromPixel(event.xy), //lonlat
					                null, //contentSize
					                html, // contentHTML
					                null, // anchor
					                true  // closeBox
					            	));

								}
							});
							var url2 = <?php echo CJSON::encode(CController::createUrl('macroArea/cespiti',array('desc_macro'=>'-d_m'))); ?>;
							var _url2 = url2.replace('-d_m', event.text);
							
				        	$.ajax({
				        		url: _url2,
				        		cache: false,
				        		dataType: 'json',
				        		success: function(ris) {
				        			if(typeof ris.status == 'undefined' || ris.status!= 'ok'){
				        				return;
				        			}
				        			if(typeof ris.cespiti == 'undefined'){
				        				return;
				        			}
				        			else
				        			{
				        				fonti.destroyFeatures();
				        				var filters = new Array();
				        				for(var c in ris.cespiti)
											//console.log(ris.cespiti[c]);
					        				filters.push(new OpenLayers.Filter.Comparison({
									                            type: OpenLayers.Filter.Comparison.EQUAL_TO,
									                            property: "CODICE_ATO",
									                            value: ris.cespiti[c]
									                        }))
									    
									    fonti.filter = new OpenLayers.Filter.Logical({
										                        type: OpenLayers.Filter.Logical.OR,
										                        filters: filters });
									    fonti.refresh({force: true});
									    fonti.setVisibility(true);
				        			}
				        		},
				        		error: function(response){console.log(response);}
				        	});							
						}
			        }
			    }
			});
	    	panel.addControls([water_source_tool]);
	    	
	    	// water_source_tool.activate();
			// *** FINE water_source_tool *** 			
        }
        
		function jumptolonlat(lon,lat){
		   if(!vectorLayer.getVisibility()){vectorLayer.setVisibility(true);}
		   var LonLat = new OpenLayers.LonLat(lon,lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
		   map.setCenter(LonLat,16); 
		   Point.move(LonLat);
		   return false;
		}
		
		function fragmapquest(){
			// ************ change your country code for language localisation
			var lang='<?php echo Yii::app()->params['language']; ?>', spalte, zeile, resultdiv, displaytext, i,
			url="mapquestjs.php?q="+document.getElementById("query").value+"&limit=6"+"&lang="+lang,
			http = new XMLHttpRequest();
			http.open("GET",url,false);
			http.send(null);
			zeile=http.responseText.split("\n");
		   
			$("#resultTip").remove();

			resultdiv = $("#result");
			resultdiv.append('<div id="resultTip"><div class="resultTipArrow"></div><div class="resultTipContent">');
		   
			if(zeile.length<=1){
				$("#result .resultTipContent").append('<p style="color:#00008b">No search results founds</p>');
			}else{
				for(i=0;i<zeile.length;i++){
					spalte=zeile[i].split("\t");
					if((spalte[0]*spalte[0]>0)||(spalte[1]*spalte[1]>0)){
						if(i==0){
							jumptolonlat(spalte[0],spalte[1]);
						}
						displaytext=spalte[2];
						$("#result .resultTipContent").append('<a href=# onmouseup="jumptolonlat('+spalte[0]+','+spalte[1]+');"><p>'+displaytext+'</p></a>');
						
						if(i<zeile.length-2)
							$("#result .resultTipContent").append('<br>');
					}
				}
			}
			$("#result .resultTipContent").append('</div>');
			$("#resultTip").append('</div>');
			
			$('#resultTip .resultTipArrow').click(function() {
				$("#resultTip").fadeOut('slow');
			});
			
			return false;
		}
		
		jQuery(document).ready(function() {
			jQuery('#search').click(function() {
				$('#search_form').submit();
				return false;
			});
			
			jQuery('#query').focus(function() {  
				$(this).addClass("focusField");  
			});  
			jQuery('#query').blur(function() {  
				$(this).removeClass("focusField");  
			}); 
		});
		/* ]]> */
	</script>
  
    <div style="width: 99%;border: 1px solid #ccc; overflow: hidden; padding: 4px;background-color:#EAF2F5; margin-bottom:4px">
	<form id="search_form" action="" method="GET" onsubmit="fragmapquest(); return false;">
		<div style="margin-top:0px;z-index:1000;width:100%;padding-top:6px;padding-bottom:6px; padding-left:6px">
			<label for="query"><b><?php  echo Yii::t('waterrequest', 'Search for address');?>:</b></label> 
			<input type="text" id="query" name="q" size="103">
			<input type="image" id="search" src="images/search.png" style="position:absolute; margin-left:5px; cursor:pointer" alt="Search address" title="Search address"/>
		</div>
		<div id="result" style="position:absolute;margin-left:120px;z-index:1100;margin-top:30px"></div>
	</form>
	</div>
	
    <div id="map" class="cols">
		<div id="resize_map"></div>
	</div>
  
    <script type="text/javascript">
    /* <![CDATA[ */
		init_map();		
     	/* ]]> */
	</script>    
