<?php

class WaterRequestParametersController extends Controller
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
			array('allow',  // allow all users to perform 'index','view','create' and 'update' actions
				'actions'=>array('index','view','create','update'),
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new WaterRequestParameters('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WaterRequestParameters']))
	        $model->attributes =$_GET['WaterRequestParameters'];
			
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new WaterRequestParameters;
		$model->zone_request_parameters = new ZonesWaterRequestParameters;
		
		$model->setScenario('new_water_request_parameters');
		if(isset($_POST['WaterRequestParameters']))
		{
			$model->attributes=$_POST['WaterRequestParameters'];
			if($model->save()) {
				$model->zone_request_parameters->attributes=$_POST['ZonesWaterRequestParameters'];
				$model->zone_request_parameters->parameter = $model->name;
				$model->zone_request_parameters->active = true;
				if($model->zone_request_parameters->save()) 
					$this->redirect(array('view','id'=>$model->name));
				else 
					$model->delete();
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param string $id the identifier of the model to be displayed
	 */
	public function actionView($id)
	{
		$dataProvider=new CActiveDataProvider(ZonesWaterRequestParameters::model(), array(
			'criteria'=>array(
				'condition'=>'parameter=:parameter', 
				'params'=>array(':parameter'=>$id),
				'order'=>'zone ASC',
			),
		));
		$model=$this->loadModel($id);
		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id the identifier of the model to be updated
	 * @param string $zone the name of zone associate with the module
	 */
	public function actionUpdate($id,$zone)
	{
		$model=$this->loadModel($id);
		$model->zone_request_parameters = ZonesWaterRequestParameters::model()->findByPk(array('parameter'=>$id, 'zone'=>$zone));
		
		if(isset($_POST['WaterRequestParameters']))
		{
			$model->attributes=$_POST['WaterRequestParameters'];
			if($model->save()) {
				if($model->zone_request_parameters->delete()) {
					$model->zone_request_parameters = new ZonesWaterRequestParameters;
					$model->zone_request_parameters->attributes=$_POST['ZonesWaterRequestParameters'];
					$model->zone_request_parameters->parameter = $model->name;
					if($model->zone_request_parameters->save()) 
						$this->redirect(array('view','id'=>$model->name));
				}
			}
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $id the identifier of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=WaterRequestParameters::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}