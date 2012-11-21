<?php	
	return array(
		'initial' => WaterRequests::TEMP_STATUS,
		'node' => array(
			array(	'id'=>WaterRequests::TEMP_STATUS,
					'label'=>'temp',	 
					'constraint'=>'Yii::app()->user->checkAccess(\'createWaterRequest\')',
					'transition'=>array(
						WaterRequests::SAVED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SAVED_STATUS)',
						WaterRequests::SUBMITTED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SUBMITTED_STATUS)',
					),
			),
					
			array(	'id'=>WaterRequests::SAVED_STATUS,
					'label'=>'save', 
					'constraint'=>'Yii::app()->user->checkAccess(\'saveOwnWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::CANCELLED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::CANCELLED_STATUS)',
						WaterRequests::SAVED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SAVED_STATUS)',
						WaterRequests::SUBMITTED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SUBMITTED_STATUS)',
					),
			),
			
			array(	'id'=>WaterRequests::SUBMITTED_STATUS,
					'label'=>'submit',
					'constraint'=>'Yii::app()->user->checkAccess(\'submitOwnWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::REJECTED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::REJECTED_STATUS)',
						WaterRequests::APPROVED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::APPROVED_STATUS)',
					),
			),
					
			array(	'id'=>WaterRequests::CANCELLED_STATUS,
					'label'=>'cancel',
					'constraint'=>'Yii::app()->user->checkAccess(\'cancelOwnWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::SAVED_STATUS=>'',
					),
			),
					
			array(	'id'=>WaterRequests::APPROVED_STATUS,
					'label'=>'approve',
					'constraint'=>'Yii::app()->user->checkAccess(\'approveWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::IN_FUTURE_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::IN_FUTURE_STATUS)',
						WaterRequests::REFUSED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::REFUSED_STATUS)',
						WaterRequests::CONFIRMED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::CONFIRMED_STATUS)',
					),
			),
					
			array(	'id'=>WaterRequests::REJECTED_STATUS,
					'label'=>'reject',
					'constraint'=>'Yii::app()->user->checkAccess(\'approveWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::SAVED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SAVED_STATUS)',
						WaterRequests::SUBMITTED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SUBMITTED_STATUS)',
					),
			),
					
			array(	'id'=>WaterRequests::IN_FUTURE_STATUS,
					'label'=>'in_future',
					'constraint'=>'Yii::app()->user->checkAccess(\'futureWaterRequest\')',
					),
					
			array(	'id'=>WaterRequests::CONFIRMED_STATUS,
					'label'=>'confirm',
					'constraint'=>'Yii::app()->user->checkAccess(\'confirmWaterRequest\',array(\'waterRequest\'=>$this))',
					'transition'=>array(
						WaterRequests::IN_PROGRESS_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::IN_PROGRESS_STATUS)',
						WaterRequests::TIMEOUT_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::TIMEOUT_STATUS)',
					),
			),
					
			array(	'id'=>WaterRequests::REFUSED_STATUS,
					'label'=>'refuse',
					'constraint'=>'Yii::app()->user->checkAccess(\'refuseWaterRequest\',array(\'waterRequest\'=>$this))',
			),
					
			array(	'id'=>WaterRequests::IN_PROGRESS_STATUS,
					'label'=>'in_progress',
					'constraint'=>'$this->phase==3',
					'transition'=>array(
						WaterRequests::COMPLETED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::COMPLETED_STATUS)',
					),
			),		
			array(	'id'=>WaterRequests::TIMEOUT_STATUS,
					'label'=>'timeout',
					'constraint'=>'false',//user can't choose this status; the transition will be made by the system
					'transition'=>array(
						WaterRequests::SUBMITTED_STATUS=>'Notifications::generate("WaterRequests",$this->id,"waterrequests",WaterRequests::SUBMITTED_STATUS)',
					),
			),	
			array(	'id'=>WaterRequests::COMPLETED_STATUS,
					'label'=>'completed',
					),
		)
	)
?>