<?php
$this->breadcrumbs=array(
	Yii::t('user', 'Users'),
);

$this->menu=array(
	array('label'=>Yii::t('user', 'Create User'), 'url'=>array('create')),
	array('label'=>Yii::t('user', 'Manage Users'), 'url'=>array('admin')),
);
?>

<h1><?php  echo Yii::t('user', 'Users');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
