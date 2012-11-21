<?php

class SettingsController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			)
		);
	}
	
	/**
	 * Lists all categories associated with the user's role.
	 * @return array of models
	 */
	public function actionIndex()
	{
		$role_name = Yii::app()->user->getRole();
		if($role_name==NULL)
			throw new CHttpException(400,Yii::t('http_status',400));
        
		$models = array();
		$categories = array();
		
		$modelCategories=NotificationCategories::model()->findAllByAttributes(array(),'role_name=:role_name',array(':role_name'=>$role_name));
		foreach($modelCategories as $modelCategory) {
			$category['category'] = $modelCategory->category;
			$category['type'] = $modelCategory->type;
			$category['id'] = $modelCategory->id;
			$model=$this->loadModel($category['id']);
			if($model===null) {
				$model = new Settings;
				$model->setAttribute('send_mail',true);
				$category['model'] = $model;
			}
			else
				$category['model'] = $model;
			$categories[] = $category;
		}

		$this->render('index',array(
			'categories'=>$categories
		));
	}
	
	/**
	 * Updates the settings of notifications
	 * Inserts a record in "settings" table only if the user does not want to receive mail
	 * @return array of models
	 */
	public function actionUpdate()
	{
		$role_name = Yii::app()->user->getRole();
		if($role_name==NULL)
			throw new CHttpException(400,Yii::t('http_status',400));
    
		$models = array();
		$categories = array();
		$modelCategories=NotificationCategories::model()->findAllByAttributes(array(),'role_name=:role_name',array(':role_name'=>$role_name));
		$index = 0;
		foreach($modelCategories as $modelCategory) {
			$category['category'] = $modelCategory->category;
			$category['type'] = $modelCategory->type;
			$category['id'] = $modelCategory->id;
			$model=$this->loadModel($category['id']);
			if($model===null) {
				$model = new Settings;
				if($_POST['send_mail'.$index] == "false") {
					$model->attributes=array('username'=>Yii::app()->user->id,'notification_category_ptr'=>$category['id']);
					$model->save();
				} else 
					$model->setAttribute('send_mail',true);
				$category['model'] = $model;
			}
			else {
				if($_POST['send_mail'.$index] == "true") {
					$model->delete();
					$model = new Settings;
					$model->setAttribute('send_mail',true);
				}
				$category['model'] = $model;
			}
			$index++;
			$categories[] = $category;
		}
		
		$this->render('update',array(
			'categories'=>$categories
		));
	}
	
	/**
	 * Overrides the "Controller" method
	 */
	public function primaryKey()
	{
		return array('username','notification_category_ptr');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $notification_category_ptr the identifier of the model to be loaded
	 */
	public function loadModel($notification_category_ptr)
	{
		$model=Settings::model()->findByPk(array('username'=>Yii::app()->user->id, 'notification_category_ptr'=>$notification_category_ptr));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>