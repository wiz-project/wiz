<?php
$gridview=isset($gridview)?$gridview:false;  // ensure variable set
$status=isset($status)?$status:'all';  // ensure variable set
?>

<?php
$this->breadcrumbs=array(
	Yii::t('waterrequest', 'Water Requests'),
);
/*
$this->menu=array(
	array('label'=>'Create WaterRequests', 'url'=>array('create')),
	array('label'=>'Manage WaterRequests', 'url'=>array('admin')),
);
*/
?>

<h1><?php echo Yii::t('waterrequest', 'Water Requests'); ?></h1>
<?php
	if (Yii::app()->user->isPlanner){
		echo CHtml::openTag('p');
		echo CHtml::link(Yii::t('waterrequest', 'Create New Water Request'),CController::createUrl('waterRequests/create'));
		echo CHtml::closeTag('p');
	}
	echo CHtml::openTag('div', array('id'=>'wr_list'));
	
	// Status Filter line
	echo CHtml::openTag('div');
	echo Yii::t('waterrequest', 'View status: ');
	echo CHtml::link(
						Yii::t('waterrequest', 'All'),
						'javascript:void(0);',
						array(
								'id'=>'all_wr',
								'class'=>($status=='all')?'wr_filter_selected':'',
								'onclick'=>'$.fn.yiiListView.update(\'waterRequestsList\', {data:{\'status\':\'all\', \'gridview\':view_wr_list_as_grid}});'
								)
					);
	foreach(WaterRequests::allStatuses() as $value){
		if(Yii::app()->user->canSee($value)){
			echo '&nbsp;&#124;&nbsp;';
			echo CHtml::link(
					ucfirst(Yii::t('waterrequest', $value)),
					'javascript:void(0);',
					array(
							'id'=>$value.'_wr',
							'class'=>($status==$value)?'wr_filter_selected':'',
							'onclick'=> 'javascript: $.fn.yiiListView.update(\'waterRequestsList\', {data:{\'status\':\''.$value.'\', \'gridview\':view_wr_list_as_grid }});',
							)
			);
		}
	}
	
	?>
	
<span id="search_links"></span>	

<?php echo CHtml::closeTag('div');


	echo CHtml::openTag('div', array('style'=>'float:right'));
	// Choose visualization type List or Grid
	echo CHtml::link(
						'&nbsp;',
						'javascript:void(0);',
						array(	'id'=>'wr_view_list',
								'title'=>Yii::t('waterrequest', 'List View'),
								'class'=>($gridview)?'wr_view':'wr_view active','onclick'=>'$(\'.wr_view\').removeClass(\'active\'); $(\'.water_request\').removeClass(\'square\'); $(this).addClass(\'active\'); setgridview(false);')
					);
	echo '&nbsp;';

	echo CHtml::link(
						'&nbsp;',
						'javascript:void(0);',
						array(	'id'=>'wr_view_gallery',
								'title'=>Yii::t('waterrequest', 'Grid View'),
								'class'=>($gridview)?'wr_view active':'wr_view','onclick'=>' $(\'.wr_view\').removeClass(\'active\'); $(\'.water_request\').addClass(\'square\'); $(this).addClass(\'active\'); setgridview(true);')
					);
	echo CHtml::closeTag('div');
	echo '<br/>';
	// Municipality Filter
	echo CHtml::openTag('div');
	if(Yii::app()->user->isPlanner && Yii::app()->user->municipality)
		if($municipality)
			echo CHtml::link(
					Yii::t('waterrequest', 'Back to my requests'),
					CController::createUrl('waterRequests/index'),
					array('id'=>'all_municipality')
			);
		else 
			echo CHtml::link(
					Yii::t('waterrequest', 'View only requests from Municipality ').Yii::app()->user->municipality,
					CController::createUrl('waterRequests/index', array('municipality'=>Yii::app()->user->municipality)),
					array('id'=>'only_my_municipality')
			);
	
	echo CHtml::closeTag('div');

	$this->widget('zii.widgets.CListView', array(
			'id'=>'waterRequestsList',
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
			'viewData'=>array('municipality'=>null, 'gridview'=>$gridview),
			'template'=>'{sorter}<br />{pager}{items}{pager}',
			/*'enableSorting' => true,
			'sortableAttributes'=>array(
					'status'=>'By status',
			),*/
			'ajaxUpdate'=>'wr_list',
	));
	
echo CHtml::closeTag('div');
?>
<script>
/* <![CDATA[ */

var view_wr_list_as_grid = <?php echo ($gridview?'true':'false'); ?>;

function setgridview(boolparam){

	view_wr_list_as_grid=boolparam;
	$('#waterRequestsList > div.pager  a').each(function(){$(this).prop('href', $.param.querystring($(this).prop('href') , 'gridview='+boolparam));});
	
};

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
