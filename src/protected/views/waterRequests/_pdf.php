<page backtop="22mm" backbottom="10mm" backleft="10mm" backright="10mm">
    <page_header>
        <table style="width: 100%; border-bottom: solid 2px #006AB2;">
            <tr>
            	<!--
                <td style="text-align: left;    width: 33%"></td>
                <td style="text-align: center;    width: 34%"></td>
                <td style="text-align: right;    width: 33%"></td>
               -->
               	<?php $img = CHtml::image("images/wizlogo.png",""); ?>
                <td style="text-align: left;    width: 35%"><?php echo $img; ?></td>
                <td style="text-align: right;    width: 65%">
                	<?php echo CHtml::encode($data->project);?>
                	<br/><br/>
                	<?php echo 'Stampato il '.CHtml::encode(Yii::app()->dateFormatter->formatDateTime((date(Yii::app()->params['dateFormat'])),'long',false)); ?>
                </td>

            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%; border-top: solid 1px #006AB2;">
        	<!--
            <tr>
                <td style="text-align: left;    width: 50%"></td>
                <td style="text-align: right;    width: 50%"></td>
            </tr>
           -->
			<tr>
                <td style="text-align: left;    width: 70%"><?php echo CHtml::encode(ucfirst($data->user->title)).' '.CHtml::encode($data->user->first_name).' '.CHtml::encode($data->user->last_name);?></td>
                <td style="text-align: right;    width: 30%">page [[page_cu]] / [[page_nb]]</td>
            </tr>
        </table>
    </page_footer>
    
    <div style="font-size: 14pt; text-align: right">
		<qrcode value="<?php echo CController::createAbsoluteUrl('waterRequests/view',array('id'=>$data->id)); ?>" style="width: 20mm; background-color: white; color: black; border:none"></qrcode>	
	</div>
    <br/>
   	<div style="font-size: 14pt; text-align: center">
		<b><?php echo CHtml::encode($data->project); ?></b>
	</div>
	<br/>
    <table style="width: 100%; border: solid 1px #C9E0ED">
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b></td>
    		<td><?php echo CHtml::encode($data->id); ?></td>
    	</tr>
    	<tr>
    		<td style="text-align: left;    width: 25%" ><b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b></td>
    		<td style="text-align: left;    width: 75%" ><?php echo CHtml::encode(ucfirst($data->user->title)).' '.CHtml::encode($data->user->first_name).' '.CHtml::encode($data->user->last_name);?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('phase')); ?>:</b></td>
    		<td><?php echo CHtml::encode($data->phaseHR); ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b></td>
    		<td><?php echo $data->statusIcon; ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b></td>
    		<td><?php echo CHtml::encode($data->timestampHR); ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b></td>
    		<td><?php echo CHtml::encode($data->description); ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b></td>
    		<td><?php echo CHtml::encode($data->note); ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('rounded_water_demand')); ?>:</b></td>
    		<td><?php echo CHtml::encode(Math::wd_round($data->total_water_demand)).' '.Yii::app()->params['water_demand_unit']; ?></td>
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('city_states')); ?>:</b></td>
    		<td><?php echo CHtml::encode(implode(",", $data->cityStates)); ?></td> 
    	</tr>
    	<tr>
    		<td><b><?php echo CHtml::encode($data->getAttributeLabel('geometries')); ?>:</b></td>
    		<td><?php echo CHtml::encode(count($data->geometries())); ?></td>
    	</tr> 
    </table>
    <br/><br/>
    <?php if (count($data->geometries()))
    			echo $data->imageTag(); 
    ?>
    
    <div>
		
		<?php
			foreach ($data->geometries() as $geom) { 
				$name = ($geom->name === '') ? 'Senza Nome' : $geom->name;
				?>
				<page pageset="old">
					<b><?php echo CHtml::encode($name).' - '.CHtml::encode(Math::wd_round($geom->geom_water_demand)).' '.Yii::app()->params['water_demand_unit']; ?></b>
					<br/><br/>
					<?php echo $geom->imageTag(); ?>
					<br/><br/>
					<table style="width: 100%; border: solid 1px #000">
						<thead>
							<tr>
								<td style="text-align: left;    width: 50%" ><b><?php echo CHtml::encode($geom->getAttributeLabel('zone')); ?></b></td>
    							<td style="text-align: left;    width: 20%" ><b><?php echo CHtml::encode($geom->getAttributeLabel('pe')); ?></b></td>
								<td style="text-align: left;    width: 30%" ><b><?php echo CHtml::encode($geom->getAttributeLabel('water_demand')); ?></b></td>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($geom->zones() as $zone) { ?>
									<tr>
										<td><?php echo CHtml::encode($zone->zoneDescription). ' - '.CHtml::encode($zone->zone_name) ; ?> </td>
										<td><?php echo CHtml::encode(Math::pe_round($zone->pe)); ?> </td>
										<td><?php echo CHtml::encode(Math::wd_round($zone->water_demand)).' '.Yii::app()->params['water_demand_unit']; ?> </td>
									</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</page>
			<?php
			}
		?>
	</div>
    
</page>


