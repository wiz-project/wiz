<?php
/**
 * This view render some fields to show in a different form
 * @param model $model Model to render field from. 
*/
?>
<?php
$i=0;
foreach($params as $name => $value) {
?>
	<div class="row">
		<?php echo CHtml::Label(ucwords($value->request_parameters->description),'WaterRequestGeometryZoneProperties['.$value->parameter.']'); ?>
		<?php echo CHtml::TextField('WaterRequestGeometryZoneProperties['.$value->parameter.']','',array('size'=>20,'maxlength'=>255,'class'=>'text','onkeypress'=>'javascript:evento(\''.$value->parameter.'\');')); ?>
		<?php if($value->value!=null)  
				echo CHtml::radioButton('WaterRequestGeometryZoneProperties[ae_choice]',!($i++),array('value'=>$value->parameter)); ?>
		<?php if ($value->parameter == 'sup') 
					echo CHtml::link('autofill','javascript:void(0)',array('onclick'=>'{var sup = $("#estimatedSUP").html(); $(this).prev().prev().val(sup)};')); ?>
		<?php /*echo CHtml::error($model,'parameter'); */?>
		
	</div>
<?php } 

if($i>0)
{
?>

	<div class="row" id="water_demand">
		<?php echo CHtml::label(Yii::t('waterrequest', 'Water Demand'),'water_demand_value'); ?>
		<?php echo CHtml::textField('water_demand_value','',array('disabled'=>true,'class'=>'text')); ?>
		<?php
			echo CHtml::Link(
				Yii::t('waterrequest', 'Calculate'),
				'javascript:void(0);',
				array('onclick'=>'simula();')
		    );
		?>
		<p class='hint' id='water_demand_info'></p>
		<div id="scenari"></div>
	</div>
<?php } ?>
