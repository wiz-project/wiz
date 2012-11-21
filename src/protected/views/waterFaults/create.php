<?php
$this->breadcrumbs=array(
	Yii::t('faults','Water Faults')=>array('index'),
	Yii::t('faults','Create Fault'),
);

$this->menu=array(
	array('label'=>Yii::t('faults','List Faults'), 'url'=>array('index')),
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

<h1><?php echo Yii::t('faults','Create Fault'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>