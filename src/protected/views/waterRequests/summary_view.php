<?php
	$attributes=array(
		'id',
		array(
			'label'=>$model->getAttributeLabel('phase'),
            'value'=>$model->phaseHR,
		),
		//Yii::app()->user->canSee($model->statusHR)?
			array(
				'label'=>$model->getAttributeLabel('status'),
	            'value'=>$model->statusIconHistory,
	            'type' => 'raw',
			)
		/*:
			array(
			'label'=>$model->getAttributeLabel('status'),
            'value'=>$model->statusIcon,
            'type' => 'raw',
			)*/
			,
		array(
				'label'=>$model->getAttributeLabel('timestamp'),
	            'value'=>Yii::app()->dateFormatter->format('dd MMMM yyyy, HH:mm',$model->timestamp),
			),
		'user.first_name',
		'user.last_name',
		'user.title',
		//'parent_phase',
		'description',
		'note',
		array(
			'label'=>$model->getAttributeLabel('water_demand'),
            'value'=>Utilities::printWD($model->total_water_demand),
		),
					/*
		array(
			'label'=>$model->getAttributeLabel('effective_water_demand'),
            'value'=>Math::wd_round($model->effective_water_demand),
		),*/
	);
	
	if (isset($model->parent_phase)) {
		$attr = array(
					'label'=>$model->getAttributeLabel('parent_water_request'),
					'type'=>'raw',
            		'value'=>CHtml::link($model->parent_wr->project,array('waterRequests/view','id'=>$model->parent_wr->id)),
				);
		array_push($attributes,$attr);
		$attr = array(
					'label'=>$model->getAttributeLabel('parent_water_demand'),
            		'value'=>Utilities::printWD($model->parent_wr->total_water_demand),
				);
		array_push($attributes,$attr);
		$attr = array(
					'label'=>$model->getAttributeLabel('parent_water_demand_usage'),
            		'value'=>Math::wd_percentage_round($model->total_water_demand/$model->parent_wr->total_water_demand*100).' %',
				);
		array_push($attributes,$attr);
		
	}
	
	if ($model->phase==2) {
			$attr = array(
				'label'=>$model->getAttributeLabel('expiration_date'),
	            'value'=>$model->expiration_date,			
			);
			array_push($attributes,$attr);
	}
	
	// TODO: Perche' solo i WRU possono vedere il costo? Il costo viene assegnato da WRU quando approva una richiesta.
	if (Yii::app()->user->isWRU) {
		$attr = array(
					'label'=>$model->getAttributeLabel('cost'),
            		'value'=>$model->cost.' '.Yii::app()->params['currency'],
				);
		array_push($attributes,$attr);
		$attr = array(
			'label'=>$model->getAttributeLabel('file_link'),
			'type'=>'raw',
            'value'=>CHtml::link($model->file_link,Yii::app()->params['transition']['upload_dir'].'/'.$model->file_link	)			
		);
		array_push($attributes,$attr);
	}
	
	if (Yii::app()->user->checkAccess('updateWaterDemandInWaterRequest')) {
		$attr = array(
					'label'=>$model->getAttributeLabel('effective_water_demand'),
            		'value'=>Math::wd_round($model->effective_water_demand),
				);
		array_push($attributes,$attr);
	}
	
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
//		'itemCssClass'=>array('odd accordion', 'even accordion'),
	)); 
?>
<script type="text/javascript">
/* <![CDATA[ */
function infoHistory(elem, wr_id) {
		$('.info_history').remove();
		var url = <?php echo CJSON::encode(CController::createUrl('waterRequests/infoHistory')); ?>;
		$.ajax({
			url: url,
			cache: false,
			data: { wr_id: wr_id },
			dataType: 'html',
			beforeSend: function() {
				$(elem).addClass('spinner');
			},
			complete: function() {
				$(elem).removeClass('spinner');
			},
			success: function(html) {
				$(elem).after(html);
				$(elem).next(".tooltip").css({
					'top': $(elem).position().top,
					'left': $(elem).position().left + $(elem).width() + 50,
					'display': 'block'});
			}
		});
	}

/* ]]> */
</script>