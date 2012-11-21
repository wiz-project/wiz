<?php
	$cs=Yii::app()->clientScript;
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/OpenLayers/OpenLayers.js', CClientScript::POS_HEAD);
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/proj4js-combined.js', CClientScript::POS_HEAD);
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG3003.js', CClientScript::POS_HEAD);
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG32232.js', CClientScript::POS_HEAD);
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/mylayerswitcher.js', CClientScript::POS_HEAD);
	$edit=isset($edit)?$edit:false;
	
	function multiarray_keys($ar) {   
		//$keys = array();
		foreach($ar as $k => $v) {
			$keys[] = $k;
			if (is_array($ar[$k]))
				$keys = array_merge($keys, multiarray_keys($ar[$k]));
		}
		return $keys;
	} 
?>

	<script type="text/javascript">
    var map;
	var gmap;
	var markers_q;
	var markers_f;
	var point;
	var markers;
	var select,vectorLayer;
	<?php if(!$edit) { ?>
		var json_q = <?php echo json_encode($qualities_property); ?>;
		var json_f = <?php echo json_encode($faults_property); ?>;
	<?php } ?>

	OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
	OpenLayers.Util.onImageLoadErrorColor = "transparent";

	function init(){
		OpenLayers.ProxyHost = "proxy.php?url=";
        var monte_mario = new OpenLayers.Projection("EPSG:3003");
		var wgs84 = new OpenLayers.Projection("EPSG:4326");
		var options = {
				controls:[],
				projection: new OpenLayers.Projection("EPSG:900913"),
				displayProjection: wgs84,
				units: "m",
				maxResolution: 156543.0339,
				maxExtent: new OpenLayers.Bounds(1554671, 4685424, 1771561, 4924891).transform(monte_mario, new OpenLayers.Projection("EPSG:900913")),
				numZoomLevels: 22 ,
                controls:  [ new OpenLayers.Control.Navigation()     // abilita eventi del mouse
                ,            new OpenLayers.Control.MousePosition()     // posizione del mouse
                ,            new OpenLayers.Control.PanZoom()     // frecce blu e slide dello zoom
                ,            new OpenLayers.Control.ArgParser()      // url parser
                ,            new OpenLayers.Control.Scale() // visualizza scala attuale
                          ]
		};
		
		$('#map').height('700px');
		map = new OpenLayers.Map('map', options);
		gmap = new OpenLayers.Layer.OSM("OpenStreetMap");
		map.addLayers([gmap]);
		map.addControl(new OpenLayers.Control.LayerSwitcher());
        map.addControl(new OpenLayers.Control.MousePosition());
		
<?php if(!$edit): ?>
		function dateConvert(date) {
			var dates = date.substr(0,10).split("-");
			var times = date.substr(11,5);
			
			return dates[2]+"/"+dates[1]+"/"+dates[0]+" "+times;
		}		

		function onPopupClose(evt) {
            select.unselectAll();
        }

		function onFeatureSelect(event) {
			var feature = event.feature;
            var selectedFeature = feature;
			
			var popup = null;
			if(feature.attributes.quality) {
				popup = new OpenLayers.Popup.FramedCloud("marker_popup", 
					feature.geometry.getBounds().getCenterLonLat(),
					new OpenLayers.Size(100,100),
					"<h4>QUALITY INFO</h4>" + "<table cellspacing=0 cellpadding=0 style='margin-bottom:0px'><tr><td><b><?php echo Yii::t('citizen','Date & Time'); ?>: </b></td><td>"+dateConvert(feature.attributes.timestamp)+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Quality'); ?>: </b></td><td>"+json_q[feature.attributes.quality].quality + "</td></tr><tr><td><b><?php echo Yii::t('citizen','Geometry'); ?>: </b></td><td>"+feature.geometry+"</td></tr></table>",
					null, true, onPopupClose
				);
			} else {
				var content = "<h4>FAULT INFO</h4>" + "<table cellspacing=0 cellpadding=0 style='margin-bottom:0px'><tr><td><b><?php echo Yii::t('citizen','Date & Time'); ?>: </b></td><td>"+dateConvert(feature.attributes.timestamp)+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Fault'); ?>: </b></td><td>"+json_f[feature.attributes.fault].fault+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Geometry'); ?>: </b></td><td>"+feature.geometry+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Color'); ?>: </b></td><td><div id='rgb-color' style='background-color:"+json_f[feature.attributes.fault].color+"'></div></td></tr></table>";
				if(json_f[feature.attributes.fault].image)
					var content = "<h4>FAULT INFO</h4>" + "<table cellspacing=0 cellpadding=0 style='margin-bottom:0px'><tr><td><b><?php echo Yii::t('citizen','Date & Time'); ?>: </b></td><td>"+dateConvert(feature.attributes.timestamp)+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Fault'); ?>: </b></td><td>"+json_f[feature.attributes.fault].fault+"</td></tr><tr><td><b><?php echo Yii::t('citizen','Geometry'); ?>: </b></td><td>"+feature.geometry+"</td></tr></table>";
				popup = new OpenLayers.Popup.FramedCloud("marker_popup", 
					feature.geometry.getBounds().getCenterLonLat(),
					new OpenLayers.Size(100,100),
					content,
					null, true, onPopupClose
				);
			}
            feature.popup = popup;
            map.addPopup(popup);
        }
		
		function onFeatureUnselect(event) {
            var feature = event.feature;
            if(feature.popup) {
                map.removePopup(feature.popup);
                feature.popup.destroy();
                delete feature.popup;
            }
		}
		
		//quality layer
		var myStyles_q = new OpenLayers.StyleMap({
            "default": new OpenLayers.Style({
                pointRadius: 5,
                strokeColor: '#00008b',
                strokeWidth: 1,
                graphicZIndex: 1,
				graphicName: "triangle",
				cursor:"pointer",
				//rotation:"180"
            }),
            "select": new OpenLayers.Style({
                pointRadius: 7, 
                fillColor: '#00008b',
                strokeColor: '#00008b',
                graphicZIndex: 3
            })
        });
		
		var exist_q = false;
		for (var key in json_q) {
			exist_q = true;
			if(json_q[key].color) {
				myStyles_q.styles['default'].addRules([
					new OpenLayers.Rule({
						filter: new OpenLayers.Filter.Comparison({
							type: OpenLayers.Filter.Comparison.EQUAL_TO, property: "quality", value: key
						}),
						symbolizer: { fillColor: json_q[key].color }
					})
				]);
			} else {
				myStyles_q.styles['default'].addRules([
					new OpenLayers.Rule({
						filter: new OpenLayers.Filter.Comparison({
							type: OpenLayers.Filter.Comparison.EQUAL_TO, property: "quality", value: key
						}),
						symbolizer: { externalGraphic: json_q[key].image, graphicWidth: 24, graphicHeight: 29 }
					})
				]);
			}
		}
		
		if(exist_q) {
			markers_q = new OpenLayers.Layer.WFS(
				"<?php echo Yii::t('citizen','Qualities'); ?>",
                "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wfs']; ?>",
				{typename: 'acque:water_quality_opinions' },
				{
					typename: 'water_quality_opinions', 
					featureNS: 'http://www.acque.net/', 
					projection: wgs84,
					styleMap: myStyles_q,
					extractAttributes: true
				}
			);
			map.addLayers([markers_q]);
		}
		//end quality layer
		
		//fault layer
		var myStyles_f = new OpenLayers.StyleMap({
            "default": new OpenLayers.Style({
                externalGraphic: "images/fault_icon.png",
				graphicWidth:20,
				graphicHeight:20,
				graphicOpacity:1,
				cursor:"pointer",
            }),
            "select": new OpenLayers.Style({
                pointRadius: 7, 
                fillColor: '#00008b',
                strokeColor: '#00008b',
                graphicZIndex: 3
            })
        });

		var exist_f = false;
		for (var key in json_f) {
			exist_f = true;
			if(json_q[key].color) {
				myStyles_f.styles['default'].addRules([
					new OpenLayers.Rule({
						filter: new OpenLayers.Filter.Comparison({
							type: OpenLayers.Filter.Comparison.EQUAL_TO, property: "fault", value: key
						}),
						symbolizer: { fillColor: json_f[key].color }
					})
				]);
			} else {
				myStyles_f.styles['default'].addRules([
					new OpenLayers.Rule({
						filter: new OpenLayers.Filter.Comparison({
							type: OpenLayers.Filter.Comparison.EQUAL_TO, property: "fault", value: key
						}),
						symbolizer: { externalGraphic: json_f[key].image, graphicWidth: 24, graphicHeight: 29 }
					})
				]);
			}
		}
		
		if(exist_f) {
			markers_f = new OpenLayers.Layer.WFS(
				"<?php echo Yii::t('citizen','Faults'); ?>",
                "<?php echo 'http://'.Yii::app()->params['geoserver']['ip'].':'.Yii::app()->params['geoserver']['port'].Yii::app()->params['geoserver']['path'].Yii::app()->params['geoserver']['wfs']; ?>",
				{typename: 'acque:water_fault_opinions'},
				{
					typename: 'water_fault_opinions', 
					featureNS: 'http://www.acque.net/', 
					projection: wgs84,
					styleMap: myStyles_f,
					extractAttributes: true
				}
			);
			map.addLayers([markers_f]);
		}
		//end fault layer
		
		if(exist_q && exist_f)
			select = new OpenLayers.Control.SelectFeature([markers_q, markers_f],{toggle: true});
		else {
			if(exist_q)
				select = new OpenLayers.Control.SelectFeature(markers_q,{toggle: true});
			else
				select = new OpenLayers.Control.SelectFeature(markers_f,{toggle: true});
		}
		if(exist_q) {
			markers_q.events.on({
					"featureselected": onFeatureSelect,
					"featureunselected": onFeatureUnselect 
			});
		}
		if(exist_f) {
			markers_f.events.on({
					"featureselected": onFeatureSelect,
					"featureunselected": onFeatureUnselect 
			});
		}
		map.addControl(select);
		select.activate();
		
