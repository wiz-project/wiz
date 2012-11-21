<?php
$this->breadcrumbs=array(
	'Plugins'
);


?>

<h1><?php echo Yii::t('extra','Installed Plugins');?></h1>
<h1></h1>

<?php

foreach($plugins as $p) {
	echo CHtml::link($p->name,Yii::app()->createUrl($p->name.'/'.$p->name.'/index', array()));
	echo ': ';
	echo $p->description;
	echo '<br/>';
} 

?>
