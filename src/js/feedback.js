	
	var paint = false;
	var message = null;
    var timeoutTimer = false;
	var d;var posx;var posy;var initx=false;var inity=false; var blackout_div; var close_div; var blackout_under;
	var box = 0;
	var posxscroll; var posyscroll;
	
	function getMouse(obj,e,classname){
		posx = posy = 0;
		var ev=(!e)?window.event:e;//Moz:IE
		if (ev.pageX){//Moz
			posxscroll=ev.pageX+window.pageXOffset;
			posyscroll=ev.pageY+window.pageYOffset;
			posx=ev.pageX;
			posy=ev.pageY;
		}
		else if(ev.clientX){//IE
			posxscroll=ev.clientX+document.body.scrollLeft;
			posyscroll=ev.clientY+document.body.scrollTop;
			posx=ev.clientX;
			posy=ev.clientY;
		}
		else{return false}//old browsers	
		
		var target = (e && e.target) || (event && event.srcElement);

		if(paint && target.id=='screen_draw') {
			obj.onmousedown=function(){
				initx=posx; inity=posy;
		
				blackout_div = $('<div id="box'+box+'"></div>')
					.css({'left' : initx+'px', 'top' : inity+'px'})
					.addClass(classname)
					.appendTo('body');
					
			}	
			obj.onmouseup=function() {
				initx=false;
				inity=false;
			
				if(blackout_div)
					if(blackout_div.css('height') == "0px" && blackout_div.css('width') == "0px")
						blackout_div.remove();
					else 
						if(classname == "blackout")
							blackout_div.css({"-moz-opacity" : "1.0", "opacity" : "1.0", "filter" : "alpha(opacity=100)", "border" : "2px solid black"});
						else
							blackout_div.css({"-moz-opacity" : "0.7", "opacity" : "0.7", "filter" : "alpha(opacity=70)", "border" : "2px solid white"});
			}

			if(initx){
				if(blackout_div) {
				
					if(blackout_div.css('height') == "0px" && blackout_div.css('width') == "0px") {
						close_div = $('<div id="close_box'+box+'"></div>')
							.addClass('close')
							.hide();
						blackout_div.after(close_div);	
						box++;
					}
				
					blackout_div.css({'width' : Math.abs(posx-initx)+'px', 'height' : Math.abs(posy-inity)+'px', 'left' : posx-initx<0?posx+'px':initx+'px', 'top' : posy-inity<0?posy+'px':inity+'px'})
					if(close_div)
						close_div.css({'left' : posx-initx<0?(posx+Math.abs(posx-initx)-10)+'px':(initx+Math.abs(posx-initx)-10)+'px', 'top' : posy-inity<0?(posy-10)+'px':(inity-10)+'px'});
				}
			} else {
				$("."+classname).mouseover(function() {
					$(this).css({"-moz-opacity" : "0.4", "opacity" : ".40", "filter" : "alpha(opacity=40)", "border" : "2px solid #cadcf5"});
					$("#close_"+$(this).attr("id")).show();
				}).mouseout(function() {
					if($(this).attr("class") == "blackout")
						$(this).css({"-moz-opacity" : "1.0", "opacity" : "1.0", "filter" : "alpha(opacity=100)", "border" : "2px solid black"});
					else
						$(this).css({"-moz-opacity" : "0.7", "opacity" : "0.7", "filter" : "alpha(opacity=70)", "border" : "2px solid white"});
					$("#close_"+$(this).attr("id")).hide();
				});
					
				$(".close").click(function() {
					var box_name = $(this).attr("id").split("_");
					$(this).remove();
					$("#"+box_name[1]).remove();
				}).mouseover(function() {
					$(this).show();
				}).mouseout(function() {
					$(this).hide();
					var box_name = $(this).attr("id").split("_");
					var class_name = $("#"+box_name[1]).attr("class");
					if(class_name == "blackout")
						$("#"+box_name[1]).css({"-moz-opacity" : "1.0", "opacity" : "1.0", "filter" : "alpha(opacity=100)", "border" : "2px solid black"});
					else
						$("#"+box_name[1]).css({"-moz-opacity" : "0.7", "opacity" : "0.7", "filter" : "alpha(opacity=70)", "border" : "2px solid white"});
				});
			}
		} 
	}
	
	function throwMessage(msg,duration,error){
        window.clearTimeout(timeoutTimer);
        timeoutTimer = window.setTimeout(function(){
            message.fadeOut(function(){
                message.remove();
                message = null;
            });
        },duration);
        if (message) 
            message.remove();
		else
			$("#feedback").show();
		if(error)
			message = $('<div />').html(msg).addClass("feedback_message_error").appendTo(document.body).fadeIn();
		else
			message = $('<div />').html(msg).addClass("feedback_message_ok").appendTo(document.body).fadeIn();
    }
	
	function closePanel() {
		$(".highlights").remove();
		$(".blackout").remove();
		$("#feedback_panel").hide(); 
		$("#screen_draw").hide();
	}
	
	function overButton() {
		paint = false;
	}
	
	function outButton() {
		if($("#screen_draw").data('classname')) {
			paint = true;
			$(document).bind('mousemove',{ data: $("#screen_draw").data('classname') }, function(event){
				getMouse(document,event,event.data.data);
			});
		}
	}
	
	function sendFeedback() {
		paint = false;
		$("#feedback").hide();
		$("#feedback_panel").hide();
		$("body").html2canvas();
	}
	
	$(document).ready(function () {
		$("#feedback").click(function() {
			$("#feedback_panel").fadeIn("slow");
			$(".action").each(function() {
				$(this).data("selected","false");
				$(this).css("background-color","#e4ebff");
			});
			$("#screen_draw").css("height",$(document).height());
			$("#screen_draw").show();
		}).mouseover(function() {
			$(this).css("color","#006ab2");
		}).mouseout(function() {
			$(this).css("color","#000");
		});
		
		$(".action").mouseover(function() {
			if($(this).data("selected") != "true")
				$(this).css("background-color","#cadcf5");
			paint = false;
		}).mouseout(function() {
			if($(this).data("selected") != "true")
				$(this).css("background-color","#e4ebff");
			if($("#screen_draw").data('classname')) {
				paint = true;
				$(document).bind('mousemove',{ data: $("#screen_draw").data('classname') }, function(event){
					getMouse(document,event,event.data.data);
				});
			}
		}).click(function() {
			$(".action").each(function() {
				$(this).data("selected","false");
				$(this).css("background-color","#e4ebff");
			});
			
			$(this).data("selected","true");
			$(this).css("background-color","#cadcf5");
			if($(this).attr('id') == 'note') {
				paint = false;
				$('#screen_draw').removeData('classname');
				$("#screen_draw").css("cursor","default");
				$("#note_text").fadeIn("slow");
				$("#note_textarea").focus();
				$("#note_textarea")
					.mouseout(function() {
						$("#note_text").fadeOut("slow");
					})
					.focusout(function() {
						$("#note_text").fadeOut("slow");
					});
			} else {
				paint = true;
				$('#screen_draw').data( 'classname' , $(this).attr('id') );
				$("#screen_draw").css("cursor","crosshair");
				$(document).bind('mousemove',{ data: $(this).attr('id') }, function(event){
					getMouse(document,event,event.data.data);
				});
			}
		});
	});