<?php endif ?>

		//centers the map to open
        map.zoomToExtent(
            new OpenLayers.Bounds(10.00000,42.70899,13.35111,44.07407).transform(map.displayProjection, map.projection)
        );
		
		vectorLayer = new OpenLayers.Layer.Vector("<?php echo Yii::t('citizen','Search result'); ?>",{visibility: false});
        pointstyle  = {externalGraphic:"./images/arrow.png", graphicHeight: 22, graphicWidth: 25, graphicXOffset: -25, graphicYOffset: -21};
        Point = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point(0,0),null,pointstyle);
        vectorLayer.addFeatures([Point]);
        map.addLayer(vectorLayer);
		
<?php if($edit): ?>
		//creates a new marker on mouse click
		markers = new OpenLayers.Layer.Markers("<?php echo Yii::t('citizen','Markers'); ?>");
		map.addLayer(markers);
		map.events.register("click", map , function(e) {
			var coordinates = map.getLonLatFromViewPortPx(e.xy);
			point = new OpenLayers.Geometry.Point(coordinates.lon, coordinates.lat);
			var size = new OpenLayers.Size(16,16);
			var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
			var icon = new OpenLayers.Icon('<?php echo Yii::app()->request->baseUrl.'/images/marker.png'; ?>',size,offset);
			marker = new OpenLayers.Marker(coordinates,icon);
			markers.clearMarkers();
			markers.addMarker(marker);
			edit_lot_coords(point);
		});
