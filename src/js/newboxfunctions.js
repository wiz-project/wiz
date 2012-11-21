/**
 * 
 */
//the status of overlay box
var isOpen = false;
var isOpen_nogeom = false;
var myisOpen= {};

// MY function to display the box
function myshowOverlayBox(id) {
	//if box is not set to open then don't do anything
	if( myisOpen.id == false ) return;
	$('#myoverlayBox').css({
		display:'block',
		left:( $(window).width() - $('#myoverlayBox').width() )/2,
		top:( $(window).height() - $('#myoverlayBox').height() )/2 -20,
		position:'absolute'
	});
	$('.bgCover').css({
		display:'block',
		width: $(window).width(),
		height:$(window).height()
	});
}
function mydoOverlayOpen(id) {
	var mycontent = document.getElementById('mycontent');
	var bucket = document.getElementById('bucket');
	var node = document.getElementById(id);
	//alert(mycontent.firstChild);
	if(mycontent.hasChildNodes()){
		bucket.appendChild(mycontent.firstChild);
		mycontent.appendChild( node );
		}
	else
		mycontent.appendChild(node);
	//set status to open
	myisOpen.id = true;
	myshowOverlayBox(id);
	$('.bgCover').css({opacity:0}).animate( {opacity:0.5, backgroundColor:'#000'} );
}
function mydoOverlayClose(id) {
	myisOpen.id = false;
	$('#myoverlayBox').css( 'display', 'none' );
	$('.bgCover').animate( {opacity:0}, null, null, function() { $(this).hide(); } );
}

function deletegeom(geom_id) {
	var spancontent = document.getElementById('span_geom');
	spancontent.innerHTML = 'Sei sicuro di voler cancellare la geometria '+geom_id+' ?'
							+'<br/>'
							+'<a href="javascript:void(0);" onclick="reallydeletegeom('+geom_id+')">Yes</a>'
							+'<a href="javascript:void(0);" onclick="mydoOverlayClose(\'deletegeom\')" style="float:right;">Cancel</a>'
							;
	mydoOverlayOpen('deletegeom');


}
function reallydeletegeom(geom_id){
	$.post("index.php?r=waterRequestGeometries/delete&id="+geom_id+"&ajax=1" );
	mydoOverlayClose('deletegeom');
	refresh_geometries_table();
}

function newdeleteGeom(geom_id){
	if(confirm('Are you sure you want to delete GEOMETRY number '+geom_id+'?'))
		$.post("index.php?r=waterRequestGeometries/delete&id="+geom_id+"&ajax=1", function(data){new_refresh_geometries_table();});
}

function renameGeom(geom_id, name){
	var new_name=prompt("Inserisci il nuovo nome della Geometria "+name,name);
	if (new_name!=null && new_name!="" && new_name!=name )
	  {
		$.post("index.php?r=waterRequestGeometries/rename&id="+geom_id+"&ajax=1",{'WaterRequestGeometries[name]':new_name}, function(data){new_refresh_geometries_table();});
	  }
	
}
// Anche quando si cancella una sola zona si ricarica tutta la lista di geometrie
function newdeleteZone(zone_id){
	if(confirm('Are you sure you want to delete zone number '+zone_id+'?'))
		$.post("index.php?r=waterRequestGeometryZones/delete&id="+zone_id+"&ajax=1", function(data){new_refresh_geometries_table();});
}

function reset_form(form){
    
	var children = document.getElementById(form).getElementsByTagName('form');
	if(children.length>0) {
		children[0].reset();
		}
	//document.getElementById(form).reset();
}

// TUTTO STO CASINO SI POTEVA EVITARE CON 
// if(!confirm('Are you sure you want to delete this item?')) return false;

//function to display the box
function showOverlayBox(div_name) {
	var pageYOffset = (window.pageYOffset || document.documentElement.scrollTop) ;

	//if box is not set to open then don't do anything
	if( isOpen == false ) return;
	
	// set the properties of the overlay box, the left and top positions		
	$(div_name).css({
		display:'block',
		left:( $(window).width() - $(div_name).width() )/2,
		top: pageYOffset+( $(window).height() - $(div_name).height() )/2 -20,
		position:'absolute'
	});
	
	
	// set the window background for the overlay. i.e the body becomes darker
	$('.bgCover').css({
		display:'block',
		width: $(window).width(),
		height:$('html').height()
	});
}

function doOverlayClose(what) {
	//detach submit button events
	$('body').off('click','#submitta');
	//set status to closed
	isOpen = false;
	$(what).parents('.overlayBox').css( 'display', 'none' );
	// now animate the background to fade out to opacity 0
	// and then hide it after the animation is complete.
	$('.bgCover').animate( {opacity:0}, null, null, function() { $(this).hide(); } );
	// reload the geoms on the map, neither redraw() nor refresh() work
	geoms.setVisibility(false);
	geoms.setVisibility(true);

}
function doOverlayCloseThis() {
	//detach submit button events
	$('body').off('click','#submitta');
	//set status to closed
	isOpen = false;
	$(this).parent().css( 'display', 'none' );
	// now animate the background to fade out to opacity 0
	// and then hide it after the animation is complete.
	$('.bgCover').animate( {opacity:0}, null, null, function() { $(this).hide(); } );
	// reload the geoms on the map, neither redraw() nor refresh() work
	geoms.setVisibility(false);
	geoms.setVisibility(true);

}

// if window is resized then reposition the overlay box
//$(window).bind('resize',showOverlayBox);
//$(window).bind('scroll',showOverlayBox);

// close it when closeLink is clicked
$('a.closeLink').click( doOverlayCloseThis );