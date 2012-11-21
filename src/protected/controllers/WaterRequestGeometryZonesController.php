<?php

class WaterRequestGeometryZonesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/wrapper';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','simulateWD','popup','popupedit','infoZone'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Simulate WaterDemand computation
	 * @param string $zone
	 * @param string $parameter
	 * @param string $value
	 * @param string $geom
	 */
	public function actionSimulateWD($zone, $parameter, $value, $geom=null) {
		// TODO: Controllare se è un AJAX o meno
		/*
		Yii::log(
				' $zone='.$zone.
				' $parameter='.$parameter.
				' $value='.$value.
				' $geom='.$geom
				 , CLogger::LEVEL_INFO, 'actionSimulateWD()');  // DEBUG
		*/
		$info = array();
		$simulo=new WaterRequestGeometryZones;
		$simulo->zone_name=$zone;
		$simulo->updatePEAndWD($parameter, $value, $geom, $info);
		//echo Math::wd_round($simulo->water_demand).' '.Yii::app()->params['water_demand_unit'];
		echo htmlspecialchars(json_encode($info), ENT_NOQUOTES);
		Yii::app()->end();
	}
	
	/**
	 * Action to show zone info tooltip
	 * @param string $zone_id
	 * @throws CHttpException
	 */
	public function actionInfoZone($zone_id=null) {
		if ($zone_id) {
			$zone_model = WaterRequestGeometryZones::model()->findByPk($zone_id);
			if (!$zone_model)
				throw new CHttpException(404,'The requested page does not exist.');
			
			$this->renderPartial('_info_tooltip', array('model'=>$zone_model), false, true );
			Yii::app()->end();
			return;
		}
		
	}
	
	/**
	 * Popup form to insert a new Geometry with Zone or add a Zone to an existing Geometry
	 * @param string $wr_id
	 * @param string $type
	 * @param string $geom_or_id
	 * @param string $zone_id
	 * @throws CHttpException
	 */
	public function actionPopup($wr_id,$type,$geom_or_id,$zone_id=null)
	{
		$wr_model=WaterRequests::model()->findByPk($wr_id);
		if($wr_model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if (!Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$wr_model))){
			echo Yii::t('waterrequest', 'You are not authorized to edit');
			Yii::app()->end();
		}
		//Yii::log('wr_id='.$wr_id.' type='.$type.' geom_or_id='.$geom_or_id.' $zone_id='.$zone_id, CLogger::LEVEL_INFO, 'popup');
		if($type=='update_geom'){
			$geom_id = $_POST['geom_id'];
			$geom_model = WaterRequestGeometries::model()->findByPk($geom_id);
			if (!$geom_model)
				throw new CHttpException(404,'The requested page does not exist.');
								
			//Yii::app()->clientScript->scriptMap=array('jquery.yiiactiveform.js'=>false,'jquery.uniform.js'=>false,'jquery.js'=>false,);
			$this->renderPartial('/waterRequestGeometries/_popupeditgeom', array('model'=>$geom_model), false, true );
			Yii::app()->end();
			return;
		}
		
		$geom_already_exist = false;
		
		if ($type=='zone'){
			$geom_id = $geom_or_id;
			$geom_model = WaterRequestGeometries::model()->findByPk($geom_id);
			if ($geom_model)
				$geom_already_exist = true;
		}
		
		if (!$geom_already_exist) {
			
			// If geometry is outside working area, fail the popup.
			$city_state = Geometry::Get_City_State($geom_or_id);
			if ($city_state == null) {
				Yii::log('Comune non trovato. $g='.$geom_or_id , CLogger::LEVEL_INFO, 'actionPopup');  // DEBUG
				$this->renderPartial('_popup_fail');
				Yii::app()->end();
			}
			$water_supply = WaterSupply::model()->find('lower(city_state)=:city_state',array(':city_state'=>strtolower($city_state)));
			if(!$water_supply){
				Yii::log('Disegnato fuori. $city_state='.$city_state , CLogger::LEVEL_INFO, 'actionPopup');  // DEBUG
				$this->renderPartial('_popup_fail');
				Yii::app()->end();
			}
			
			$geom_model= new WaterRequestGeometries;
			$geom_model->wr_id=$wr_id;
		}
		
		$zone_model = null;
		if ($zone_id)
			$zone_model = WaterRequestGeometryZones::model()->findByPk($zone_id);
		if (!$zone_model)
			$zone_model = new WaterRequestGeometryZones;
			
		//Yii::app()->clientScript->scriptMap=array('jquery.yiiactiveform.js'=>false,'jquery.uniform.js'=>false,'jquery.js'=>false,);
		$this->renderPartial('_popupform', array('model'=>$zone_model,'geom_model'=>$geom_model,'geom_already_exist'=>$geom_already_exist), false, true );
		Yii::app()->end();
	}

	/**
	 * Popup form to edit an existing Zone
	 * @param unknown_type $zone_id
	 */
	public function actionPopupEdit($zone_id=null)
	{				
		$zone_model = null;
		if ($zone_id)
			$zone_model = WaterRequestGeometryZones::model()->findByPk($zone_id);
		else
			echo 'Zone_ID non valido';
		if (!$zone_model)
			$zone_model = new WaterRequestGeometryZones;
		//else echo 'Zone_model trovato';  // DEBUG
		
		//Yii::app()->clientScript->scriptMap=array('jquery.yiiactiveform.js'=>false,'jquery.uniform.js'=>false,'jquery.js'=>false,);
		$this->renderPartial('_popupform', array('zone_model'=>$zone_model), false, true );
		Yii::app()->end();
	}

	/**
	 * Create a new Geometry with a Zone or add a Zone to an existing Geometry
	 * @param string $id string representing the integer id of the Geometry
	 */
	public function actionCreate($id=null)
	{
		if (!empty($id)) {
			//already exist a geometry
			$geom_model = WaterRequestGeometries::model()->findByPk($id);
			if (!$geom_model) {
				echo CJSON::encode(array('status'=>'error_iniziale'));
				return;		
			}
		}
		
		if ((isset($_POST['WaterRequestGeometries'])) && (isset($_POST['WaterRequestGeometryZones']))) {
			if (!isset($geom_model)) {
				//create new geometry model
				$geom_model = new WaterRequestGeometries;
				//and store post data into model
				$geom_model->attributes=$_POST['WaterRequestGeometries'];
				$geom_model->geom=new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($_POST['WaterRequestGeometries']['geom'])));	
			}
			/*
			if (((isset($geom_model->wr)) && ($geom_model->wr->phase==2)) && ((isset($geom_model->zones)) && (count($geom_model->zones)>1))) {
				echo CJSON::encode(array('status'=>'cannot add another zone to this geometry'));
				return;
			}
			*/
			//create new geometry zone model
			$geom_zone_model = new WaterRequestGeometryZones;
			$geom_zone_model->attributes=$_POST['WaterRequestGeometryZones'];
			
			//create new geometry zone property model 
			//$geom_zone_property_model=new WaterRequestGeometryZoneProperties;	
			//$geom_zone_property_model->attributes=$_POST['WaterRequestGeometryZoneProperties'];
			if (isset($_POST['WaterRequestGeometryZoneProperties'])) {
				if(isset($_POST['WaterRequestGeometryZoneProperties']['ae_choice']))
					$ae = $_POST['WaterRequestGeometryZoneProperties']['ae_choice'];
				else 
					$ae = false;
				
				foreach($_POST['WaterRequestGeometryZoneProperties'] as $param => $value){
					if($param != 'ae_choice' && !empty($value)){
						$new_prop=new WaterRequestGeometryZoneProperties;
						$new_prop->parameter=$param;
						$new_prop->value=$value;
						// the prop is selected
						if($ae==$param)
							$new_prop->use4ae = true;
						// append to array
						$sent_props[]=$new_prop;	
					}				
				}	
				if(!isset($sent_props)){
					echo CJSON::encode(array('status'=>'error no props sent'));
					return;
				}
				// TODO: Controllare che la zona necessiti di almeno uno selezionato
				// check if one is selected, otherwise select the first one.
				$choosedProp = -1;
				for($i = 0; ($i < count($sent_props)) && ($choosedProp < 0); $i++){
					if($sent_props[$i]->use4ae)
						$choosedProp = $i;
				}		

				/*
				// 'cp' not found, set the first prop.
				if($choosedProp<0){
					$sent_props[0]->use4ae = true;
					$choosedProp=0;  
				}

				
				// Check if use4ae is needed to be set to a specific prop
				if($geom_zone_model->zone_name)
				foreach($sent_props as $geom_zone_property_model){
					$geom_zone_property_model->geometry_zone=$geom_zone_model->id;
					if ($geom_zone_property_model->()) {
						//Yii::log("Zone_name $geom_zone_model->zone_name", CLogger::LEVEL_INFO, "LOOP");
						//$geom_zone_property_model->save();	
						//echo $geom_zone_property_model->parameter;  // DEBUG
					}else
						$_propserror = true;
				}
				*/
			}
			else
				$sent_props = array();
				
			//starting new transaction
			$transaction = Yii::app()->db->beginTransaction();
			try {
				$_error = true;
				
				if ($geom_model->validate()) {
					
					//saving geom_model
					$geom_model->save();
					
					//connect geom_zone_model to geom_model through id
					$geom_zone_model->wr_geometry_id=$geom_model->id;
					/*
					var_dump($geom_zone_model->wr_geometry_id);
					$geom_zone_model->geometry=$geom_model;
					var_dump($geom_model->id);
					var_dump($geom_zone_model->wr_geometry_id);*/
					
					//calculate pe and water demand
					//$geom_zone_model->updatePEAndWD($sent_props[$choosedProp]->parameter, $sent_props[$choosedProp]->value);
					
					/*
					$geom_zone_model->pe = $geom_zone_model->calculatePE($sent_props[$choosedProp]->parameter, $sent_props[$choosedProp]->value);
					
					$geom_zone_model->water_demand = $geom_zone_model->calculateWaterDemand();
					*/
					
					//$geom_zone_model->updateWD();
					
					if ($geom_zone_model->validate()) {
						
						//saving geom_zone_model
						Yii::log('SAVING GEOM ZONE MODEL' , CLogger::LEVEL_INFO, 'actionCreate');  // DEBUG
						$geom_zone_model->save();
						
						$_propserror = false;
						foreach($sent_props as $geom_zone_property_model){
							$geom_zone_property_model->geometry_zone=$geom_zone_model->id;
							//$geom_zone_property_model->zone = $geom_zone_model;
							if ($geom_zone_property_model->validate()) {
								Yii::log('SAVING PROPERTY MODEL ' , CLogger::LEVEL_INFO, 'actionCreate');  // DEBUG
								$geom_zone_property_model->save();
								//echo $geom_zone_property_model->parameter;  // DEBUG
							}else{
								//Yii::log('PROPERTY MODEL NON VALIDA' , CLogger::LEVEL_INFO, 'actionCreate');  // DEBUG
								//Yii::log(print_r($geom_zone_property_model->attributes, true) , CLogger::LEVEL_INFO, 'actionCreate');  // DEBUG
								//Yii::log(print_r($geom_zone_property_model->getErrors(), true) , CLogger::LEVEL_INFO, 'actionCreate');  // DEBUG
								$error_status = 'Error: ';
								foreach($geom_zone_property_model->getErrors() as $attr => $err_msg)
									$error_status = $error_status.$attr.': '.implode(',',$err_msg).' ';
								echo CJSON::encode(array('status'=>$error_status)); // DEBUG
								$_propserror = true;
							}
						}
						$_error = $_propserror;
						//echo 'after foreach: '.$_propserror.' ';  // DEBUG
					}
					else {
						$error_status = 'Error: ';
						foreach($geom_zone_model->getErrors() as $attr => $err_msg)
							$error_status = $error_status.$attr.': '.implode(',',$err_msg).' ';
						echo CJSON::encode(array('status'=>$error_status)); // DEBUG
						$_error = true;
					}
				}
				else echo CJSON::encode(array('status'=>' geom_model non valida ')); // DEBUG
				if ($_error) {
					$transaction->rollBack();
					Yii::log('ROLLBACK!', CLogger::LEVEL_INFO);
					//echo CJSON::encode(array('status'=>'Rollback error == TRUE ('.$_error.')'));
				}
				else {
					$transaction->commit();
					/*
					$wr = $geom_model->wr;
					Yii::app()->controller->redirect(array('/waterRequests/update', 'id'=>$wr->id));
					*/
					echo CJSON::encode(array('status'=>'ok'));
					Yii::app()->end();
				}
			}
			catch(Exception $e){
				$transaction->rollBack();
				echo CJSON::encode(array('status'=>'not inserted '.$e));
			}
		}//if isset...
		else 
			Yii::log('Something went wrong Geom='.isset($_POST['WaterRequestGeometries']).' Zone='.isset($_POST['WaterRequestGeometryZones']).' Property='.isset($_POST['WaterRequestGeometryZoneProperties']), CLogger::LEVEL_INFO);
		Yii::app()->end();
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		
		$geom_zone_model=$this->loadModel($id);
		
		// TODO: controllare se è un modello orfano
		
		$wr_model=WaterRequests::model()->findByPk($geom_zone_model->geometry->wr_id);
		if (!Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$wr_model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));  // TODO: Gestire con ajax.error?
		
