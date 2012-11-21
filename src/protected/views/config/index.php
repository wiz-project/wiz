<?php
$this->breadcrumbs=array(
	Yii::t('config','Manage Params')
);

$this->menu=array(
	array('label'=>Yii::t('config','Create Param'), 'url'=>array('create')),
);

?>

<h1><?php echo Yii::t('config','Manage Params'); ?></h1>

<?php if(Yii::app()->user->hasFlash('success')) { ?>

	<div class="flash-success">
		<?php echo Yii::t('config',Yii::app()->user->getFlash('success')); ?>
	</div>

<?php } 

	$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'params-grid',
        'dataProvider'=>$dataProvider,
        'columns'=>array(
			array(   
				'name' => 'ID',
				'type' => 'raw',
				'htmlOptions' => array('style'=>'text-align:center','width'=>'70px'),
			),
			array(   
				'name' => Yii::t('config','Param'),
				'type' => 'raw',
				'value' => '$data["Parameter"]'
			),
			array(   
				'name' => Yii::t('config','Value'),
				'type' => 'raw',
				'value' => '$data["Value"]'
			),
			array(
				'class'=>'CButtonColumn',
				'htmlOptions' => array('style'=>'text-align:center'),
				'template'=>'{update}',
				'buttons'=>array
				(
					'update' => array
					(
						'label'=>Yii::t('config','Edit'),
						'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
						'url'=>'Yii::app()->createUrl("config/update", array("id"=>$data["Parameter"]))',
						'visible'=>'$data["Value"] != "" || is_int($data["Value"])',
					),
				),
			),
        ),
)); ?>