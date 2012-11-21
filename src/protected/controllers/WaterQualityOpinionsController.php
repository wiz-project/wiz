<?php

class WaterQualityOpinionsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/wrapper.php'.
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
			array('allow',  // allow all users to perform 'index' and 'create' actions
				'actions'=>array('index','create'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if (!Yii::app()->user->checkAccess('giveEvaluation'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
			
		$model_quality=new WaterQualityOpinions();
		$model_fault=new WaterFaultOpinions();
		
		if(isset($_POST) && !empty($_POST)) {
			var_dump($_POST);
			if(empty($_POST['geom'])) {
				Yii::app()->user->setFlash('warning','ATTENTION: set a point on the map.');
			} else {
				if($_POST['geom_type'] == 1) {
					if(!empty($_POST['qualities_list'])) {
						$model=new WaterQualityOpinions();
						$model->quality = $_POST['qualities_list'];
						$model->geom = new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($_POST['geom'])));
						$model->username = Yii::app()->user->id;
						if($model->save()) 
							$this->redirect(array('view'));
						else
							Yii::app()->user->setFlash('error','An error occurred in data storage. Please try again.');
					} else {
						Yii::app()->user->setFlash('warning','ATTENTION: to point out the quality of the water service.');
					}
				} else {
					if(!empty($_POST['faults_list'])) {
						$model=new WaterFaultOpinions();
						$model->fault = $_POST['faults_list'];
						$model->geom = new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($_POST['geom'])));
						$model->username = Yii::app()->user->id;
						if($model->save()) 
							$this->redirect(array('view'));
						else 
							Yii::app()->user->setFlash('error','An error occurred in data storage. Please try again.');
					} else {
						Yii::app()->user->setFlash('warning','ATTENTION: to point out the fault of the water network.');
					}
				}
			}
		}
	
		$this->render('create');
	}

	/**
	 * Displays the possible operations (view/create estimation).
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$this->render('index');
	}
	
	/**
	 * Displays all models.
	 */
	public function actionView()
	{
		if (!Yii::app()->user->checkAccess('viewEvaluation'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$qualities = WaterQualities::model()->view()->findAll();
		$qualities_property = array();
		foreach ($qualities as $quality) { 
			if(empty($quality->image))
				$qualities_property[$quality->id] = array("quality"=>$quality->quality,"color"=>$quality->color);
			else
				$qualities_property[$quality->id] = array("quality"=>$quality->quality,"image"=>$quality->image);
		}
		
		$faults = WaterFaults::model()->view()->findAll();
		$faults_property = array();
		foreach ($faults as $fault) { 
			if(empty($fault->image))
				$faults_property[$fault->id] = array("fault"=>$fault->fault,"color"=>$fault->color);
			else
				$faults_property[$fault->id] = array("fault"=>$fault->fault,"image"=>$fault->image);
		}
		
		$this->render('view',array(
			'qualities_property'=>$qualities_property,
			'faults_property'=>$faults_property
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the identifier of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=WaterQualityOpinions::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('http_status', '404'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-quality-opinions-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
