<?php
$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	'Upload'
);


?>

<h1>Upload new Plugin</h1>
	<noscript>			
		<p>Please enable JavaScript to use file uploader.</p>
		<!-- or put a simple form for upload here -->
	</noscript>
	
	<br/>
	
	<div id="plugin-file-uploader">		
		
	</div>
	
	<div id="plugin-link" style="display:none;">		
		<a >Test the plugin!</a>
	</div>
	
	

    <script>        
        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('plugin-file-uploader'),
                action: <?php echo "'".CController::createUrl('plugins/pluginFileUpload')."'" ?> ,
                debug: false,
                template: '<div class="qq-uploader">' + 
                			'<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                			'<div class="qq-upload-button">Upload zip file</div>' +
                			'<ul class="qq-upload-list"></ul>' + 
             				'</div>',
                onComplete: function(id, fileName, responseJSON){
                	if (('success' in responseJSON)&&(responseJSON['success']==true)) {
 						$('#plugin-file-uploader').hide();
 						$('#plugin-link a').attr('href', responseJSON['url']);
 						$('#plugin-link').show();
					}
					else {
						alert('error');
					}
                	
                },
            });
        }
        
        window.onload = createUploader;     
    </script>
    