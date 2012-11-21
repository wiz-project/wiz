<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}


	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				if (Yii::app()->user->getState('redirect')){
					$redirect = Yii::app()->user->getState('redirect');
					Yii::app()->user->setState('redirect', '');
					$this->redirect($redirect);
				}
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/** 
	 * Generates a new random password and creates a new Notification item
	 */
	public function actionRetrieve() {
		$model=new LoginForm;
		
		if(isset($_POST['LoginForm'])) {
			$user=Users::model()->findByPk($_POST['LoginForm']['username']);
			if($user===null)
				$model->addError('username',Yii::t('user','Username not valid'));
			else {
				$new_pass = Users::model()->generatePassword();
				$user->attributes=array('password'=>Users::model()->hashPassword($new_pass));
				$user->save();
				//Notification
				Notifications::generate('Users',$user->username,'users','retrieve',$new_pass);
				// display the login form
				Yii::app()->user->setFlash('retrieve','The new password was sent to you via email.');
				$this->redirect(array('login'));
			}
		}
		// display the retrieve form
		$this->render('retrieve',array('model'=>$model));
	}
	
	public function actionScreenshot()
	{
		if(isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
			$rawImage = $GLOBALS['HTTP_RAW_POST_DATA'];
			$removeHeaders = substr($rawImage, strpos($rawImage, ",")+1);
			$decode = base64_decode($removeHeaders);
			$image_name = "screenshot_".date("YmdHHmmii");
			$fopen = fopen('screenshot/'.$image_name.'.png', 'wb');
			fwrite($fopen, $decode);
			fclose($fopen);
			
			echo $image_name;
		}
	}
	
	public function actionFeedback() 
	{
		if(isset($_POST)) {
			//send e-mail to administrator
			Yii::app()->mailer->AddAddress(Yii::app()->params['adminEmail']);
			Yii::app()->mailer->Subject = 'Feedback Wiz';
			
			$note = $_POST['note'];
			if(empty($note))
				$note = "No notes posted.";
			$content = "<table cellspacing=\"10px\" cellpadding=\"10px\"><tr><td style=\"font-weight:bold\" align=\"right\">User Agent</td><td>".Yii::app()->browser->getUserAgent()."</td><tr><td style=\"font-weight:bold\" align=\"right\">User Note</td><td style=\"font-style:italic\">".$note."</td></tr>";
			
			try {
				$filename = Yii::app()->basePath . '/../screenshot/'.$_POST['screenshot'].'.png';
				if(file_exists($filename))
					Yii::app()->mailer->AddAttach($filename);
				Yii::app()->mailer->MsgHTML($content);
				$result = Yii::app()->mailer->Send($content);
			} catch ( Exception $e ) {
				//logs send failure
				$result = 'error';
				$logs = new SendmailLogs;
				$logs->attributes = array('mail_type'=>'feedback','mail_identifier'=>0,'error_code'=>$e->getCode(),'error_message'=>$e->getMessage(),'address'=>Yii::app()->params['adminEmail']);
				$logs->save();
			}
			echo $result;
		}
	}	
}