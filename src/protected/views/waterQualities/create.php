<?php
$this->breadcrumbs=array(
	Yii::t('qualities','Water Qualities')=>array('index'),
	Yii::t('qualities','Create Quality'),
);

$this->menu=array(
	array('label'=>Yii::t('qualities','List Qualities'), 'url'=>array('index')),
);

Yii::app()->clientScript->registerScript('colorpicker-create', "
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

<h1><?php echo Yii::t('qualities','Create Quality'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>