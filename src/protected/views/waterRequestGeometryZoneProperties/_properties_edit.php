<?php
/**
 * This view render some fields to show in a different form
 * @param model $local Model to fullfill parameters from. 
 * @param array $params List of parameters to render
 * Nel caso non ci fosse nessun parametro selezionato con use4ae, tutti i radiobutton saranno deselezionati
 * (in questo caso il comportamento è deciso dal browser)
*/
?>
<?php
//Yii::log(print_r($local,true),CLogger::LEVEL_INFO,'_properties_edit');
$i=0;

foreach ($local as $myprop)
	$handy[$myprop->parameter]=$myprop;

foreach($params as $name => $value) {
?>
	<div class="row">
		<?php echo CHtml::Label(ucfirst($value->request_parameters->description),'WaterRequestGeometryZoneProperties['.$value->parameter.']'); ?>
		<?php echo CHtml::TextField('WaterRequestGeometryZoneProperties['.$value->parameter.']',
										array_key_exists($value->parameter, $handy)?
											$handy[$value->parameter]->value  // ho trovato un valore salvato
											:
											'',  // Default blank
										array('size'=>20,'maxlength'=>255,'class'=>'text')
									); ?>
		<?php if($value->value!=null)
				echo CHtml::radioButton('WaterRequestGeometryZoneProperties[ae_choice]',
										array_key_exists($value->parameter, $handy)?
											$handy[$value->parameter]->use4ae  // ho trovato un valore selezionato
											:
											false,  // default false
										array('value'=>$value->parameter)
									 );?>
		<?php 
		// NON POSSO FARE L'AUTOFILL !! Non ho una geometria di cui calcolare l'area
		/*
			if ($value->parameter == 'sup') 
					echo CHtml::link('autofill','javascript:void(0)',array('onclick'=>'{var sup = $("#estimatedSUP").html(); $(this).prev().prev().val(sup)};'));
		*/
		?>
									 
	</div>
<?php } ?>

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
