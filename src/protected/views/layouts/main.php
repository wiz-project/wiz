<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/uniform.aristo.css" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fileuploader.css" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/colorpicker.css" />
	
	<link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.png" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php
		$cs=Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.uniform.js', CClientScript::POS_HEAD);
/*		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/OpenLayers/OpenLayers.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/proj4js-combined.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG3003.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/EPSG32232.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/mylayerswitcher.js', CClientScript::POS_HEAD);
*/		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/fileuploader.js', CClientScript::POS_HEAD);
		
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.watch.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.equalHeights.js', CClientScript::POS_HEAD);
		
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/plugins/jquery.plugin.html2canvas.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Core.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Util.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Generate.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Parse.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Preload.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Queue.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/Renderer.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/html2canvas/src/renderers/Canvas.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/feedback.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/colorpicker.js', CClientScript::POS_HEAD);
		
	?>

<script type="text/javascript">
/* <![CDATA[ */
	function saveImage(drawingString) {
		var postData = "canvasData="+drawingString;
		var ajax = new XMLHttpRequest();
		ajax.open("POST",'<?php echo $this->createUrl("site/screenshot")?>',true);
		ajax.setRequestHeader('Content-Type','canvas/upload');
		ajax.onreadystatechange=function() {
			if(ajax.readyState==4 && ajax.status==200) {
				var ajaxnew = new XMLHttpRequest();
				var data = "screenshot="+ajax.responseText+"&note="+$("#note_textarea").val();
				var ajaxnew = new XMLHttpRequest();
				ajaxnew.open("POST",'<?php echo $this->createUrl("site/feedback")?>',true);
				ajaxnew.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
				ajaxnew.onreadystatechange=function() {
					if(ajaxnew.readyState==4 && ajaxnew.status==200) {
						$("#feedback_panel").hide();
						$(".highlights").remove();
						$(".blackout").remove();
						$("#screen_draw_loader").hide();
						$("#screen_draw").hide();
						if(ajaxnew.responseText == 'error')
							throwMessage("<?php echo Yii::t('feedback','An error occurred. Try again'); ?>",2000,true);
						else
							throwMessage("<?php echo Yii::t('feedback','Feedback send to administrator'); ?>",2000,false);
					}
				}
				ajaxnew.send(data);
			}
		}
		ajax.send(postData);
	}
/* ]]> */
</script>

</head>



<body>

<div id="header-wrap">
	<div id="header-container">
		<div id="header-top">
			<div id="header-top-menu">
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>Yii::t('top_menu','Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>Yii::app()->user->name, 'url'=>CController::createUrl('users/view',array('id'=>Yii::app()->user->id,'redirect'=>'home')), 'itemOptions'=>array('id'=>'user-icon'), 'visible'=>!Yii::app()->user->isGuest),
						/*array('label'=>'2', 'url'=>array('/site/logout'), 'itemOptions'=>array('id'=>'notification-icon'),'visible'=>!Yii::app()->user->isGuest
						'items'=>array(
        						array('label'=>'Notifica 1', 'url'=>array('/site/about')),
        						array('label'=>'Notifica 2', 'url'=>array('/site/about')),
      						),*/
						array('label'=>Yii::app()->user->unreadNotifications(), 'url'=>CController::createUrl('notifications/index',array('what'=>'unread')), 'itemOptions'=>array('id'=>'notification-icon'),'visible'=>!Yii::app()->user->isGuest),
						
						array('label'=>'','url'=>array(''),'itemOptions'=>array('id'=>'settings-icon'),'visible'=>!Yii::app()->user->isGuest,
							'items'=>array(
        						array('label'=>Yii::t('top_menu','Settings'), 'url'=>array('/settings/index'), 'visible'=>Yii::app()->user->haveSettings()),
								//array('label'=>Yii::t('top_menu','Profile'), 'url'=>CController::createUrl('users/view',array('id'=>Yii::app()->user->id,'redirect'=>'home'))),
        						array('label'=>Yii::t('top_menu','Logout'), 'url'=>array('/site/logout')),
      						),
						),
					),
				)); ?>
				
			</div> <!-- headertopmenu -->
		</div> <!-- headertop -->			
	</div> <!-- -->
