<?php

/**
 * This is the model class for table "notification".
 *
 * This class handles the sending of reports produced by the execution of some operations performed on the system.
 * The followings are the available columns in table 'notifications'.
 * @property int $id the progressive notification 
 * @property date $timestamp the creation date of notification 
 * @property string $description the type of notification 
 * @property string $link the url to access the object of the notification
 * @property boolean $read the flag read notification occurs
 * @property int $notification_category_ptr the category of notification
 */
class Notifications extends CActiveRecord
{
	public $send_mail = true;
	public $mail_body = "";
	public $mail_subject = "";
	public $address = "";
	public $owner = "";
	public $config_file = "notifications_config.php";
	public $link = array(
		"waterrequests" => "?r=waterRequests/view&id=", 
		"users" => "?r=users/view&id=",
		"approve" => "?r=users/approve&id=",
	);

	/**
	 * Returns the static model of the specified AR class.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notifications';
	}

	/**
	 * @return array validation rules for model attributes
	 */
	public function rules()
	{
		return array(

			//primary key
			array('id','unique'),
			
			//required
			array('link, description, notification_category_ptr', 'required'),
			
			//max_length
			array('description', 'length', 'max'=>250),
			
			//safe
			array('read', 'safe'),
			
			//The following rule is used by search().
			array('timestamp,description,notification_category_ptr', 'safe', 'on'=>'searchAll'),
		);
	}

