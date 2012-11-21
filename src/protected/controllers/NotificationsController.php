<?php

class NotificationsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
			array('allow',  // allow all users to perform 'index','view' and 'create' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			/*
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),*/ 
		);
	}

	/**
	 * Displays all modules associated with a given role
	 */
	public function actionView()
	{
		$model=new Notifications('searchAll');
		if(isset($_GET['Notifications']))
	        $model->attributes =$_GET['Notifications'];

			$params =array(
	        'model'=>$model,
	    );
	 
		return $params;
	}

	/**
	 * Updates a particular model.
	 * @param integer $id the identifier of the model to be updated
	 */
	public function actionUpdate()
	{
	
		$model=Notifications::model()->findByPk($id);
		
		$role = 'sys_admin';
		//'params'=>array(':role_name'=>Yii::app()->user->getRole()),
		$model->attributes=array('read'=>true);
		if($model->save()) {
			$rawData=Notifications::model()->with('category')->findAll(array(
				'condition'=>'category.role_name=:role_name',
				'params'=>array(':role_name'=>$role),
				'order'=>'t.timestamp DESC',
			));
			$dataProvider=new CArrayDataProvider($rawData);
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
				'page_size'=>10
			));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($what)
	{
		if (!Yii::app()->user->checkAccess('viewNotifications'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$save = true;
		if(isset($_POST) && !empty($_POST)) {
			for($i=0; $i<count($_POST['cid']); $i++) {
				$model=Notifications::model()->findByPk($_POST['cid'][$i]);
				$model->attributes=array('read'=>true);
				$model->send_mail = false;
				if(!$model->save())
					$save = false;
			}
			$what = $_POST['what'];
			if($save)
				Yii::app()->user->setFlash('notification_success',Yii::t('notifications','Notifications have been updated as read.'));
			else
				Yii::app()->user->setFlash('notification_error',Yii::t('notifications','An error occurred in data storage. Please try again.'));
		}
	
		$condition = 'category.role_name=:role_name';
		if($what == 'unread')
			$condition .= ' AND read=false';
			
		$rawData=Notifications::model()->with('category')->findAll(array(
            'condition'=>$condition,
            'params'=>array(':role_name'=>Yii::app()->user->getRole()),
            'order'=>'t.timestamp DESC',
        ));
		$dataProvider=new CArrayDataProvider($rawData);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'what'=>$what,
			'page_size'=>10
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $id the identifier of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Notifications::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>