<?php
$this->breadcrumbs=array(
	Yii::t('citizen','Water Service Evaluations')=>array('index'),
	Yii::t('citizen','Give An Evaluation'),
);

Yii::app()->clientScript->registerScript('resize_content', "
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
	jQuery('input:radio[name=geom_type]').click(function() {
		if($(this).val() == 1) {
			$('#faults_list').parent().addClass('disabled');
			$('#fault').find('.required').hide();
			$('#qualities_list').parent().removeClass('disabled');
			$('#quality').find('.required').show();
		} else {
			$('#qualities_list').parent().addClass('disabled');
			$('#quality').find('.required').hide();
			$('#faults_list').parent().removeClass('disabled');
			$('#fault').find('.required').show();
		}
	});
	jQuery(document).ready(function() {
		$('#faults_list').parent().addClass('disabled');
		$('#fault').find('.required').hide();
	});
");

?>

<div class="span-13" id="map-sidebar">
	<div id="close_content"></div>
	<div id="content">

		<h1><?php echo Yii::t('citizen','Give An Evaluation'); ?></h1>

		<?php echo $this->renderPartial('_form'); ?>
	</div><!-- content -->
</div>
<div class="span-25">
	<div id="map-frame">
		<?php echo $this->renderPartial('//maps/_quality_map', array('edit'=>true)); ?>	
	</div><!-- map-frame -->
</div>
