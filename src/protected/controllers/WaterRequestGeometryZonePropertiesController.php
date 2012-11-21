<?php

class WaterRequestGeometryZonePropertiesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','showpropsform'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	 * List all related WaterRequestGeometryZoneProperties.
	 * This is only a proxy function, the real rendering is done by the WaterRequestGeometryZoneProperties view.
	 */
	public function actionShowPropsForm($zone_type) {

		$prop_model = new WaterRequestGeometryZoneProperties;
		$params = array();
		$zone = $zone_type;
		while ($zone!=null) {
			$params = ZonesWaterRequestParameters::model()->active_parameters()->findAll('zone=:zone',array(':zone'=>$zone));
			if ($params)
				break;
			$zone = Zones::parentZone($zone);
		}
		
		echo $this->renderPartial('//waterRequestGeometryZoneProperties/_not_a_form', array('model'=>$prop_model, 'params'=>$params));
		//echo $this->renderPartial('//waterRequestGeometryZoneProperties/_properties', array('model'=>$prop_model, 'zone_type'=>$zone_type, 'params'=>$params));

		Yii::app()->end();
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		//Yii::log('$geometry_zone='.$geometry_zone , CLogger::LEVEL_INFO, 'wrgzProperties Create');  // DEBUG
		
		$model=new WaterRequestGeometryZoneProperties;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['WaterRequestGeometryZoneProperties']))
		{
			$model->attributes=$_POST['WaterRequestGeometryZoneProperties'];
/*			
 * 			// Serve fare questo controllo?
			// Ho imposto la relazione geometry_zone come chiave esterna
 * 			$ref_zone = WaterRequestGeometryZones::model()->find('id=:id', array(':id'=>$geometry_zone));
			if (count($ref_zone)){
				Yii::log('Trovato $ref_zone='.$ref_zone->id.' type='.$ref_zone->zone , CLogger::LEVEL_INFO, 'wrgzProperties Create');  // DEBUG
				$model->geometry_zone=$geometry_zone;
				
			}
			else {
				Yii::log('NON Trovato ' , CLogger::LEVEL_INFO, 'wrgzProperties Create');  // DEBUG
				throw new CHttpException(404,'Wrong geometry_zone.');
			}
	*/		if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'zone_type'=>$ref_zone->zone
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$ref_zone = WaterRequestGeometryZones::model()->find('id=:id', array(':id'=>$model->geometry_zone));
		if (!count($ref_zone))
				throw new CHttpException(404,'The Property has a wrong geometry_zone.');
		
		if(isset($_POST['WaterRequestGeometryZoneProperties']))
		{
			$model->attributes=$_POST['WaterRequestGeometryZoneProperties'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'zone_type'=>$ref_zone->zone
		));
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
		$dataProvider=new CActiveDataProvider('WaterRequestGeometryZoneProperties');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new WaterRequestGeometryZoneProperties('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WaterRequestGeometryZoneProperties']))
			$model->attributes=$_GET['WaterRequestGeometryZoneProperties'];

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
		$model=WaterRequestGeometryZoneProperties::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-request-geometry-zone-properties-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
