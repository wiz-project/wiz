<?php
$this->breadcrumbs=array(
	Yii::t('notifications','Notifications'),
);

$this->menu=array(
	array('label'=>Yii::t('notifications','Create Notifications'), 'url'=>array('create')),
	array('label'=>Yii::t('notifications','Manage Notifications'), 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('resize_content', "
	jQuery('#call').click(function(){
		if($('#call').is(':checked')) {
			if($('.notification').length == 0) {
				var btn = '<div class=\"notification row\">".CHtml::submitButton('',array('title'=>Yii::t('notifications','Store')))."</div>';
				$('.grid-view').before(btn);
			}
		} else
			$('.notification').remove();
		$('input:checkbox[name=\"cid[]\"]').each(function() {
			if($('#call').is(':checked')) {
				if(!$(this).attr('disabled')) {
					$(this).parents('span').addClass('checked');
					$(this).attr('checked', true);
				}
			}
			else {
				$(this).parents('span').removeClass('checked');
				$(this).attr('checked', false);
			}
		});
	});
	
	$('input:checkbox[name=\"cid[]\"]').click(function() {
		var check = false;
		$('input:checkbox[name=\"cid[]\"]').each(function() {
			if($(this).is(':checked'))
				check = true;
		});
		if(check) {
			if($('.notification').length == 0) {
				var btn = '<div class=\"notification row\">".CHtml::submitButton('',array('title'=>Yii::t('notifications','Store')))."</div>';
				$('.grid-view').before(btn);
			}
		} else {
			$('#call').parents('span').removeClass('checked');
			$('#call').attr('checked', false);
		}
	});
	
	$(document).ajaxComplete(function() {
		$('input:checkbox').uniform();
		
		jQuery('#call').click(function(){
			if($('#call').is(':checked')) {
				if($('.notification').length == 0) {
					var btn = '<div class=\"notification row\">".CHtml::submitButton('',array('title'=>Yii::t('notifications','Store')))."</div>';
					$('.grid-view').before(btn);
				}
			} else
				$('.notification').remove();
			$('input:checkbox[name=\"cid[]\"]').each(function() {
				if($('#call').is(':checked')) {
					if(!$(this).attr('disabled')) {
						$(this).parents('span').addClass('checked');
						$(this).attr('checked', true);
					}
				}
				else {
					$(this).parents('span').removeClass('checked');
					$(this).attr('checked', false);
				}
			});
		});
	
		$('input:checkbox[name=\"cid[]\"]').click(function() {
			var check = false;
			$('input:checkbox[name=\"cid[]\"]').each(function() {
				if($(this).is(':checked'))
					check = true;
			});
			if(check) {
				if($('.notification').length == 0) {
					var btn = '<div class=\"notification row\">".CHtml::submitButton('',array('title'=>Yii::t('notifications','Store')))."</div>';
					$('.grid-view').before(btn);
				}
			} else {
				$('#call').parents('span').removeClass('checked');
				$('#call').attr('checked', false);
			}
		});
	});
	
	$(document).ready(function() {
		if($('.empty').length) 
			$('#call').attr('disabled','disabled');
	});
");

?>

<h1><?php echo Yii::t('notifications','Notifications'); ?></h1>

<?php if(Yii::app()->user->hasFlash('notification_success')): ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('notification_success'); ?>
	</div>

<?php else: 
		if(Yii::app()->user->hasFlash('notification_error')): ?>
			<div class="flash-error">
				<?php echo Yii::app()->user->getFlash('notification_error'); ?>
			</div>
			
<?php   endif;
	
	   endif; 
?>

<div class="form">

<?php 

	$form=$this->beginWidget('UniActiveForm', array(
		'id'=>'notifications-form',
		'enableAjaxValidation'=>false,
	)); 

	echo Chtml::hiddenField('what',$what); 
	$this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider' => $dataProvider,
	    'columns' => array(
			array(
				'name'=> CHtml::checkBox("call",null,array("value"=>"all","id"=>"call")),         
				'value'=>'CHtml::checkBox("cid[]",false,array("value"=>$data->id,"id"=>"cid_".$data->id,"disabled"=>$data->read?true:false))',
				'type'=>'raw',
				'htmlOptions'=>array('style'=>'text-align:center','width'=>'120px'),
			),
	        array(
	            'name'=>'timestamp',
				'header'=>Yii::t('notifications','Date & Time'),
	            'type'=>'raw',
				'value'=>'$data->timestampHR',
				'htmlOptions'=>array('style'=>'width:20%'),
				'cssClassExpression' => "(\$data->read) ? '' : 'unread_txt'",
	        ),
	        array(
	            'name'=>'description',
				'header'=>Yii::t('notifications','Object'),
	            'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->description), $data->link)',
	        ),
	    ),
	));
	
	$this->endWidget();
	
?>

</div><!-- form -->