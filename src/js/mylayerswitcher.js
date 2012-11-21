        // Layerswitcher modificato per permettere di raggruppare i layer
        OpenLayers.Control.MyCustomLayerSwitcher =
        	OpenLayers.Class(OpenLayers.Control.LayerSwitcher,{

        	    /** 
        	     * Method: toggleGroup
        	     * A group label has been clicked, toggle its corresponding content
        	     * !! WARNING: MUST have JQuery to work !!
        	     * 
        	     * Parameters:
        	     * e - {Event} 
        	     *
        		 */
             	toggleGroup: function(e){
            		$(this).next(".groupCont").slideToggle(250);
        	        if (e != null) {
        	            OpenLayers.Event.stop(e);                                            
        	        }
            	},
            	
            	/** 
        	     * Method: onGroupClick
        	     * A group checkbox has been clicked, adjust its corresponding layers visibility
        	     * 
        	     * Parameters:
        	     * e - {Event} 
        	     *
        		 */
             	onGroupClick: function(e){
             		var visibility = this.inputElem.checked;
             		var layers = this.layerSwitcher.map.layers.slice();
        	        for(var i=0, len=layers.length; i<len; i++) {
						var layer = layers[i];
						//console.log('group ='+layer.group+' vis ='+layer.visibility);
						if(layer.group == this.group)
							layer.setVisibility(!visibility);
        	        }
        	        if (e != null) {
        	            OpenLayers.Event.stop(e);                                            
        	        }
            	},
            	
           	    /** 
        	     * Method: redraw
        	     * Draw the LayerSwitcher
         	     *
        		 */
        	    redraw: function() {
         	        //if the state hasn't changed since last redraw, no need 
        	        // to do anything. Just return the existing div.
        	        if (!this.checkRedraw()) { 
        	            return this.div; 
        	        } 
        	        
        	        //clear out previous layers 
        	        this.clearLayersArray("base");
        	        this.clearLayersArray("data");
        	        
        	        var containsOverlays = false;
        	        var containsBaseLayers = false;
        	        
        	        //var groups = new Object();
        	        var groupVisible = new Object();
        	        var groupsLbls = new Object();
        	        var groupsConts = new Object();
        	            	    	        
        	        var len = this.map.layers.length;
        	        this.layerStates = new Array(len);
        	        for (var i=0; i <len; i++) {
        	            var layer = this.map.layers[i];
        	            this.layerStates[i] = {
        	                'name': layer.name, 
        	                'visibility': layer.visibility,
        	                'inRange': layer.inRange,
        	                'id': layer.id
        	            };
        	            if(layer.hasOwnProperty('group')){
        	            	/*if(!groups.hasOwnProperty(layer.group))
       	            			groups[layer.group]= [];
        	            	groups[layer.group].push(layer.name);*/
        	            	if(!groupVisible.hasOwnProperty(layer.group))
        	            		groupVisible[layer.group]= layer.visibility;
        	            	groupVisible[layer.group]= groupVisible[layer.group] || layer.visibility ;
        	            	
    						}
        	        }    
    				var layers = this.map.layers.slice();
        	        if (!this.ascending) { layers.reverse(); }
        	        for(var i=0, len=layers.length; i<len; i++) {
        	            var layer = layers[i];
        	            var baseLayer = layer.isBaseLayer;

        	            if (layer.displayInLayerSwitcher) {

        	                if (baseLayer) {
        	                    containsBaseLayers = true;
        	                } else {
        	                    containsOverlays = true;
        	                }    

        	                // only check a baselayer if it is *the* baselayer, check data
        	                //  layers if they are visible
        	                var checked = (baseLayer) ? (layer == this.map.baseLayer) : layer.getVisibility(); 	   
        	                 
        	                // create input element
        	                var inputElem = document.createElement("input");
        	                inputElem.id = this.id + "_input_" + layer.name;
        	                inputElem.name = (baseLayer) ? this.id + "_baseLayers" : layer.name;
        	                inputElem.type = (baseLayer) ? "radio" : "checkbox";
        	                inputElem.value = layer.name;
        	                inputElem.checked = checked;
        	                inputElem.defaultChecked = checked;

        	                if (!baseLayer && !layer.inRange) {
        	                    inputElem.disabled = true;
        	                }
        	                var context = {
        	                    'inputElem': inputElem,
        	                    'layer': layer,
        	                    'layerSwitcher': this
        	                };
        	                OpenLayers.Event.observe(inputElem, "mouseup", 
        	                    OpenLayers.Function.bindAsEventListener(this.onInputClick, context)
        	                );
        	                
        	                // create span
        	                var labelSpan = document.createElement("span");
        	                OpenLayers.Element.addClass(labelSpan, "labelSpan");
        	                if (!baseLayer && !layer.inRange) {
        	                    labelSpan.style.color = "gray";
        	                }
        	                labelSpan.innerHTML = layer.name;
        	                labelSpan.style.verticalAlign = (baseLayer) ? "bottom" 
        	                                                            : "baseline";
        	                OpenLayers.Event.observe(labelSpan, "click", 
        	                    OpenLayers.Function.bindAsEventListener(this.onInputClick, context)
        	                );
        	                // create line break
        	                var br = document.createElement("br");
        	    
        	                //add the layer to the correct group
        	                var groupArray = (baseLayer) ? this.baseLayers : this.dataLayers;
        	                groupArray.push({
        	                    'layer': layer,
        	                    'inputElem': inputElem,
        	                    'labelSpan': labelSpan
        	                });
        	                                                     
        	                var groupDiv = (baseLayer) ? this.baseLayersDiv : this.dataLayersDiv;

    						// Implementati i gruppi
    						if(layer.hasOwnProperty('group')){
            	            	if(!groupsLbls.hasOwnProperty(layer.group)){

            	            		var groupChkbx = document.createElement("input");
                	                groupChkbx.id = this.id + "_input_group_" + layer.group;
                	                groupChkbx.name = layer.group;
                	                groupChkbx.type = "checkbox";
                	                groupChkbx.value = layer.group;
                	                groupChkbx.checked = groupVisible[layer.group];
                	                groupChkbx.defaultChecked = false;

                	                var gcontext = {
                	                    'inputElem': groupChkbx,
                	                    'group': layer.group,
                	                    'layerSwitcher': this
                	                };
                	                OpenLayers.Event.observe(groupChkbx, "mouseup", 
                	                    OpenLayers.Function.bindAsEventListener(this.onGroupClick, gcontext)
                	                );

            	            		groupsLbls[layer.group]=  document.createElement("div");
           	         	        	OpenLayers.Element.addClass(groupsLbls[layer.group], "groupDiv");
           	         	        	//groupsLbls[layer.group].innerHTML = layer.group;
          	    	                
          	    	          		// create span
                	                var groupSpan = document.createElement("span");
                	                OpenLayers.Element.addClass(groupSpan, "labelSpan");
                	                groupSpan.innerHTML = layer.group;
                	                groupSpan.style.verticalAlign = "baseline";
                	                
            	         	        OpenLayers.Event.observe(groupSpan /*groupsLbls[layer.group]*/, "click", 
           	     	                    OpenLayers.Function.bindAsEventListener(this.toggleGroup, groupsLbls[layer.group])
           	     	                );

            	         	        groupsLbls[layer.group].appendChild(groupChkbx);
            	         	        groupsLbls[layer.group].appendChild(groupSpan);
            	         	        
           	     	                groupsConts[layer.group]=  document.createElement("div");
           	         	        	OpenLayers.Element.addClass(groupsConts[layer.group], "groupCont");

           	         	        	groupDiv.appendChild(groupsLbls[layer.group]);
           	         	        	groupDiv.appendChild(groupsConts[layer.group]);
           	         	        }
            	            	groupsConts[layer.group].appendChild(inputElem);
            	            	groupsConts[layer.group].appendChild(labelSpan);
            	            	groupsConts[layer.group].appendChild(br);
        					}
        	   	            else{
    	    	                groupDiv.appendChild(inputElem);
    	    	                groupDiv.appendChild(labelSpan);
    	    	                groupDiv.appendChild(br);
        	   	            }

        	            }
        	        }
        	        
        	        // Display only groups with visible layers
        	        for(var lg in groupVisible)
	        	        if(!groupVisible[lg])
	        	        	groupsConts[lg].style.display = "none";	        	  
        	        
        	        // if no overlays, dont display the overlay label
        	        this.dataLbl.style.display = (containsOverlays) ? "" : "none";        
        	        
        	        // if no baselayers, dont display the baselayer label
        	        this.baseLbl.style.display = (containsBaseLayers) ? "" : "none";        

        	        return this.div;
        	    },
        	CLASSNAME: "OpenLayers.Control.MyCustomLayerSwitcher"
        	});

