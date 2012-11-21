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
function showOverlayBox() {
	document.getElementById('dbg_top').innerHTML = ( $(window).height() - $('#overlayBox').height() )/2 - 50;
	document.getElementById('dbg_left').innerHTML = ( $(window).width() - $('#overlayBox').width() )/2;
	document.getElementById('dbg_width').innerHTML = $(window).width();
	document.getElementById('dbg_height').innerHTML = $(window).height();

	//if box is not set to open then don't do anything
	if( isOpen == false ) return;
	
	// set the properties of the overlay box, the left and top positions
	$('#overlayBox').css({
		display:'block',
		left:( $(window).width() - $('#overlayBox').width() )/2,
		top:( $(window).height() - $('#overlayBox').height() )/2 - 50,
		position:'absolute'
	});
	// set the window background for the overlay. i.e the body becomes darker
	$('.bgCover').css({
		display:'block',
		width: $(window).width(),
		height:$(window).height()
	});
}
function showOverlayBox_nogeom() {
	
	//if box is not set to open then don't do anything
	if( isOpen_nogeom == false ) return;
	
	// set the properties of the overlay box, the left and top positions
	$('#overlayBox_nogeom').css({
		display:'block',
		left:( $(window).width() - $('#overlayBox_nogeom').width() )/2,
		top:( $(window).height() - $('#overlayBox_nogeom').height() )/2 -20,
		position:'absolute'
	});
	// set the window background for the overlay. i.e the body becomes darker
	$('.bgCover').css({
		display:'block',
		width: $(window).width(),
		height:$(window).height()
	});
}

function doOverlayOpen() {
	//pulisce i campi non hidden 
	reset_form('overlayBox');
	//set status to open
	isOpen = true;
	showOverlayBox();
	$('.bgCover').css({opacity:0}).animate( {opacity:0.5, backgroundColor:'#000'} );
}
function doOverlayOpen_nogeom(geom_id) {
	//pulisce i campi non hidden 
	reset_form('overlayBox_nogeom');
	document.getElementById('WaterRequestGeometryZones_wr_geometry_id').value=geom_id;
	//set status to open
	isOpen_nogeom = true;
	showOverlayBox_nogeom();
	$('.bgCover').css({opacity:0}).animate( {opacity:0.5, backgroundColor:'#000'} );
}

function doOverlayClose() {
	//set status to closed
	isOpen = false;
	$('#overlayBox').css( 'display', 'none' );
	// now animate the background to fade out to opacity 0
	// and then hide it after the animation is complete.
	$('.bgCover').animate( {opacity:0}, null, null, function() { $(this).hide(); } );
}
function doOverlayClose_nogeom() {
	//set status to closed
	isOpen_nogeom = false;
	$('#overlayBox_nogeom').css( 'display', 'none' );
	// now animate the background to fade out to opacity 0
	// and then hide it after the animation is complete.
	$('.bgCover').animate( {opacity:0}, null, null, function() { $(this).hide(); } );
}
// if window is resized then reposition the overlay box
$(window).bind('resize',showOverlayBox);
$(window).bind('scroll',showOverlayBox);
// close it when closeLink is clicked
$('a.closeLink').click( doOverlayClose );
$('a.closeLink_nogeom').click( doOverlayClose_nogeom );
$('#bucket').css( 'display', 'none' );