<?php endif ?>		
	}
	
	function jumptolonlat(lon,lat){
	   if(!vectorLayer.getVisibility()){vectorLayer.setVisibility(true)}
       var LonLat = new OpenLayers.LonLat(lon,lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
       map.setCenter(LonLat,16); 
       Point.move(LonLat);
       return false;
      }
	
	function fragmapquest(){
		// ************ change your country code for language localization
		var lang='<?php echo Yii::app()->params['language']; ?>';
		url="mapquestjs.php?q="+document.getElementById("query").value+"&limit=6"+"&lang="+lang;
		var http = new XMLHttpRequest();
		http.open("GET",url,false);
		http.send(null);
		zeile=http.responseText.split("\n");
	   
		$("#resultTip").remove();
	   
		resultdiv = $("#result");
		resultdiv.append('<div id="resultTip"><div class="resultTipArrow"></div><div class="resultTipContent">');
	   
		if(zeile.length<=1){
			$("#result .resultTipContent").append('<p style="color:#00008b"><?php echo Yii::t('citizen','No search results founds'); ?></p>');
		}else{
			i=0;
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
	
		if($("#fault_legend").length > 0) {
			var f_legend_top = $("#quality_legend").height() + 152;
			$("#fault_legend").css('margin-top',f_legend_top+'px');
		}
    });
    </script>
 
	<div style="width: 99%;border: 1px solid #ccc; overflow: hidden; padding: 4px;background-color:#EAF2F5; margin-bottom:4px">
		<form id="search_form" action="" method="GET" onsubmit="fragmapquest(); return false;">
			<div style="margin-top:0px;z-index:1000;width:100%;padding-top:6px;padding-bottom:6px; padding-left:6px">
				<label for="query"><b><?php echo Yii::t('citizen','Search for address:'); ?></b></label> 
				<input type="text" id="query" name="q" size="103">
				<input type="image" id="search" src="images/search.png" style="position:absolute; margin-left:5px; cursor:pointer" alt="<?php echo Yii::t('citizen','Search address'); ?>" title="<?php echo Yii::t('citizen','Search address'); ?>"/>
			</div>
			<div id="result" style="position:absolute;margin-left:120px;z-index:1000;margin-top:0px"></div>
		</form>
	</div>
 
    <div id="map">
		<div id="resize_map"></div>
		
		<?php if(!$edit) { ?>
			<div id="quality_legend" <?php if(in_array('image',multiarray_keys($qualities_property))) { echo "style=\"margin-left:10px\""; } ?>>
				<?php while(list($key,$value)=each($qualities_property)) { 
						if(isset($qualities_property[$key]['color'])) { ?>		
							<div id="q_legend" class="quality_opinion" style="border-top:10px solid <?php echo $qualities_property[$key]['color']; ?>" alt="<?php echo strtoupper($qualities_property[$key]['quality']); ?>" title="<?php echo strtoupper($qualities_property[$key]['quality']); ?>"></div>
				<?php 	} else { ?>
							<div id="q_legend" style="background:url(<?php echo $qualities_property[$key]['image']; ?>);width:32px;height:37px" alt="<?php echo strtoupper($qualities_property[$key]['quality']); ?>" title="<?php echo strtoupper($qualities_property[$key]['quality']); ?>"></div>
				<?php   }
					  } ?>
			</div>
			<?php if(in_array('image',multiarray_keys($faults_property))) { ?>
				<div id="fault_legend">
				<?php while(list($key,$value)=each($faults_property)) { ?>
						<div id="f_legend" style="background:url(<?php echo $faults_property[$key]['image']; ?>);width:32px;height:37px" alt="<?php echo strtoupper($faults_property[$key]['fault']); ?>" title="<?php echo strtoupper($faults_property[$key]['fault']); ?>"></div>
				<?php } ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>

    <script type="text/javascript">
    	init();
	</script>   