	/**
	 * @return array relational rules
	 */
	public function relations()
	{
		return array(
			'category'=>array(self::BELONGS_TO,'NotificationCategories',array('notification_category_ptr'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'timestamp' => 'Timestamp',
			'role_name' => 'Role',
			'link' => 'Link',
			'read' => 'Read',
			'description' => 'Description',
			'notification_category_ptr' => 'Category'
		);
	}

	/**
	 * Converts timestamp into a string human readable
	 * The datetime format is specifed by dateTimeFormat param in config/main.php
	 * 
	 */
	public function getTimestampHR() {
		$date = strtotime($this->timestamp);
		try{
			$ret = date(Yii::app()->params['dateTimeFormat'],$date);
		}
		catch(Exception $e){
			$ret = date('j F Y, H:i',$date);//default datetime format
		}
		return $ret;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
	 */
	public function searchAll()
	{
	    $criteria=new CDbCriteria;
		
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('notification_category_ptr',$this->notification_category_ptr,true);
	    return new CActiveDataProvider(get_class($this), array(
	        'criteria'=> $criteria,
	        'sort'=>array(
	            'defaultOrder'=>'timestamp DESC',
	        ),
	        'pagination'=>array(
	            'pageSize'=>10
	        ),
	    ));
	}
	
	/**
	 * Generates and sends a new notification to interested users.
	 * If the configuration file is not found or an error occurs while sending mail, logs the error on database.
	 * @param string $model_name the name of model to be loaded
	 * @param string/integer $model_id the id of model to be loaded
	 * @param string $category the category of notification
	 * @param string $type the type of notification
	 */
	public static function generate($model_name,$model_id,$category,$type,$other = null)
	{

		
		$roles = Notifications::findCategory($category,$type);
		
		foreach ($roles as $role=>$role_name) {
			$model=new Notifications;
			$model->notification_category_ptr = $role;
			
			$log_error = false;
			$file_error = false;
			$error_message = "";
			
			$link_view = Yii::app()->createAbsoluteUrl(lcfirst($model_name).'/view', array('id' => $model_id));
			
			if(file_exists(Yii::app()->basePath . '/views/' .lcfirst($model_name). '/' .$model->config_file)) {
				include(Yii::app()->basePath . '/views/' .lcfirst($model_name). '/' .$model->config_file);
				if(isset($messages[$type]) && isset($messages[$type][$role_name])) {
					if (isset($messages[$type][$role_name]['subject']))
						$model->mail_subject = $messages[$type][$role_name]['subject'];
					if (isset($messages[$type][$role_name]['description']))
						$model->mail_body = $messages[$type][$role_name]['description'];
				} else {
					$file_error = true;
					$errore_message = 'Description and mail variables for ' .lcfirst($model_name). 'category and ' .$type. 'type operation do not exist';
				}
			} else {
				$file_error = true;
				$error_message = 'File '.Yii::app()->basePath . '/views/' .lcfirst($model_name). '/' .$model->config_file.' does not exists';
			}
				
			if($file_error) {	
				if(file_exists(Yii::app()->basePath . '/views/' .$model->config_file)) {
					include(Yii::app()->basePath . '/views/' .$model->config_file);
					if(isset($description) && isset($mail)) {
						$model->mail_subject = $description;
						$model->mail_body = $mail;
					} else {
						$log_error = true;
						$errore_message = 'Description and mail variables for general category and type operation do not exist';
					}
				} else {
					$log_error = true;
					$error_message = 'File '.Yii::app()->basePath . '/views/' .$model->config_file.' does not exists';
				}
			}
			
			if(!$log_error) {
				$model_input = CActiveRecord::model($model_name)->findByPk($model_id);
				try {
					$model->owner = $model_input->username;
				}
				catch ( Exception $e ) {
					$model->owner = false;
				}
				
				foreach($model_input->attributeNames() as $attribute) {
					$model->mail_subject = str_replace('$'.$attribute,$model_input->$attribute,$model->mail_subject);
					$model->mail_body = str_replace('$'.$attribute,$model_input->$attribute,$model->mail_body);
					$model->mail_body = str_replace('$link_view',$link_view,$model->mail_body);
				}
				
				if ($model->owner) {
					$model_user = Users::model()->findByPk($model_input->username);
					foreach($model_user->attributeNames() as $attribute) {
						$model->mail_subject = str_replace('$'.$attribute,$model_user->$attribute,$model->mail_subject);
						$model->mail_body = str_replace('$'.$attribute,$model_user->$attribute,$model->mail_body);
						$model->mail_body = str_replace('-link',$model_user->activation_link,$model->mail_body);
					}
				}

				if(!empty($other))
					$model->mail_body = str_replace('$other',$other ,$model->mail_body);
				
				$model->attributes=array('description'=>$model->mail_subject,'link'=>$link_view);
				
				$t = $model->save();
				Yii::log('saving...->'.$t, CLogger::LEVEL_INFO, 'Notifications::create()');
				
			} else {
				//logs send failure
				$logs = new SendmailLogs;
				$logs->attributes = array('mail_type'=>'notifications','mail_identifier'=>0,'error_code'=>1,'error_message'=>$error_message,'address'=>$email);
				$logs->save();
			}

		}
		
	}
	
	/**
	 * Find the category of notification.
	 * @param string $category the category description
	 * @param string $type the description of the operation performed
	 * @return array $categories the roles that identify the recipients of this notification
	 */
	public static function findCategory($category,$type)
	{
		$notification_categories = NotificationCategories::model()->findAll('category=:category AND type=:type',array(':category'=>$category,':type'=>$type));
		if ($notification_categories != null) {
			$categories = array();
			foreach ($notification_categories as $nc) {
				$categories[$nc->id] = $nc->role_name;
			}
		}
		else {
			$this->notification_category_ptr = -1;
			Yii::log('category='.$category.' type='.$type, CLogger::LEVEL_INFO, 'Notifications::findCategory()');
		}
		Yii::log('categories='.print_r($categories), CLogger::LEVEL_INFO, 'Notifications::findCategory()');
		return $categories;
	}
	
	/**
	 * Sends an email to users who want to receive a notification
	 * if their role corresponds to that associated with the notification.
	 */
	public function afterSave() {
		//checks if send_mail==true and if the variable Yii::app()->params['block_email'] is set to true to inhibit the delivery of mail
		if (($this->send_mail)&&(!Yii::app()->params['block_email'])) {
			$mail_identifier = $this->primaryKey;

			if ($this->category->role_name == 'owner') {
				$users = Users::model()->findAll('username=:username',array(':username'=>$this->owner));
			}
			else {
				$users = Users::model()->findAll('role_name=:role_name',array(':role_name'=>$this->category->role_name));	
			}

			$send = false;
			$recipients = null;
			
			foreach($users as $user) {
				
				$settings = Settings::model()->findByPk(array('username'=>$user->username, 'notification_category_ptr'=>$this->notification_category_ptr));
				if ($settings == NULL) {
					//note: if settings is null the email sendign is enable!
					Yii::app()->mailer->AddAddress($user->email);
					$recipients .= $user->email.",";
					$send = true;
				}
			}
			
			if (($send)||(Yii::app()->params['debug_email'])) {
				//checks if email debugging is active
				if (Yii::app()->params['debug_email']) {
					$this->mail_subject =  "[ WIZ DEBUG EMAIL ] ".$this->mail_subject;
					$this->mail_body = "[ REAL RECIPIENTS ] => [ ".$recipients." ]<br/><br/> ".$this->mail_body;
					Yii::app()->mailer->ClearAddresses();
					Yii::app()->mailer->AddAddress(Yii::app()->params['debugEmail']);
				}
				try{
					Yii::app()->mailer->Subject = $this->mail_subject;
					$footer_text = 'THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
					$wiz_logo = 'wizlogo.png';
					$tree_logo = 'ambiente_logo.gif';
					$html_msg = '<html>' .
								' <head></head>' .
								' <body>' .
								'  <table style="width: 100%; border-bottom: solid 2px #006AB2;">'.
								'   <tr>'.
								'    <td align="left"> <img src="'.Yii::app()->getBaseUrl(true).'/images/'.$wiz_logo.'" height="70" width="74"/></td>'.
								'   </tr>'.
								'  </table>'.
								'  <br/>'.
								'  <table style="width: 100%; border-bottom: solid 2px #006AB2;">'.
								'   <tr>'.
								'    <td align="left"> '.$this->mail_body.'</td>'.
								'   </tr>'.
								'   <tr>'.
								'    <td align="left"> &nbsp; </td>'.
								'   </tr>'.
								'  </table>'.
								'  <table style="width: 100%;">'.
								'   <tr>'.
								'    <td align="left"> <img src="'.Yii::app()->getBaseUrl(true).'/images/'.$tree_logo.'" height="86" width="286"/></td>'.
								'   </tr>'.
								'   <tr>'.
								'    <td align="left"> '.$footer_text.'</td>'.
								'   </tr>'.
								'  </table>'.
								' </body>' .
								'</html>';
					Yii::app()->mailer->MsgHTML($html_msg);
					Yii::app()->mailer->Send();
				} catch ( Exception $e ) {
					//logs send failure
					$logs = new SendmailLogs;
					$logs->attributes = array('mail_type'=>'notifications','mail_identifier'=>$mail_identifier,'error_code'=>$e->getCode(),'error_message'=>$e->getMessage(),'address'=>$user->email);
					$logs->save();
				}
			}		
		}
	}
}