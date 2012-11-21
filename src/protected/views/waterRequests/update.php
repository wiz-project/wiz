<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest', 'Water Requests')=>array('index'),
	$model->project=>array('view','id'=>$model->id),
	Yii::t('waterrequest', 'Update'),
);

$this->menu=array(
	array('label'=>'List WaterRequests', 'url'=>array('index')),
	array('label'=>'Create WaterRequests', 'url'=>array('create')),
	array('label'=>'View WaterRequests', 'url'=>array('view', 'id'=>$model->id)),
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
	<div id="content">

<h1><?php echo $model->project; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- content -->
</div>
<div class="span-25">
		<div id="map-frame">

<?php echo $this->renderPartial('//maps/_map', array('model'=>$model,'wr_id'=>$model->id)); ?>		
		</div><!-- map-frame -->
</div>
