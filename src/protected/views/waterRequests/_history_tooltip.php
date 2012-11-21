<div class="tooltip info_history" style="display: none">
	<div class="tooltip_content">
		<div class="geom_name">
			&nbsp;
			<?php echo CHtml::link(
					Yii::t('waterrequest', 'Close'),
					'javascript:void(0);',
					array('id'=>'close_info_geom','alt'=>'close info geom','title'=>'close info geom','class'=>'close_info_geom', 'onclick'=>'$(this).parents(".tooltip").hide();')
					);
			?>
		</div>
		<div style="padding: 6px;">
		<?php
				$tr_file='waterrequest';
				$columns=Yii::app()->user->canSeeHistory($model->statusHR)?
							array(
									array(
							            'name'=>Yii::t('waterrequest', 'Date'),
							            'value'=>'Yii::app()->dateFormatter->format(\'dd MMMM yyyy, HH:mm\',$data->timestamp)',
							        ),
									'comment',
									array(
							            'name'=>Yii::t('waterrequest', 'Status'),
							            'value'=>'Yii::t(\'waterrequest\',$data->status)',
							        ),
							)
						:
							array(
									array(
							            'name'=>Yii::t('waterrequest', 'Date'),
							            'value'=>'Yii::app()->dateFormatter->format(\'dd MMMM yyyy, HH:mm\',$data->timestamp)',
							        ),
							        array(
							            'name'=>Yii::t('waterrequest', 'Status'),
							            
							            'value'=>'ucfirst(Yii::t(\'waterrequest\',$data->status))',
							        ),
									
							);
				$this->widget('zii.widgets.grid.CGridView',
					array(
						'id'=>'water-request-history-grid',
						'ajaxUpdate'=>false,
						'enablePagination'=>false,
						'enableSorting'=>false,
						'dataProvider'=>$dataprovider,
						'template'=>'{items}',
						//'filter'=>$model,
						'columns'=>$columns,
						)
					);
		?>
		
		</div>
	</div><!-- tooltip_content -->
</div> <!-- tooltip -->