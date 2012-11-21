<?php // $edit=isset($edit)?$edit:$model->isEditable(); ?>
    <script type="text/javascript">
        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 5;
        // make OL compute scale according to WMS spec
        OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

        var map, gmap, geoms, ctrlSelect, drawPoligon, editPoligon;
        function init(){
            var sphericalMercator = new OpenLayers.Projection("EPSG:900913");
            var wgs84 = new OpenLayers.Projection("EPSG:4326");
            var monte_mario = new OpenLayers.Projection("EPSG:3003");
            var options = {
                projection: sphericalMercator,
                displayProjection: wgs84,
                units: "m",
                maxResolution: 156543.0339,
//                maxExtent: new OpenLayers.Bounds(10.00000,42.70899,13.35111,44.07407).transform(wgs84, new OpenLayers.Projection("EPSG:900913")),
			/* TODO: maxExtent è il massimo della toscana, possiamo evitare la transform passando i parametri gia convertiti in google. */
                maxExtent: new OpenLayers.Bounds(1554671, 4685424, 1771561, 4924891).transform(monte_mario, new OpenLayers.Projection("EPSG:900913")),
                numZoomLevels: 22 ,
                controls:  [ new OpenLayers.Control.Navigation()     // abilita eventi del mouse
                ,             new OpenLayers.Control.MousePosition()     // posizione del mouse
                ,             new OpenLayers.Control.PanZoom()     // frecce blu e slide dello zoom
                ,            new OpenLayers.Control.ArgParser()      // url parser
               // ,             new OpenLayers.Control.Attribution() // riga con note del layer
               ,             new OpenLayers.Control.Scale() // visualizza scala attuale
                          ]
 
            };


            map = new OpenLayers.Map('map', options);
/*            gmap = new OpenLayers.Layer.Google(
                        "Google Physical",
                        {type: google.maps.MapTypeId.PHYSICAL, numZoomLevels: 22}
                      );
*/
			gmap = new OpenLayers.Layer.OSM();

			confini = new OpenLayers.Layer.WMS(
		        "Confini Comunali",
		        "<?php echo 'http://'.Yii::app()->params['geoserverIP'].':'.Yii::app()->params['geoserverPort'].Yii::app()->params['geoserverPath'] ?>",
		        //"/geoserver/wms",
		        {
		            layers: 'confini_comunali',  // 3003
		            transparent: 'true',
		            format: 'image/png'
		        },
		        { opacity: .5}
		    );

			geoms = new OpenLayers.Layer.WFS(
                "Geometries",
                "<?php echo 'http://'.Yii::app()->params['geoserverIP'].':'.Yii::app()->params['geoserverPort'].Yii::app()->params['geoserverPath'] ?>",
                //"/geoserver/wfs",
                {typename: 'acque:geoms'
                <?php echo isset($model)?',viewparams: \'id:'.$model->id.'\'':'' ?>},
                {
                    typename: 'geoms', 
                    featureNS: 'http://www.acque.net/', 
                    extractAttributes: false,
                    projection:wgs84
                }
            );
             map.addLayers([gmap, confini, geoms]);

            var panel = new OpenLayers.Control.Panel(
                    {displayClass: 'olControlEditingToolbar'}
                );
            var panel2 = new OpenLayers.Control.Panel(
                    //{displayClass: 'olControlEditingToolbar'}
                );
/*            
            drawPoligon = new OpenLayers.Control.DrawFeature(
            	lotti, OpenLayers.Handler.Polygon,
                {displayClass: 'olControlDrawFeaturePolygon'}
            );
            drawPoligon.featureAdded = function(feature) {
                feature.layer.eraseFeatures([feature]);
                feature.state = OpenLayers.State.INSERT;
                feature.layer.drawFeature(feature);
                commit();
//                trigger_add();      
            };
*/

            editPoligon = new OpenLayers.Control.ModifyFeature(
            		geoms, 
                    {
                        mode: OpenLayers.Control.ModifyFeature.RESHAPE
                    	  //  |  OpenLayers.Control.ModifyFeature.ROTATE
                    	  //  |  OpenLayers.Control.ModifyFeature.RESIZE
                   		      |  OpenLayers.Control.ModifyFeature.DRAG
                   //   , createVertices:true
	                }
                );

            
            panel.addControls(
                [new OpenLayers.Control.Navigation(),/* drawPoligon,*/ editPoligon
                ,new OpenLayers.Control.Button({displayClass: 'saveButton', trigger: function() {/*alert('salvando');*/ commit();}, title: 'Save changes'})
                ]
            );

            ctrlSelect = new OpenLayers.Control.SelectFeature(geoms ,{toggle: true});
            map.addControl(ctrlSelect);
            map.addControl(panel);
            map.addControl(new OpenLayers.Control.LayerSwitcher());

            map.zoomToExtent(
                new OpenLayers.Bounds(10.00000,42.70899,13.35111,44.07407).transform(map.displayProjection, map.projection)
            );
            ctrlSelect.activate();
        }
        
        function commit(){
        
        var features = geoms.features;
        var new_feature = new OpenLayers.Feature.Vector();
        var poligoni = new OpenLayers.Geometry.MultiPolygon();
        for (var i=0; i < features.length; i++) {
        	//alert(i+ ' = '+ features[i].fid + ' ' + features[i].state);
            switch (features[i].state) {
                case OpenLayers.State.INSERT:
                  // if(features[i].fid=="geoms.<?php echo $model->id ?>")
 //                 	poligoni.addComponent(features[i].geometry);
                  //features[i].state = null; // ATTENZIONE: overhead crescente
                break;
                case OpenLayers.State.UPDATE:
                  	poligoni.addComponent(features[i].geometry.components[0]);
                  	break;
                case OpenLayers.State.DELETE:
                break;
            }
            
        } 
        if(poligoni.components.length > 0){
	        new_feature.geometry = poligoni;
	        geoms.addFeatures([new_feature]);
//          document.getElementById('Lots_coordinates').value=new_feature.geometry;
//	        document.getElementById('temp_coordinates').value=new_feature.geometry;
        	edit_lot_coords(new_feature.geometry);
        	//edit_lot_surface(new_feature.geometry.getArea());
        }else
        {
			//document.getElementById('temp_coordinates').value='MULTIPOLYGON((POLYGON EMPTY))';
        	edit_lot_coords('MULTIPOLYGON EMPTY');
		}

        //doOverlayOpen();
        return true;
        };
    </script>
  
    <div id="map"></div>
    
    <script type="text/javascript">
    	init();
	</script>    