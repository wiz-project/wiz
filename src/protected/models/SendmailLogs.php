<?php

/**
 * This is the model class for table "sendmail_logs".
 *
 * The followings are the available columns in table 'sendmail_logs'
 * @property int $id_log the progressive log 
 * @property datetime $timestamp the date of the error feedback
 * @property string $mail_type the type of operation
 * @property int $mail_identifier the identifier of model
 * @property int $result_send the result of operation
 * @property int $error_code the error code
 * @property string $error_message the error message
 * @property string $address the address where the mail was addressed
 */
class SendmailLogs extends CActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return SendmailLogs the static model class
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
		return 'sendmail_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('id_log','unique'),
			
			//required
			array('mail_type,mail_identifier,address', 'required'),
			
			//safe
			array('error_message,error_code', 'safe'),
			
			//The following rule is used by search().
			array('timestamp,mail_type,result_send,error_message,address', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'users'=>array(self::BELONGS_TO, 'Users', array('address'=>'email')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'timestamp' => 'Date & Time',
			'mail_type' => 'Category',
			'result_send' => 'E-mail received',
			'error_message' => 'Error message',
			'error_code' => 'Error code',
			'address' => 'Recipient address',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('mail_type',$this->mail_type,true);
		$criteria->compare('result_send',$this->result_send,true);
		$criteria->compare('error_message',$this->error_message,true);
		$criteria->compare('error_code',$this->error_message,true);
		$criteria->compare('address',$this->address,true);
		return new CActiveDataProvider(get_class($this), array(
	        'criteria'=>$criteria
		));
	}
}