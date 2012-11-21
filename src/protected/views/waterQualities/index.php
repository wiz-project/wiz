<?php
$this->breadcrumbs=array(
	Yii::t('qualities','Water Qualities'),
);

$this->menu=array(
	array('label'=>Yii::t('qualities','Create Quality'), 'url'=>array('create')),
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

<h1><?php echo Yii::t('qualities','Manage Water Qualities'); ?></h1>

<p>
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'water-qualities-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(   
			'name' => 'id',
			'header' => '',
			'filter' => false,
			'htmlOptions' => array('width'=>'30px'),
		),
		'quality',
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
					'label'=>Yii::t('qualities','View'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
					'url'=>'Yii::app()->createUrl("waterqualities/view", array("id"=>$data->id))',
				),
				'update' => array
				(
					'label'=>Yii::t('qualities','Edit'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
					'url'=>'Yii::app()->createUrl("waterqualities/update", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
