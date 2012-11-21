<?php
$this->breadcrumbs=array(
	'Water Request Geometries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WaterRequestGeometries', 'url'=>array('index')),
	array('label'=>'Create WaterRequestGeometries', 'url'=>array('create')),
	array('label'=>'Update WaterRequestGeometries', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WaterRequestGeometries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaterRequestGeometries', 'url'=>array('admin')),
);
?>
<div class="span-13" id="map-sidebar">
	<div id="content">

<h1>View WaterRequestGeometries #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'wr_id',
		array(
			'label'=>'Coordinate',
            'type'=>'raw',
			'value'=>
				$model->geom != null ?
				CHtml::link('Evidenzia sulla mappa','javascript:ctrlSelect.clickFeature(geoms.getFeatureByFid(\'wr_geom.'.$model->id.'\'));')
				:
				'Non ha coordinate'
		)
		
	),
)); ?>

	</div><!-- content -->
</div>
<div class="span-25">
		<div id="map-frame">

<?php echo $this->renderPartial('//maps/_map', array('model'=>$model,'edit'=>false)); ?>		
		</div><!-- map-frame -->
</div>
