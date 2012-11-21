<?php
$this->breadcrumbs=array(
	Yii::t('faults','Water Faults')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('faults','Update Fault'),
);

$this->menu=array(
	array('label'=>Yii::t('faults','List Faults'), 'url'=>array('index')),
	array('label'=>Yii::t('faults','Create Fault'), 'url'=>array('create')),
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

<h1><?php echo Yii::t('faults','Update Fault')." ".$model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>