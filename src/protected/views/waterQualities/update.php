<?php
$this->breadcrumbs=array(
	Yii::t('qualities','Water Qualities')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('qualities','Update Quality'),
);

$this->menu=array(
	array('label'=>Yii::t('qualities','List Qualities'), 'url'=>array('index')),
	array('label'=>Yii::t('qualities','Create Quality'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('colorpicker-update', "
	jQuery(document).ready(function() {
		$('.colorpicker_input').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	});
");
?>

<h1><?php echo Yii::t('qualities','Update Quality')." ".$model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>