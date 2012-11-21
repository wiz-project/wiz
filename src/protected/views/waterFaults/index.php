<?php
$this->breadcrumbs=array(
	Yii::t('faults','Water Faults'),
);

$this->menu=array(
	array('label'=>Yii::t('faults','Create Fault'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('set-color', "
	$('.color-div').each(function() {
		var color = '<div id=\"rgb-color\" style=\"background-color:'+$(this).html()+'; float:left;\"></div>';
		$(this).append(color);
	});
	$(document).ajaxComplete(function() {
		$('.color-div').each(function() {
			var color = '<div id=\"rgb-color\" style=\"background-color:'+$(this).html()+'; float:left;\"></div>';
			$(this).append(color);
		});
	});
");
?>

<h1><?php echo Yii::t('faults','Manage Water Faults'); ?></h1>

<p><?php echo Yii::t('faults','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?></p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'water-faults-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(   
			'name' => 'id',
			'header' => '',
			'filter' => false,
			'htmlOptions' => array('width'=>'30px'),
		),
		'fault',
		array(   
			'name' => 'color',
			'type' => 'html',
			'htmlOptions' => array('class'=>'color-div','width'=>'140px'),
		),
		'priority',
		array(   
			'name' => 'image',
			'type' => 'html',
            'value' => '$data->image!=""?CHtml::image(Yii::app()->request->baseUrl."/$data->image"):""',
			'htmlOptions' => array('style'=>'text-align:center'),
		),
		array(   
			'name' => 'active',
			'type' => 'html',
            'value' => 'CHtml::image($data->active ? Yii::app()->request->baseUrl."/images/active.png":Yii::app()->request->baseUrl."/images/noactive.png","",array("style"=>"width:14px"))',
			'htmlOptions' => array('style'=>'text-align:center'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'buttons'=>array
			(
				'view' => array
				(
					'label'=>Yii::t('faults','View'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
					'url'=>'Yii::app()->createUrl("waterfaults/view", array("id"=>$data->id))',
				),
				'update' => array
				(
					'label'=>Yii::t('faults','Edit'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
					'url'=>'Yii::app()->createUrl("waterfaults/update", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
