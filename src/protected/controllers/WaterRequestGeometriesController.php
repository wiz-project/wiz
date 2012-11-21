<?php

class WaterRequestGeometriesController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update','delete','showzones','zones','rename','popupeditgeom'),
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

	
	public function actionZones($id) {
		$model=WaterRequestGeometries::model()->findByAttributes(
			array(
				'id'=>$id
			)
		);
		if($model != null)
			echo $this->renderPartial('//waterRequestGeometryZones/_ajax_list', array('model'=>$model));
		else 
			echo "non trovato";
		Yii::app()->end();
	}
	
	
	/**
	 * Render the editing popup for a geometry
	 * @param string $id Geometry ID
	 * @param string $new_wkt the new geometry as WKT
	 */
	public function actionPopupEditGeom($id,$new_wkt)
	{
		$geom_model = WaterRequestGeometries::model()->findByPk($id);
		if (!$geom_model)
			throw new CHttpException(404,'The requested page does not exist.');
							
		//Yii::app()->clientScript->scriptMap=array('jquery.yiiactiveform.js'=>false,'jquery.uniform.js'=>false,'jquery.js'=>false,);
		$this->renderPartial('_popupeditgeom', array('model'=>$geom_model, 'new_wkt'=>$new_wkt, '$new_name'=>$new_name), false, true );
		Yii::app()->end();
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if($this->deleteIfOrphan($model))
			throw new CHttpException(404,'The requested page does not exist.');
		
		// TODO: si potrebbe fare una "getParentWr($model, $destroyOrphan=true)" per evitare la doppia findByPk
		
		$wr_model=WaterRequests::model()->findByPk($model->wr_id);
		if (!Yii::app()->user->checkAccess('updateWaterRequest', array('waterRequest'=>$wr_model)))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['WaterRequestGeometries']))
		{
			$model->attributes=$_POST['WaterRequestGeometries'];
			$model->geom=new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($_POST['WaterRequestGeometries']['geom'])));
			$model->altitude = null;
			if($model->save())
				echo 'ok';
				//$this->redirect(array('view','id'=>$model->id));
		}

		//$this->render('update',array('model'=>$model,));
	}

	/**
	 * Rename a single geometry.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be renamed
	 */
	public function actionRename($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//Yii::log( print_r($_POST, true) , CLogger::LEVEL_INFO, "Rename" );
		if(isset($_POST['WaterRequestGeometries']))
		{
			// generic assignment // TODO: rendere fisso a 'name' ?
			$model->attributes=$_POST['WaterRequestGeometries'];
//			$model->geom=new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($_POST['WaterRequestGeometries']['geom'])));
			if($model->save())
				echo CJSON::encode(array('status'=>'rinominata '.$model->id));
			else
				Yii::log(print_r($_POST['WaterRequestGeometries'].'Tentativo di rinominare la geometria fallito Geom_id='.$model->id, CLogger::LEVEL_INFO, 'Rename'));
		}

		//$this->render('update',array('model'=>$model,));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// TODO: Check permessi
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$geom_model=$this->loadModel($id);
			if(!$geom_model)
				throw new CHttpException(400,'Geometria non trovata!');

			if ($geom_model->zones()) {
				foreach ($geom_model->zones() as $zone)
				{
					if ($zone->properties()) {
						foreach ($zone->properties() as $property){
							Yii::log("Cancello Property $property->id", CLogger::LEVEL_INFO, 'DELETE Geom');
							$property->delete();
						}
					}
					Yii::log("Cancello Zone $zone->id", CLogger::LEVEL_INFO, 'DELETE Geom');
					$zone->delete();
				}
			}

			Yii::log("Cancello Geometry $geom_model->id", CLogger::LEVEL_INFO, 'DELETE Geom');
			$geom_model->delete();
			
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
		$dataProvider=new CActiveDataProvider('WaterRequestGeometries');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new WaterRequestGeometries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WaterRequestGeometries']))
			$model->attributes=$_GET['WaterRequestGeometries'];

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
		$model=WaterRequestGeometries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Check if the Geometry has an existing WaterRequest parent.
	 * If the parent is not found, the geometry is deletted.
	 * @param WaterRequestGeomtries the model to check
	 * @return true if the geometry was orphan and deletted, false otherwise
	 */
	public function deleteIfOrphan($model){
		
		$wr_model=WaterRequests::model()->findByPk($model->wr_id);
		if($wr_model===null)
		{
			//orphan geometry
			if ($model->zones()) {
				foreach ($model->zones() as $zone)
				{
					if ($zone->properties()) {
						foreach ($zone->properties() as $property){
							Yii::log("Cancello Orphan Property $property->id", CLogger::LEVEL_INFO, 'DELETE Geom');
							$property->delete();
						}
					}
					Yii::log("Cancello Orphan Zone $zone->id", CLogger::LEVEL_INFO, 'DELETE Geom');
					$zone->delete();
				}
			}
			Yii::log("Cancello Orphan Geometry $geom_model->id", CLogger::LEVEL_INFO, 'DELETE Geom');
			$model->delete();
			return true;
		}
		return false;		
	}
	
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='water-request-geometries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
