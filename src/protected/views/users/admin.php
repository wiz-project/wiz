<?php
$this->breadcrumbs=array(
	Yii::t('user', 'Manage Users'),
);

$this->menu=array(
	array('label'=>Yii::t('user', 'List Users'), 'url'=>array('index')),
	array('label'=>Yii::t('user', 'Create User'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('users-grid', {
			data: $(this).serialize()
		});
		return false;
	});
");
?>

<h1><?php echo Yii::t('user', 'Manage Users');?></h1>

<p>
<?php echo Yii::t('user', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?>
</p>

<?php if(Yii::app()->user->hasFlash('success')){ ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
	
<?php } ?>

<?php echo CHtml::link(Yii::t('user', 'Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'first_name',
		'last_name',
		'email',
		'username',
		'role_name',
		'password',
		array(   
			'name' => 'active',
			'type' => 'html',
            'value' => 'CHtml::image($data->active ? Yii::app()->request->baseUrl."/images/active.png":Yii::app()->request->baseUrl."/images/noactive.png","",array("style"=>"width:14px"))',
			'htmlOptions' => array('style'=>'text-align:center','width'=>'70px'),
		),
		array(
			'class'=>'CButtonColumn',
			'deleteConfirmation'=>Yii::t('user','Are you sure you want to delete this item?'),
			'htmlOptions' => array('style'=>'text-align:center','width'=>'100px'),
			'template'=>'{view}{update}{approved}{delete}{retrieve}',
			'buttons'=>array
			(
				'view' => array
				(
					'label'=>'View',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
					'url'=>'Yii::app()->createUrl("users/view", array("id"=>$data->username))',
				),
				'update' => array
				(
					'label'=>'Edit',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/edit.png',
					'url'=>'Yii::app()->createUrl("users/update", array("id"=>$data->username))',
				),
				'approved' => array
				(
					'label'=>'Approve Role',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/approve_role.png',
					'url'=>'Yii::app()->createUrl("users/approve", array("id"=>$data->username))',
					'visible'=>'!$data->approved && $data->role_name == "planner"',
				),
				'delete' => array
				(
					'label'=>'Remove',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/delete.png',
					'url'=>'Yii::app()->createUrl("users/delete", array("id"=>$data->username))',
					'visible'=>'$data->active',
				),
				'retrieve' => array
				(
					'label'=>'Retrieve Password',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/retrieve.png',
					'url'=>'Yii::app()->createUrl("users/retrieve", array("id"=>$data->username,"redirect"=>"admin"))',
				),
			),
		),
	),
)); 

echo CHtml::link(Yii::t('user', 'Create new user'),CController::createUrl('users/create'));

?>