</div> <!-- -->

<div id="header">
	<div id="logo">
		<?php //echo CHtml::encode(Yii::app()->name); ?>
		<?php 
			if (Yii::app()->user->isCitizen)
				$img = 'wiz4all.png';
			else if (Yii::app()->user->isPlanner)
				$img = 'wiz4planner.png';
			else
				$img = 'wizlogo.png';
		?>
		<img src="<?php echo 'images/'.$img; ?>" alt="Logo" style="padding: 5px 0 0 8px"/>
	</div>
	<div id="operation-menu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>Yii::t('operation_menu','Water Requests'), 'url'=>CController::createUrl('waterRequests/index',array()),'visible'=>Yii::app()->user->isWRU or Yii::app()->user->isPlanner,'itemOptions'=>array('id'=>'waterrequest-operation-icon'), 'active'=>Yii::app()->controller->id=='waterRequests'),
				array('label'=>Yii::t('operation_menu','Zones'), 'url'=>CController::createUrl('zones/index',array()),'visible'=>Yii::app()->user->isWRUT,'itemOptions'=>array('id'=>'zones-operation-icon'), 'active'=>Yii::app()->controller->id=='zones'),
				array('label'=>Yii::t('operation_menu','Parameters'), 'url'=>CController::createUrl('waterRequestParameters/index',array()),'visible'=>Yii::app()->user->isWRUT,'itemOptions'=>array('id'=>'parameters-operation-icon'), 'active'=>Yii::app()->controller->id=='waterRequestParameters'),
				array('label'=>Yii::t('operation_menu','Formulas'), 'url'=>CController::createUrl('waterRequestFormulas/index',array()),'visible'=>Yii::app()->user->isWRUT,'itemOptions'=>array('id'=>'formulas-operation-icon'), 'active'=>Yii::app()->controller->id=='waterRequestFormulas'),
				array('label'=>Yii::t('operation_menu','Water Evaluation'), 'url'=>CController::createUrl('waterQualityOpinions/index',array()),'visible'=>Yii::app()->user->isCitizen,'itemOptions'=>array('id'=>'quality-operation-icon'), 'active'=>Yii::app()->controller->id=='waterQualityOpinions'),
				array('label'=>Yii::t('operation_menu','Water Info'), 'url'=>CController::createUrl('waterInfo/index',array()),'visible'=>Yii::app()->user->isGuest||Yii::app()->user->isCitizen,'itemOptions'=>array('id'=>'info-operation-icon'), 'active'=>Yii::app()->controller->id=='waterInfo'),
				array('label'=>Yii::t('operation_menu','Plugins'), 'url'=>CController::createUrl('plugins/index',array()),'visible'=>Yii::app()->user->isDeveloper,'itemOptions'=>array('id'=>'plugin-operation-icon'), 'active'=>Yii::app()->controller->id=='plugins'),
				array('label'=>Yii::t('operation_menu','Extra'),'url'=>array('extra/index'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('id'=>'extra-operation-icon'), 'active'=>Yii::app()->controller->id=='extra'),
				//array('label'=>'More', 'url'=>array('/site/index'),'visible'=>!Yii::app()->user->isGuest,'itemOptions'=>array('id'=>'more-operation-icon'), 'active'=>''),
			),
			'linkLabelWrapper' => 'span',
			)); ?>
		
	</div><!-- operation-menu -->
</div><!-- header -->

