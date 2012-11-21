<?php

class UsersController extends Controller
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
			array('allow',  // allow all users to perform 'index','view' and 'create' actions
				'actions'=>array('index','view','create','update','admin','delete','approve','approveViaLink'),
				'users'=>array('*'),
			),
			/*array('allow', // allow authenticated user to perform 'update' actions
				'actions'=>array('update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','approve'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),*/
		);
	}

	/**
	 * Displays a particular model.
	 * @param string $id the ID of the model to be displayed
	 * @param string $redirect
	 */
	public function actionView($id,$redirect = null)
	{
		$model=$this->loadModel($id);
		if (!Yii::app()->user->checkAccess('viewProfile', array('users'=>$model))) {
			Yii::app()->user->setState('redirect', Yii::app()->request->requestUri);
			$this->redirect(array('/site/login'));
		}
		$this->render('view',array(
			'model'=>$model,
			'redirect'=>$redirect,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the page specified by the parameter $redirect.
	 * @param string $redirect
	 */
	public function actionCreate($redirect = null)
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model->setScenario('new_user');
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			$model->role_name=$_POST['Users']['role_name'];
			if($model->save()) {
				if(empty($_POST['redirect']))
					$this->redirect(array('view','id'=>$model->username));
				else {
					Yii::app()->user->setFlash('create',Yii::t('user', 'Registration completed successfully.'));
					$this->redirect(array($redirect));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'redirect'=>$redirect
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the page specified by the parameter $redirect.
	 * @param integer $id the ID of the model to be updated
	 * @param string $redirect
	 */
	public function actionUpdate($id,$redirect = null)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->username,'redirect'=>$redirect));
		}

		$this->render('update',array(
			'model'=>$model,
			'redirect'=>$redirect,
		));
	}

	/**
	 * Changes old password.
	 * If update is successful, the browser will be redirected to the page specified by the parameter $redirect.
	 * @param integer $id the ID of the model to be updated
	 * @param string $redirect
	 */
	public function actionChangepwd($id,$redirect = null) 
	{
		$model=$this->loadModel($id);
		
		if(isset($_POST['Users']))
		{
			$model->setAttribute('password',$_POST['Users']['password']);
			if($model->password != "") {
				if($model->password == $_POST['Users']['repeat_password']) {
					$model->password = $model->hashPassword($model->password);
					if($model->save()) {
						Yii::app()->user->setFlash('changepwd',Yii::t('user', 'The new password has been successfully saved.'));
						$this->redirect(array($_POST['redirect']));
					} 
				} else
					$model->addError('repeat_password',Yii::t('user', 'Password must be repeated exactly'));
			} else
				$model->addError('password',Yii::t('user', 'Password can not be null'));
		}
		
		$model->setAttribute('password','');
		$this->render('changepwd',array(
			'model'=>$model,
			'redirect'=>$redirect,
		));
	}
	
	/**
	 * Sets the active flag, associated with the user, to false.
	 * If disabling is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionDelete($id)
	{
		if (!Yii::app()->user->isSysAdmin)
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			//$this->loadModel($id)->delete();
			$model = $this->loadModel($id);
			$model->setAttribute('active',false);
			$save = $model->save();
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']) && $save)
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Approves a role for the user.
	 * If approval is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be approved
	 */
	public function actionApprove($id) 
	{
		if (!Yii::app()->user->isSysAdmin)
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		$model=$this->loadModel($id);

		if(isset($_POST['Users']))
		{
			$model->setAttribute('role_name',$_POST['Users']['role_name']);
			$model->setAttribute('approved',true);
			if($model->save()) {
				$model=new Users('search');
				$this->redirect(array('admin'),array(
					'model'=>$model,
				));
			}
		}

		$this->render('approve',array(
			'model'=>$model,
		));
	}
	
	
	public function actionApproveViaLink($link) 
	{
		$model=Users::model()->findByAttributes(array('activation_link'=>$link));

		$msg = '';
		$approved = false;
		
		if ($model) {
			if ($model->approved === false) {
				$model->setAttribute('approved',true);
				if($model->save()) {
					$approved = 'true';
					$msg = '';
					Notifications::generate('Users',$model->username,'users','approve');
				}
				else
					$msg = 'Saving error';
			}
			else
				$msg = 'Already approved';
		}
		else
			$msg = 'Link not valid';
		
		$this->render('approve_via_link',array('approved'=>$approved, 'msg' => $msg));
		return;
	}
	
	
	
	
	/** 
	 * Generates a new random password and creates a new Notification item
	 */
	public function actionRetrieve($id,$redirect) {
	
		$model=$this->loadModel($id);
		
		$new_pass = Users::model()->generatePassword();
		$model->attributes=array('password'=>Users::model()->hashPassword($new_pass));
		$model->save();
		//Notification
		Notifications::generate('Users',$model->username,'users','retrieve',$new_pass);
		if($redirect == 'admin')
			Yii::app()->user->setFlash('success',Yii::t('user', 'The new password is generated and was sent to user e-mail.'));
		else
			Yii::app()->user->setFlash('retrieve',Yii::t('user', 'The new password is generated and was sent to your e-mail.'));
		
		$this->redirect(array($redirect));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if (!Yii::app()->user->checkAccess('listProfile'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if (!Yii::app()->user->isSysAdmin)
			throw new CHttpException(403,Yii::t('http_status', '403'));
		
		$this->layout='//layouts/column1';
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string the username of the model to be loaded
	 */
	public function loadModel($username)
	{
		$model=Users::model()->findByPk($username);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
