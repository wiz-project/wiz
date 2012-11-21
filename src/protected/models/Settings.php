<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings'
 * @property string $username the user identifier
 * @property int $notification_category_ptr the notification category identifier
 * @property boolean $send_mail if the user does not want to receive notification, it is set to false
 */
class Settings extends CActiveRecord
{
	
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
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('username,notification_category_ptr','unique'),
			
			// The following rule is used by search().
			array('send_mail', 'safe', 'on'=>'search'),
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
			'users'=>array(self::BELONGS_TO, 'Users', 'username'),
			'categories'=>array(self::BELONGS_TO, 'NotificationCategories', array('notification_category_ptr'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('settings','Username'),
			'notification_category_ptr' => Yii::t('settings','Category'),
			'send_mail' => Yii::t('settings','Receiving e-mail')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('username',$this->username,true);
		$criteria->compare('notification_category_ptr',$this->notification_category_ptr,true);
		$criteria->compare('send_mail',$this->send_mail,true);
		return new CActiveDataProvider(get_class($this), array(
	        'criteria'=>$criteria
		));
	}
	/**
	 * This function internationalize the labels using Yii::t()
	 * @see CActiveRecord::getAttributeLabel()
	 */
	public function getAttributeLabel($attribute)
	{
		$baseLabel = parent::getAttributeLabel($attribute);
		return Yii::t('user', $baseLabel);
	}
	
}