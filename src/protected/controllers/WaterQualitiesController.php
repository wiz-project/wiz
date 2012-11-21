<?php

class WaterQualitiesController extends Controller
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
		if (!Yii::app()->user->checkAccess('viewQuality'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$model=new WaterQualities('search');
		$model->unsetAttributes();  // clear any default values
	    if(isset($_GET['WaterQualities']))
	        $model->attributes =$_GET['WaterQualities'];
		
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
		if (!Yii::app()->user->checkAccess('createQuality'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$model=new WaterQualities;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model->setScenario('new_water_qualities');
		if(isset($_POST['WaterQualities']))
		{
			$model->attributes=$_POST['WaterQualities'];
			if($_POST['WaterQualities']['color']{0} != '#') 
				$model->setAttribute('color','#'.$_POST['WaterQualities']['color']);
			$model->setAttribute('active', true);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		if (!Yii::app()->user->checkAccess('viewQuality'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$model=$this->loadModel($id);
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the identifier of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if (!Yii::app()->user->checkAccess('updateQuality'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WaterQualities']))
		{
			$model->attributes=$_POST['WaterQualities'];
			if($_POST['WaterQualities']['color']{0} != '#') 
				$model->setAttribute('color','#'.$_POST['WaterQualities']['color']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$model=WaterQualities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('http_status',404));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-quality-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>