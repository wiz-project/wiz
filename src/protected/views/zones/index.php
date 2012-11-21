<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest', 'Zones'),
);

$this->menu=array(
	array('label'=>Yii::t('waterrequest', 'Create Zone'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('zones-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php  echo Yii::t('waterrequest', 'Manage Zones');?></h1>

<p>
<?php  echo Yii::t('waterrequest', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?>
</p>

<?php echo CHtml::link(Yii::t('waterrequest', 'Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'zones-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'description',
		'parent_zone_name',
		array(   
			'name' => 'active',
			'type' => 'html',
            'value' => 'CHtml::image($data->active ? Yii::app()->request->baseUrl."/images/active.png":Yii::app()->request->baseUrl."/images/noactive.png","",array("style"=>"width:14px"))',
			'htmlOptions' => array('style'=>'text-align:center'),
		),
		array(   
			'name' => 'searchable',
			'type' => 'html',
            'value' => 'CHtml::image($data->searchable ? Yii::app()->request->baseUrl."/images/active.png":Yii::app()->request->baseUrl."/images/noactive.png","",array("style"=>"width:14px"))',
			'htmlOptions' => array('style'=>'text-align:center'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'buttons'=>array
			(
				'view' => array
				(
					'label'=>Yii::t('waterrequest', 'View'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
					'url'=>'Yii::app()->createUrl("zones/view", array("id"=>$data->name))',
				),
				'update' => array
				(
					'label'=>Yii::t('waterrequest', 'Edit'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
					'url'=>'Yii::app()->createUrl("zones/update", array("id"=>$data->name))',
				),
			),
		),
	),
)); ?>
