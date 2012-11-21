<?php
$this->breadcrumbs=array(
	'Water Supplys'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List WaterSupply', 'url'=>array('index')),
	array('label'=>'Create WaterSupply', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('water-supply-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Water Supplys</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'water-supply-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'city_state',
		'daily_maximum_water_supply',
		'yearly_average_water_supply',
		'scenario',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}',
			'buttons'=>array
			(
				'view' => array
				(
					'label'=>'View',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
				),
				'update' => array
				(
					'label'=>'Edit',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
				),
				'delete' => array
				(
					'label'=>'Delete',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/delete.png',
				),
			),
		),
	),
)); ?>