<div id="ie6-container-wrap">
	<div class="container" id="page">

		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>
	
		<?php echo $content; ?>
		
		<div class="clear"></div>
	
		<div id="footer">
			<div style="margin:0 auto; width: 900px;">
				<div id="life_logo">&nbsp;</div>
				<div id="footer_text">
					<div id="footer_menu">
						<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>Yii::t('footer_menu','Home'), 'url'=>array('/')),
								array('label'=>Yii::t('footer_menu','About'), 'url'=>array('site/page','view'=>'about')),
								array('label'=>Yii::t('footer_menu','Contact Us'), 'url'=>array('site/page','view'=>'contacts')),
								array('label'=>Yii::t('footer_menu','Source Code'), 'url'=>array('site/page','view'=>'source')),
								array('label'=>Yii::t('footer_menu','Legal Notice'), 'url'=>array('site/page','view'=>'legal-notice')),
								array('label'=>'English version', 'url'=>CController::createUrl('lang/changeLang',array('lang'=>'en','redirect'=>Yii::app()->request->requestUri)), 'visible'=>Yii::app()->language=='it'),
								array('label'=>'Versione Italiana', 'url'=>CController::createUrl('lang/changeLang',array('lang'=>'it','redirect'=>Yii::app()->request->requestUri)), 'visible'=>Yii::app()->language=='en'),
							),
						)); 
						?>
					</div>
					<?php echo Yii::t('footer_menu','WIZ project is part-funded by the European Commission under the LIFE Programme, LIFE09/ENV/IT/000056. <br /> Any opinions expressed in these pages are those of the author/organisation and do not necessarily reflect the views of the European Commission. <br/>'); ?>
				</div>
				<div id="copyright">
					<!--Copyright &copy; <?php echo date('Y'); ?> by Acque SPA.<br/>
					All Rights Reserved.<br/>-->
					<a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
						<img alt="Licenza Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
					</a>
					<br />
					Quest' opera è distribuita con
						<a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">licenza
						Creative Commons Attribuzione - Non commerciale - Condividi allo stesso modo 3.0 Unported</a>
					<?php //echo Yii::powered(); ?>
				</div>
			</div> <!-- content-centered -->
		</div><!-- footer -->
		<a id="feedback" href="javascript:{void(0)}"><?php echo Yii::t('feedback','Leave a Feedback');?></a>
		
		<div class="form">

		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'feedback-form',
				'enableAjaxValidation'=>true,
			  )); ?>
		
		<?php echo CHtml::hiddenField('sreenshot_image'); ?>
		<div id="feedback_panel">
		<div style="margin-top:5px">
		 <div id="highlights" class="action" style="border:2px solid #cadcf5; width:60px; height:50px; text-align:center; color:#006ab2; padding:10px; float:left">
		  <img src="images/highlight.png" alt="HighLight" />
		  <p style="margin-top:10px"><?php echo Yii::t('feedback','HighLight'); ?></p>
		 </div>
		 <div id="blackout" class="action" style="border:2px solid #cadcf5; width:60px; height:50px; text-align:center; color:#006ab2; padding:10px; float:left; margin-left:2px">
		  <img src="images/blackout.png" alt="Blackout" />
		  <p style="margin-top:10px"><?php echo Yii::t('feedback','Blackout'); ?></p>
		 </div>
		 <div id="note" class="action" style="border:2px solid #cadcf5; width:60px; height:50px; text-align:center; color:#006ab2; padding:10px; float:left; margin-left:2px">
		  <img src="images/note.png" alt="Note" />
		  <p style="margin-top:10px"><?php echo Yii::t('feedback','Note'); ?></p>
		 </div>
		</div>
		<div id="note_text">
		 <textarea id="note_textarea" rows="5" cols="35"></textarea> 
		</div>
		<div class="jFormComponent button" style="width:90%; text-align:center">
		  <?php echo CHtml::Button(Yii::t('feedback','Cancel'),array('onclick'=>'closePanel();','onmouseover'=>'overButton();','onmouseout'=>'outButton();','class'=>'buttonFeed')); ?>
		  <?php echo CHtml::Button(Yii::t('feedback','Send'),array('onclick'=>'sendFeedback();','onmouseover'=>'overButton();','onmouseout'=>'outButton();','class'=>'buttonFeed')); ?>
		</div>
		</div>
		
		<?php $this->endWidget(); ?>
		
		</div>
	</div><!-- page -->
</div> <!-- ie6-container-wrap-->
<div id="screen_draw" class="black_overlay"></div>
<div id="screen_draw_loader"></div>

</body>
</html>