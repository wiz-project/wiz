<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest', 'Water Requests')=>array('index'),
	Yii::t('waterrequest', 'Choose Phase')=>array('create'),
	Yii::t('waterrequest', 'Create Water Request')
);

$this->menu=array(
	array('label'=>'List WaterRequests', 'url'=>array('index')),
	array('label'=>'Manage WaterRequests', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('resize_content', "
	$('#content').watch('height', function(){
		$('#map').height($('#map-sidebar').height()-56);
    });
	jQuery('#close_content').click(function(){
		$('#map-sidebar').hide(); 
		$('.span-25').css('margin-left','7px'); 
		$('#resize_map').show();
	}).mouseover(function() {
		this.setAttribute('title', 'Close content');
	});
	jQuery('#resize_map').click(function(){
		$('#map-sidebar').show(); 
		$('.span-25').css('margin-left','510px'); 
		$('#resize_map').hide();
	}).mouseover(function() {
		this.setAttribute('title', 'View content');
	});
");
?>

<div class="span-13 cols" id="map-sidebar">
	<div id="close_content"></div>
	<div id="content">

<h1><?php echo Yii::t('waterrequest', 'Create New Water Request'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- content -->
</div>
<div class="span-25">
	<div id="map-frame">
		<?php echo $this->renderPartial('//maps/_map', array('model'=>$model, 'wr_id'=>$model->id)); ?>		
	</div><!-- map-frame -->
</div>
