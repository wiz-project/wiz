<?php

class WaterRequestsController extends Controller
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
				'actions'=>array('index','view','comune','epanet','epanetFileUpload','zipFileUpload','pdf','allPdf','updateStatus', 'infoHistory','getrwd','parentpercent'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','showgeoms','setstatus','upload'),
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
		$model = $this->loadModel($id);
		if (Yii::app()->user->checkAccess('viewWaterRequest', array('waterRequest'=>$model))) 
			$this->render('view',array(
				'model'=>$model,
			));
		else {
			//throw new CHttpException(403,Yii::t('http_status', '403'));
			 Yii::app()->user->setState('redirect', Yii::app()->request->requestUri);
			$this->redirect(array('/site/login'));
		}
			
	}
	
	/**
	 * Displays the rounded water demand.
	 * @param integer $id the ID of the model
	 */
	public function actionGetrwd($id)
	{
		$model = $this->loadModel($id);
		if (Yii::app()->user->checkAccess('viewWaterRequest', array('waterRequest'=>$model))) 
			echo CJSON::encode(array('status'=>'ok', 'rwd'=>$model->rounded_water_demand));
		else
			echo CJSON::encode(array('status'=>'ok'));
	}
	
	/**
	 * Displays the rounded water demand.
	 * @param integer $id the ID of the model
	 */
	public function actionParentpercent($id)
	{
		$model = $this->loadModel($id);
		if (Yii::app()->user->checkAccess('viewWaterRequest', array('waterRequest'=>$model))) 
			echo $this->renderPartial('parent_percent', array('model'=>$model), false, true);
		else
			throw new CHttpException(403,Yii::t('http_status', '403'));
	}
	/**
	 * Set the status of a request
	 * @param string $id
	 * @param string $status
	 * @return mixed any error occurred
	 */
	public function actionSetstatus($id,$status) {
		// TODO: Set an appropriate auth policy
		if(!Yii::app()->user->getIsSysAdmin() )//  already in $model->updateStatus()
			return print_r('You are not authorized to do that');
		if(!$id)
			return print_r('Set an id');
		if(!$status)
			return print_r('Set a status');
		if((intval($status)!=WaterRequests::APPROVED_STATUS)&&(intval($status)!=WaterRequests::REJECTED_STATUS))//  already in $model->updateStatus()
			return print_r('Wrong status');
		
		try{
			$model=$this->loadModel(intval($id));
		}catch(Exception $e){
			// TODO: re-throw if not a 404 exception
			return print_r('Model not found');
		}
		if($model->status!=WaterRequests::SUBMITTED_STATUS)//  already in $model->updateStatus()
			return print_r('Model must be Submitted');  // TODO: Expose statusLabel() or parametrize getStatusHR()
		
		if($model->updateStatus(intval($status)))
			if($model->save())
				//$this->redirect(array('view','id'=>$model->id));
				/* hack to reload page, should use json encode and decode with
				 * echo json_encode(array('redirect'=>$this->createUrl('view','id'=>$model->id)));
				 */
				echo '<script type="text/javascript">window.location.reload()</script>';  
			else
				return print_r('Error saving water request');
		else
			echo 'Something went wrong';
		Yii::app()->end();
	}
	
	/**
	 * List all related WaterRequestGeometries.
	 * This is only a proxy function, the real rendering is done by the WaterRequestGeometries view.
	 * @param string $id
	 */
 	public function actionShowgeoms($id) {
		$model=WaterRequests::model()->findByAttributes(
			array(
				//'username'=>Yii::app()->user->id,
				'id'=>$id
			)
		);
		if($model == null)
		{
			echo "non trovato";
		}else 
			//if ($model->phase==2) 
			//	echo $this->renderPartial('//waterRequestGeometries/_geometry_list', array('model'=>$model,'view'=>false));
			//else
				echo $this->renderPartial('//waterRequestGeometries/_geometry_list_detailed', array('model'=>$model,'view'=>false));
		Yii::app()->end();
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param string $phase 1, 2 or 3
	 * @param string $parent Parent WaterRequest ID
	 */
	public function actionCreate($phase=null,$parent=null)
	{
		if (!Yii::app()->user->checkAccess('createWaterRequest'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		if(!$phase || ($phase!=1 && $phase!=2 && $phase!=3)){
			$this->layout='//layouts/column1';
			/*
			$model=WaterRequests::model()->findAllByAttributes(
				array(
					'username'=>Yii::app()->user->id,
					'status'=>WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS),
					'phase'=>1
				)
			);*/
			$criteria = new CDbCriteria;
			$criteria->compare('username',Yii::app()->user->id,true,'AND');
			$criteria->compare('status',WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS),false,'AND');
			$criteria->compare('phase',1,false,'AND');
			
			$dataProvider=new CActiveDataProvider(
				WaterRequests::model()->no_tmp(),
					array(
    					'criteria'=>$criteria
	    			)
			);
			$this->render('choose_phase',array('dataProvider'=>$dataProvider));
			Yii::app()->end();
		}
		
		$model=WaterRequests::model()->findByAttributes(
			array(
				'username'=>Yii::app()->user->id,
				'status'=>WaterRequests::SW_NODE(WaterRequests::TEMP_STATUS),
				'phase'=>$phase
			)
		);
		if($model != null) {
			$model->delete();
		}
			
		$model=new WaterRequests;
		$model->username=Yii::app()->user->id;
		$model->phase=$phase;
		
		if ($parent) {
			//check if parent exist and it would be a right parent
			$parent_phase = $parent_status = null;
			if ($phase==2) {
				$parent_phase = 1;
				$parent_status = WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS);
			}
			else if ($phase==3) {
				$parent_phase = 2;
				$parent_status = WaterRequests::SW_NODE(WaterRequests::CONFIRMED_STATUS); 
			}
				
			$p = WaterRequests::model()->findAllByAttributes(
				array(
					'username'=>Yii::app()->user->id,
					'status'=>$parent_status,
					'phase'=>$parent_phase,
					'id'=>$parent
				)
			);
			if (isset($p))
				$model->parent_phase = $parent;
		}
		$model->save(false);
		
		if ($model->phase==2)
			$model->scenario = 'phase_two';
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WaterRequests']))
		{
			
			$model->attributes=$_POST['WaterRequests'];
			//$model->timestamp=date(Yii::app()->params['dateTimeFormatDB']);
			//$model->username=Yii::app()->user->id;
			
			if (isset($_POST['save-button'])) {
				$model->updateStatus(WaterRequests::SAVED_STATUS);
			}
			else
				$model->updateStatus(WaterRequests::SUBMITTED_STATUS);
				
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$this->render('create',array(
			'model'=>$model
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		//Yii::log(print_r(($model->isEditable())?'TRUE':'FALSE', true), CLogger::LEVEL_INFO);
		//Yii::log(print_r($model->attributes, true), CLogger::LEVEL_INFO);
		
		if (!Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if ($model->phase==2)
			$model->scenario = 'phase_two';
		
		if(isset($_POST['WaterRequests']))
		{
			$model->attributes=$_POST['WaterRequests'];
			if (isset($_POST['save-button'])) {
				$model->updateStatus(WaterRequests::SAVED_STATUS);
			}
			else {
				$model->updateStatus(WaterRequests::SUBMITTED_STATUS);
			}
			//Yii::log(print_r(($model->isEditable())?'TRUE':'FALSE', true), CLogger::LEVEL_INFO);
			//Yii::log(print_r($model->attributes, true), CLogger::LEVEL_INFO);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
			else
			{
				// TODO: salvo lo stato per convertirlo in swWaterRequests/blabla e ri-valido per avere ->getErrors()
				// Trovare il modo di settare lo stato (magari salvando in updateStatus)
				$model->save(true, array('status'));
				$model->validate();
				//Yii::log('VALIDATO', CLogger::LEVEL_INFO);
			}
		}
		//Yii::log(print_r(($model->isEditable())?'TRUE':'FALSE', true), CLogger::LEVEL_INFO, 'NON SALVATO');
		//Yii::log(print_r($model->attributes, true), CLogger::LEVEL_INFO);
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Change the status of a WaterRequest
	 * @param string $id string representing the ID of a WaterRequest
	 */
	public function actionUpdateStatus($id)
	{
		// TODO: Manca il controllo su quale siano gli stati permessi
		$model=$this->loadModel($id);
		
		$wrh = new WaterRequestHistory;

		if ((isset($_POST['cancel-button'])) || (isset($_POST['save-button'])) || (isset($_POST['submit-button']))) {
			if ((!Yii::app()->user->checkAccess('cancelWaterRequest', array('waterRequest'=>$model))) ||
				(!Yii::app()->user->checkAccess('saveWaterRequest', array('waterRequest'=>$model))) || 
				(!Yii::app()->user->checkAccess('restoreWaterRequest', array('waterRequest'=>$model))) ||
				(!Yii::app()->user->checkAccess('submitWaterRequest', array('waterRequest'=>$model))) )
				throw new CHttpException(403,Yii::t('http_status', '403'));
			//these transitions don't have a WaterRequestHistory form
			//system autofill WaterRequestHistory informations
			if (isset($_POST['cancel-button']))
				$model->updateStatus(WaterRequests::CANCELLED_STATUS);
			else if (isset($_POST['save-button']))
				$model->updateStatus(WaterRequests::SAVED_STATUS);
			else
				$model->updateStatus(WaterRequests::SUBMITTED_STATUS);
			
			$wrh->wr_id = $model->id;
			$wrh->timestamp = date(Yii::app()->params['dateTimeFormatDB']);
			$wrh->comment = '';
			$wrh->status = $model->status;
			if (($wrh->save()) && ($model->save()))
				$this->redirect(array('view','id'=>$model->id));
		}
		
		if ((isset($_POST['approve-button'])) || (isset($_POST['reject-button'])) || (isset($_POST['in_future-button'])) || 
				(isset($_POST['refuse-button'])) || (isset($_POST['confirm-button'])) || (isset($_POST['timeout-button'])) || 
				(isset($_POST['in_progress-button'])) ) {
			if (!Yii::app()->user->isWRU)
				throw new CHttpException(403,Yii::t('http_status', '403'));
				
			//these transitions have a WaterRequestHistory form
			if (isset($_POST['approve-button'])) {
				$action = 'approve';
				$next_status = WaterRequests::APPROVED_STATUS;
			}
			else if (isset($_POST['reject-button'])) {
				$action = 'reject';
				$next_status = WaterRequests::REJECTED_STATUS;
				}
			else if (isset($_POST['in_future-button'])) {
				$action = 'in_future';
				$next_status = WaterRequests::IN_FUTURE_STATUS;
				}
			else if (isset($_POST['refuse-button'])) {
				$action = 'refuse';
				$next_status = WaterRequests::REFUSED_STATUS;
				}
			else if (isset($_POST['confirm-button'])) {
				$action = 'confirm';
				$next_status = WaterRequests::CONFIRMED_STATUS;
				}
			else if (isset($_POST['timeout-button'])) {
				$action = 'timeout';
				$next_status = WaterRequests::TIMEOUT_STATUS;
				}
			else if (isset($_POST['in_progress-button'])) {
				$action = 'in_progress';
				$next_status = WaterRequests::IN_PROGRESS_STATUS;
				}
			
			if (isset($_POST['WaterRequestHistory'])) {
				//WaterRequestHistory submission
				$wrh->attributes = $_POST['WaterRequestHistory'];
				$wrh->wr_id = $model->id;
				$wrh->timestamp = date(Yii::app()->params['dateTimeFormatDB']);
				if (isset($_POST['WaterRequests']))
					$model->attributes = $_POST['WaterRequests'];
				$model->updateStatus($next_status);
				$wrh->status = $model->status;
				
				if (($wrh->validate()) && ($model->validate())) {
					$transaction = Yii::app()->db->beginTransaction();
					try {
						$wrh->save();
						$model->updateOperativeMargin();
						$model->save();
						$transaction->commit();
						$this->redirect(array('view','id'=>$model->id));
					}
					catch(Exception $e){
						$transaction->rollBack();
					}
				}
				
				/*	 
				if (($wrh->save()) && ($model->save())) {
					$this->redirect(array('view','id'=>$model->id));
				}
				 */
			}
			
			$this->render('update_status',array(
				'model'=>$wrh,'wr'=>$model,'action'=>$action
			));
			
		}
	
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
			$this->loadModel($id)->delete();

			// TODO: Serve? il controllo sull'accesso alla delete viene fatto dal controller
			/*
			$model=$this->loadModel($id);
			if (!Yii::app()->user->checkAccess('deleteWaterRequest', array('waterRequest'=>$model)))
				throw new CHttpException(403,Yii::t('http_status', '403'));
			
			$model->delete();
			*/
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Upload a shapefile and load data to a WaterRequest
	 * @param string $id string representing the ID of a WaterRequest
	 * @throws CHttpException
	 */
	public function actionUpload($id)
	{
		$model=$this->loadModel($id);
		//if(!$model)
		//	throw new CHttpException(400,'Invalid request. Wrong parameters. Model not found.');
		if (!Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		
		$this->layout='//layouts/column1';
		$model->scenario='upload';
		//Yii::log('Model loaded: '.print_r($model, true) , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
		if(isset($_POST['WaterRequests']))
        {
			//Yii::log('Sto caricando un file' , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
        	$model->shpfile=CUploadedFile::getInstance($model,'shpfile');
        	$model->shxfile=CUploadedFile::getInstance($model,'shxfile');
        	$model->dbffile=CUploadedFile::getInstance($model,'dbffile');
        	$model->fileproj = $_POST['WaterRequests']['fileproj'];
			//Yii::log('shpfile='.print_r($model->shpfile, true) , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
			$shp_folder = Yii::app()->params['shp_upload_folder'];
            if($model->validate())	{
            	$all_saved= $model->shpfile->saveAs(Yii::app()->basePath . $shp_folder . $model->shpfile->getname())
            			&&	($model->shxfile!=null)?$model->shxfile->saveAs(Yii::app()->basePath . $shp_folder . $model->shxfile->getname()):true
            			&&	($model->dbffile!=null)?$model->dbffile->saveAs(Yii::app()->basePath . $shp_folder . $model->dbffile->getname()):true ;
				if($all_saved)
	            {
					//Yii::log('CARICATO!' , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
					$shpfile = Yii::app()->basePath . $shp_folder . $model->shpfile->getname();
	            	if(isset($shpfile)){
	            	
	            		//echo 'DATI ESTRATTI:<pre>';
	            		//echo "\n Il sistema di riferimento selezionato &egrave;: ".$model->fileproj. "\n";
	            		$options = array('noparts' => false);
	            		$shp = new ShapeFile($shpfile, $options); // along this file the class will use file.shx and file.dbf
	            	
	            		$i = 0;
	            		while ($record = $shp->getNext()) {
	            			$shp_data = $record->getShpData();
	            			if(count($shp_data)){
	            				// read shape data
	            				// store number of parts
	            				$oneshot = 0;
	            				$wkt = 'MULTIPOLYGON(';
	            				foreach ($shp_data['parts'] as $part) {
	            					$wkt = $wkt. (($oneshot++==0)?'((':',((');
	            					$coords = array();
	            					foreach ($part['points'] as $point) {
	            						$coords[] = round($point['x'],2).','.round($point['y'],2);
	            					}
	            					$search  = array(',', ';');
	            					$replace = array(' ', ',');
	            					$subject = implode(';', $coords);
	            					$wkt = $wkt. str_replace($search, $replace, $subject);
	            					$wkt = $wkt. '))';
	            				}
	            				$wkt = $wkt. ')';
	            				$saveres = WaterRequestGeometries::save_geom($wkt, $id, $model->fileproj);
	            				if($saveres['result']){
	            					Yii::log('Salvataggio avvenuto con successo. ID inserito: '. $saveres['newid'], CLogger::LEVEL_INFO, 'actionUpload');
	            				}else{
	            					Yii::log('Salvataggio non riuscito. WR_ID: '.$id.' iterazione: '. $i, CLogger::LEVEL_INFO, 'actionUpload');
	            				}
	            			}
	            			//echo "\n";
	            			$i++;
	            	
	            		}
	            	
	            		//echo '</pre>';
	            	
	            	}
	            	
	            	$this->redirect(array('view','id'=>$model->id));
	            	/*
	            	$this->render('upload',array(
						'wr_id'=>$id,
						'model'=>$model,
						'shpfile'=>Yii::app()->basePath . $shp_folder . $model->shpfile->getname(),
					));	
					*/
					Yii::app()->end();
	            }
//	            else Yii::log('FAIL!!' , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
        	}
//            else Yii::log('NON VALIDO!!' , CLogger::LEVEL_INFO, 'actionUpload');  // DEBUG
            
        }
		$this->render('upload',array(
			'wr_id'=>$id,
			'model'=>$model,
		));	
	
	}	
	
	
	/**
	 * Lists all models.
	 * Users viewing restrictions apply.
	 * @param string $municipality Filter on the WaterRequests
	 */
	public function actionIndex($municipality=null)
	{
		if (!Yii::app()->user->checkAccess('listWaterRequest'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		//Yii::log(print_r($_GET, true), CLogger::LEVEL_INFO, 'INDEX');
		$this->layout='//layouts/column1';
		
		$criteria = new CDbCriteria;
		if(Yii::app()->user->isWRUT)
			$criteria=new CDbCriteria(array(
            	'order'=>'status ASC',
        	));
		
		if (Yii::app()->user->isWRU) {
			/* In the first period, a WRU can view all Water Requests, in all statuses*/
			/* uncomment these line to filter the view */
			//$criteria->compare('status','<>'.WaterRequests::SW_NODE(WaterRequests::SAVED_STATUS),false,'OR');
			//$criteria->compare('status','<>'.WaterRequests::SW_NODE(WaterRequests::CANCELLED_STATUS),false,'OR');
			if($municipality){
				$oneshot=0;
				$autoctoni=Users::model()->findAllByAttributes(array('municipality'=>$municipality));
				if(!count($autoctoni))
					$criteria->compare('id',-99,false,'AND');
				foreach ($autoctoni as $user)
				{
						$criteria->compare('username',$user->username,false,(!$oneshot++?'AND':'OR'));
				}
				//Yii::log(print_r($criteria, true), CLogger::LEVEL_INFO, 'criteria INDEX');
				
			}	
		}
		else {
			$criteria->compare('username',Yii::app()->user->id,false,'AND');
			// Showing all WaterRequests created by other users with user's same municipality too
			if($municipality){
				//Yii::log($municipality, CLogger::LEVEL_INFO, 'INDEX con municipality');
				$users_like_me=Users::model()->findAllByAttributes(array('municipality'=>Yii::app()->user->municipality));
				foreach ($users_like_me as $user)
				{
					if($user->username!=Yii::app()->user->id)
						$criteria->compare('username',$user->username,false,'OR');
				}
			}
		}	

		// show only statuses the user can see
		$criteria->addInCondition('replace(status, \''.WaterRequests::WORKFLOW.'/'.'\',\'\')',Yii::app()->user->whatCanSee());
		
		// show only desired status
		if(isset($_GET['status']) && in_array($_GET['status'], WaterRequests::allStatuses()))
			$criteria->compare('status',WaterRequests::WORKFLOW.'/'.$_GET['status'],false,'AND');
		
		//Yii::log(print_r($criteria, true), CLogger::LEVEL_INFO, 'INDEX con municipality - criteria');
		$dataProvider=new CActiveDataProvider(
								WaterRequests::model()->no_tmp(),
								array(
    								'criteria'=>$criteria,
									'sort'=>array(
											'defaultOrder'=>'timestamp DESC',
									),
									'pagination'=>array(
											'pageSize'=>9,
									),
										
	    						)
	    					);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'municipality'=>$municipality,
			'gridview'=>isset($_GET['gridview'])?($_GET['gridview']=='true'):false,
			'status'=>isset($_GET['status'])?$_GET['status']:'all',
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new WaterRequests('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WaterRequests']))
			$model->attributes=$_GET['WaterRequests'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Update the uploaded EPANET file adding thw WaterRequest info and sends it to the client
	 * @param string $id string representing the ID of a WaterRequest
	 */
	public function actionEpanet($id)
	{
		
		$this->layout='//layouts/column1';
		
		//retrieve water request
		$wr = $this->loadModel($id);
		
		if (Yii::app()->user->checkAccess('epanetWaterRequest', array('waterRequest'=>$wr))) {

			$model = new EpanetForm;
			
			//parametrized values available
			$replaceable_parameters = array(
					'first_name'=>'First name of water request\'s owner',
					'last_name'=>'Last name of water request\'s owner',
					'date'=>'Water Request date',
					'project'=>'Water Request project name',
					'index'=>'Autoincrement index'
			);
			
			//parametrized values marker
			$replaceable_parameters_marker = '$';
			
			//form field in which user can insert parametrized values
			$form_elements = array(/*'junction_id','description',*/'tag','demand_pattern','demand_categories','emitter_coeff','initial_quality','source_quality', 'srid','other_srid');
			
			if(isset($_POST['EpanetForm'])) {
				//setting directory to retrieve the uploaded file and to store the new file
				$up_dir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['EPANET']['upload_dir'].DIRECTORY_SEPARATOR;
				$down_dir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['EPANET']['download_dir'].DIRECTORY_SEPARATOR;
				
				//retrieving information about uploaded file
				$file_info = pathinfo($_POST['EpanetForm']['filename']); 
				$filename = $file_info['filename'];
				$ext = $file_info['extension'];
				
				//setting upload and download directory-filename
				$up_file_dir = $up_dir.$filename.'.'.$ext;
				$down_filename = $filename.Yii::app()->params['EPANET']['download_filename_suffix'];
				$down_file_dir = $down_dir.$down_filename.'.'.$ext;
				
				//read epanet network from file
				$epanet = new EPANET($up_file_dir);
				
				//retrieving some parameters from water request
				$first_name = $wr->username;
				$last_name = $wr->username;
				$date = $wr->dateHR;
				$project = $wr->project;
				$wr_id=$wr->id;
				
				// setting prefix
				$junction_prefix=Yii::app()->params['EPANET']['junction_prefix'];
				if($junction_prefix !== '')
					$junction_prefix=substr(Yii::app()->params['EPANET']['junction_prefix'], 0, 3).'_';
				$junction_prefix=$junction_prefix.$wr_id;
				
				$index = 0;
				
				//loop into geometries 
				foreach ($wr->geometries() as $geom) {
					$water_demand = 0;
					$description = '';
					$zones_array = array();
					//loop into zones to calculate total water demand for each geometry
					foreach ($geom->zones() as $zone) {
						$water_demand+=$zone->water_demand;
						if(array_search($zone->zone_name, $zones_array)===false)
							array_push($zones_array, $zone->zone_name);
					}
					foreach ($zones_array as $zs)
					{
						$description = $description.$zs.' ';
					}
					//substitute, from form elements, water request parameters 
					foreach($form_elements as $element) {
						$str = $_POST['EpanetForm'][$element];
						foreach ($replaceable_parameters as $param=>$text) {
							$$element = str_replace($replaceable_parameters_marker.$param,$$param,$str);
							$str = $$element;
						}
					}
					
					//be sure to have all parameters before invoking addJunction
					
					// junction_id is in config file
					//if (!isset($junction_id))
					//	$junction_id = 'a';
	
					// La descrizione viene generata a partire dalle Zone della geometria
					//if (!isset($description))
					//	$description = 'b';				
	
					if (!isset($tag))
						$tag = 'c';				
	
					if (!isset($demand_pattern))
						$demand_pattern = 'd';	
						
					if (!isset($demand_categories))
						$demand_categories = '';
						
					if (!isset($emitter_coeff))
						$emitter_coeff = '';
						
					if (!isset($initial_quality))
						$initial_quality = '';
						
					if (!isset($source_quality))
						$source_quality = '';
					
					//check if srid passed exists
					if(isset($srid) && $srid == 'other')
						if(isset($other_srid) && Geometry::Srid_Exists($other_srid))
						{
							$srid = $other_srid;
							Yii::app()->user->usedSrid($srid);
						}
						else
						{
							echo 'Incorrect srid';
							Yii::app()->end();					
						}
					
					$elevation = $geom->elevation;
					//Yii::log('Elevation: '.print_r($elevation, true) , CLogger::LEVEL_INFO, 'actionEpanet');  // DEBUG
					
					
					if(isset($srid) && $srid != Yii::app()->params['geoserver']['water_request_geometries_srid'])
						$centroid = $geom->getCentroid($srid);  // converte le coordinate
					else
						$centroid = $geom->centroid;
								
					//$junction_id = $junction_prefix.'_'.$geom->id.'_'.$junction_id;
					$junction_id = $junction_prefix.'_'.$geom->id;
					//add geometry centroid and parameters to epanet
					$epanet->addJunction($junction_id, $centroid['x'], $centroid['y'], $description, $tag, $elevation, $water_demand , $demand_pattern, $demand_categories, $emitter_coeff, $initial_quality, $source_quality);
					$index++;
				}
				//write epanet network into file
				$epanet->finalize($down_file_dir);
			
				//send file	
				Yii::app()->request->sendFile(
	    			$down_filename.'.'.$ext,
	    			file_get_contents($down_file_dir),
	    			$ext,
	    			true
	  			);
				
				// Non riesce a fare il redirect e appende al file .inp la pagina wr/view
	  			//$this->redirect('view',array('id'=>$wr->id)); 
				return;					
			}
			
			$this->render('epanet',array(
				'wr'=>$wr,'model'=>$model,'replaceable_parameters'=>$replaceable_parameters,'replaceable_parameters_marker'=>$replaceable_parameters_marker
			));
		}
		else
			throw new CHttpException(403,Yii::t('http_status', '403')); 
		
	}
	
	/**
	 * Handle the upload of an inp file
	 * @param string $qqfile unused parameter
	 */
	public function actionEpanetFileUpload($qqfile=null)
	{
		if (!Yii::app()->user->checkAccess('uploadEpanet'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("inp");
		// max file size in bytes
		$sizeLimit = Yii::app()->params['EPANET']['upload_max_size'] * 1024 * 1024;

		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$dir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['EPANET']['upload_dir'].DIRECTORY_SEPARATOR;
		$result = $uploader->handleUpload($dir,true);
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	/**
	 * Handle the upload of an compressed archive file
	 * @param string $qqfile unused parameter
	 */
	public function actionZipFileUpload($qqfile=null)
	{
		if (!Yii::app()->user->checkAccess('uploadZip'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("zip", "tar", "tgz", "gz", "rar", "7z");
		// max file size in bytes
		$sizeLimit = Yii::app()->params['transition']['upload_max_size'] * 1024 * 1024;

		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$dir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['transition']['upload_dir'].DIRECTORY_SEPARATOR;
		$result = $uploader->handleUpload($dir);
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	/**
	 * Generate a summary of the specified WaterRequest as a pdf file and sends it to the client
	 * @param string $id string representing the ID of a WaterRequest
	 */
	public function actionPdf($id)
	{
		$model=$this->loadModel($id);
		if (!Yii::app()->user->checkAccess('pdfWaterRequest', array('waterRequest'=>$model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		//retrieve water request
		//$wr = $this->loadModel($id);
		$html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('_pdf', array('data'=>$model), true));
        $pdf_content = $html2pdf->Output('', true); 
		
		Yii::app()->request->sendFile(
    			'Water_Request_'.$id.'.pdf',
    			$pdf_content,
    			'pdf',
    			'false'
  			);
	}
	
	/**
	 * Action to generate pdf files for all WaterRequests
	 */
	public function actionAllPdf() {
		$wr = WaterRequests::model()->findAll();
		foreach ($wr as $model) {
			$id = $model->id;
			//retrieve water request
			//$wr = $this->loadModel($id);
			$html2pdf = Yii::app()->ePdf->HTML2PDF();
	        $html2pdf->WriteHTML($this->renderPartial('_pdf', array('data'=>$model), true));
	        $pdf_content = $html2pdf->Output('', true); 
			
			$out_filename = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['pdf_dir'].DIRECTORY_SEPARATOR.'Water_Request_'.$id.'.pdf';
			$out_file = fopen($out_filename, "w");
			fwrite($out_file,$pdf_content);
			fclose($out_file);
		}
		Yii::app()->end();
	}
	
	
	/**
	 * Action to show WaterRequest info tooltip
	 * @param string $wr_id
	 * @throws CHttpException
	 */
	public function actionInfoHistory($wr_id=null) {
		$model=$this->loadModel($wr_id);
		if (!Yii::app()->user->checkAccess('viewWaterRequest', array('waterRequest'=>$model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));
				
		$dataProvider=new CActiveDataProvider(WaterRequestHistory::model(), array(
				'criteria'=>array(
						'condition'=>'wr_id=:wr_id',
						'params'=>array(':wr_id'=>$wr_id),
						'order'=>'timestamp ASC',
				),
		));
		
		$this->renderPartial(
								'_history_tooltip',
								array(
										'model'=>$model,
										'dataprovider'=>$dataProvider
										),
								false,
								false
							);
		Yii::app()->end();
		return;
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=WaterRequests::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-requests-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
