<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest', 'Water Requests')=>array('index'),
	$model->project,
);

/*
$this->menu=array(
	array('label'=>'List WaterRequests', 'url'=>array('index')),
	array('label'=>'Create WaterRequests', 'url'=>array('create')),
	array('label'=>'Update WaterRequests', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WaterRequests', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterRequests', 'url'=>array('admin')),
);*/

?>
<div class="span-13" id="map-sidebar">
	<div id="content">

		<h1>
			<?php 
				echo $model->project;
				if (Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$model)))
					echo ' ('.CHtml::link(Yii::t('waterrequest', 'edit'),CController::createUrl('waterRequests/update',array('id'=>$model->id))).')';
			?>
		</h1>
		
		<?php echo $this->renderPartial('summary_view',array('model'=>$model)); ?>
		
		<br/>
		
		<div id="geometry_list">
			<?php
				//if ($model->phase==2) 
				//	echo $this->renderPartial('//waterRequestGeometries/_geometry_list', array('model'=>$model,'view'=>true));
				//else
					echo $this->renderPartial('//waterRequestGeometries/_geometry_list_detailed', array('model'=>$model, 'view'=>true));
				?>
		</div>
		
		<br/>
		
		<div id="involved_cities">
			<?php echo $this->renderPartial('involved_service_areas',array('model'=>$model)); ?>
		</div> <!-- involved_cities -->

		<br/>
		
		<?php echo $this->renderPartial('_status_transition',array('model'=>$model)); ?>

		<br/>
		
		<?php
			if (Yii::app()->user->checkAccess('pdfWaterRequest', array('waterRequest'=>$model))) {
				$img = CHtml::image('images/document_pdf.png');
				echo CHtml::link($img, array('pdf', 'id'=>$model->id), array('id'=>'pdf-link', 'title'=>Yii::t('waterrequest', 'Generate PDF')));
			}
			
			if (Yii::app()->user->checkAccess('epanetWaterRequest', array('waterRequest'=>$model))) {
				$img = CHtml::image('images/document_epanet.png');
				echo CHtml::link($img, array('epanet', 'id'=>$model->id), array('id'=>'epanet-link', 'title'=>Yii::t('waterrequest', 'Generate Epanet file')));
			}
			
			
			if (Yii::app()->user->checkAccess('shpWaterRequest', array('waterRequest'=>$model))) {
				$img = CHtml::image('images/document_shp.png','Download shape for this request');
				$ret = Yii::app()->params['geoserver']['protocol'].
				Yii::app()->params['geoserver']['ip'].
				':'.
				Yii::app()->params['geoserver']['port'].
				Yii::app()->params['geoserver']['path'].
				'/'.
				Yii::app()->params['geoserver']['workspace'].
				'/ows'.
				'?service=WFS'.
				//'&version='.Yii::app()->params['geoserver']['version'].  // version dipende dal service
				'&version=1.0.0'.
				'&request=GetFeature'.
				'&typeName='.Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['layer_geom'].
				'&maxFeatures=50'.
				'&outputFormat=SHAPE-ZIP'.
				'&format_options=filename:wr_'.$model->id.'.zip'.
				'&viewparams=wr_id:'.$model->id;
				echo CHtml::link($img, $ret, array('id'=>'shp-link', 'title'=>Yii::t('waterrequest', 'Download shapefile')));
			}
		?>
		
		<br/><br/>
		
		<?php
			if (($model->phase==1) AND ($model->status==WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS)) AND (Yii::app()->user->isPlanner)) {
					echo '<p>'.Yii::t('waterrequest', 'Your water request has been approved by the system.').'</p>';
					echo CHtml::link(Yii::t('waterrequest', 'Create a detailed Water Request'),CController::createUrl('waterRequests/create',array('phase'=>2,'parent'=>$model->id)));
			}
		?>
		
	</div><!-- content -->
</div>
<div class="span-25">
		<div id="map-frame">
			<?php echo $this->renderPartial('//maps/_map', array('model'=>$model,'edit'=>false,'wr_id'=>$model->id)); ?>		
		</div><!-- map-frame -->
</div>