//		Yii::log("Updating $geom_zone_model->id ", CLogger::LEVEL_INFO, "Update WRG_Zones" );

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if ((isset($_POST['WaterRequestGeometryZones'])) && (isset($_POST['WaterRequestGeometryZoneProperties']))) {
			
			//Yii::log("Into IF", CLogger::LEVEL_INFO, "Update WRG_Zones" );
			
			// Actually assign only 'zone'
			$geom_zone_model->attributes=$_POST['WaterRequestGeometryZones'];
			
			if(isset($_POST['WaterRequestGeometryZoneProperties']['ae_choice']))
				$ae = $_POST['WaterRequestGeometryZoneProperties']['ae_choice'];
			else 
				$ae = false;
			$found_props_ids=array();
			foreach($_POST['WaterRequestGeometryZoneProperties'] as $param => $value){
				//Yii::log("Param $param -> Value $value", CLogger::LEVEL_INFO, "Update WRG_Zones" );
				if($param != 'ae_choice' && !empty($value)){
					$load_prop=WaterRequestGeometryZoneProperties::model()->find(
					    'parameter=:param AND geometry_zone=:geom_zone',
					    array(':param'=>$param,
					    	  ':geom_zone'=>$geom_zone_model->id)
					);
					if($load_prop==null)
						$load_prop=new WaterRequestGeometryZoneProperties;	
					else
						$found_props_ids[]=$load_prop->id;
						//Yii::log("Caricato ".print_r($load_prop->attributes,true), CLogger::LEVEL_INFO, "Update WRG_Zones" );
					$load_prop->parameter=$param;
					$load_prop->value=$value;
					// the prop is selected
					if($ae==$param)
						$load_prop->use4ae = true;
					else
						$load_prop->use4ae = false;
					// append to array
					$sent_props[]=$load_prop;	
				}				
			}	
			
			if(!isset($sent_props)){
				echo CJSON::encode(array('status'=>'error no props sent'));
				return;
			}
			// check if one is selected, otherwise select the first one.
			$choosedProp = -1;
			for($i = 0; ($i < count($sent_props)) && ($choosedProp < 0); $i++){
				if($sent_props[$i]->use4ae)
					$choosedProp = $i;
			}
			// non ho settato nulla, cerco 'cp'		
			if($choosedProp<0){
				foreach($sent_props as $geom_zone_property_model){
					if ($geom_zone_property_model->parameter == 'cp')
						$geom_zone_property_model->use4ae = true;
					$choosedProp=0;  // NOW IT'S ONLY A FLAG (0 = found, -1 = not found)
				}
			}
			// 'cp' not found, set the first prop.
			if($choosedProp<0){
				$sent_props[0]->use4ae = true;
				$choosedProp=0;  
			}
			/*
			
			// Check if use4ae is needed to be set to a specific prop
			if($geom_zone_model->zone_name)
			foreach($sent_props as $geom_zone_property_model){
				$geom_zone_property_model->geometry_zone=$geom_zone_model->id;
				if ($geom_zone_property_model->()) {
					//Yii::log("Zone_name $geom_zone_model->zone_name", CLogger::LEVEL_INFO, "LOOP");
					//$geom_zone_property_model->save();	
					//echo $geom_zone_property_model->parameter;  // DEBUG
				}else
					$_propserror = true;
			}
			*/
			
			// Select all old properties to delete
			$all_props=WaterRequestGeometryZoneProperties::model()->findAll(
				    'geometry_zone=:geom_zone',
				    array(':geom_zone'=>$geom_zone_model->id)
				);
			foreach($all_props as $found)
				if(!in_array($found->id, $found_props_ids) )
					$to_purge[]=$found->id;
				
			//Yii::log("Inseriti da utente ".print_r($found_props_ids,true), CLogger::LEVEL_INFO, "Update WRG_Zones" );
			//if(isset($to_purge))
				//Yii::log("Da eliminare ".print_r($to_purge,true), CLogger::LEVEL_INFO, "Update WRG_Zones" );
	
			//starting new transaction
			$transaction = Yii::app()->db->beginTransaction();
			try {
				$_error = true;

				// purge old properties
				if(isset($to_purge))
					foreach($to_purge as $del_id)
						WaterRequestGeometryZoneProperties::model()->findByPk($del_id)->delete();
					
				//calculate pe and water demand
				//$geom_zone_model->pe = $geom_zone_model->calculatePE($sent_props[$choosedProp]->parameter, $sent_props[$choosedProp]->value);
				//$geom_zone_model->water_demand = $geom_zone_model->calculateWaterDemand();

				//$geom_zone_model->updateWD();
				
				if ($geom_zone_model->validate()) {
					$geom_zone_model->save();
					
					$_propserror = false;
					foreach($sent_props as $geom_zone_property_model){
						$geom_zone_property_model->geometry_zone=$geom_zone_model->id;
						if ($geom_zone_property_model->validate()) {
							//Yii::log("Zone_name $geom_zone_model->zone_name", CLogger::LEVEL_INFO, "LOOP");
							$geom_zone_property_model->save();	
							//echo $geom_zone_property_model->parameter;  // DEBUG
						}else{
							Yii::log('PROPERTY MODEL NON VALIDA' , CLogger::LEVEL_INFO, 'actionUpdate');  // DEBUG
							Yii::log(print_r($geom_zone_property_model->attributes, true) , CLogger::LEVEL_INFO, 'actionUpdate');  // DEBUG
							Yii::log(print_r($geom_zone_property_model->getErrors(), true) , CLogger::LEVEL_INFO, 'actionUpdate');  // DEBUG
							$error_status = 'Error: ';
							foreach($geom_zone_property_model->getErrors() as $attr => $err_msg)
								$error_status = $error_status.$attr.': '.implode(',',$err_msg).' ';
							echo CJSON::encode(array('status'=>$error_status)); // DEBUG
							$_propserror = true;
						}					}
					$_error = $_propserror;
					//echo 'after foreach: '.$_propserror.' ';  // DEBUG
				}
				else
					echo CJSON::encode(array('status'=>' error geom_zone_model non valida '));  // DEBUG
				if ($_error) {
					$transaction->rollBack();
					//echo CJSON::encode(array('status'=>'error una delle prop non valida'));
				}
				else {
					$transaction->commit();
					echo CJSON::encode(array('status'=>'ok'));
					Yii::app()->end();
				}
			}
			catch(Exception $e){
				$transaction->rollBack();
				echo CJSON::encode(array('status'=>'not inserted '.$e));
			}
		}//if isset...
		
		Yii::log('Something went wrong Zone='.isset($_POST['WaterRequestGeometryZones']).' Property='.isset($_POST['WaterRequestGeometryZoneProperties']), CLogger::LEVEL_INFO);
		Yii::app()->end();
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$to_delete=$this->loadModel($id);
			
			// TODO: permessi di cancellare (per ora solo proprietario e sysadmin)
			if((Yii::app()->user->id==$to_delete->geometry->wr->user->username) || Yii::app()->user->getIsSysAdmin())
			{
				if ($to_delete->properties()) {
						foreach ($to_delete->properties() as $property){
							Yii::log("Cancello Property $property->id", CLogger::LEVEL_INFO, 'DELETE Zone');
							$property->delete();
						}
					}			
				Yii::log("Cancello Zone $to_delete->id", CLogger::LEVEL_INFO, 'DELETE Zone');
				$to_delete->delete();
			}
			else
				throw new CHttpException(400,'400_CANNOT_DELETE');

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('WaterRequestGeometryZones');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new WaterRequestGeometryZones('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WaterRequestGeometryZones']))
			$model->attributes=$_GET['WaterRequestGeometryZones'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=WaterRequestGeometryZones::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
		
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		// TODO: in alcune form uso id water-request-geometry-zones-form_Y e water-request-geometry-zones-form_N
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-request-geometry-zones-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
