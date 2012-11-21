<?php

/**
 * This is the model class for table "notification_categories".
 *
 * The followings are the available columns in table 'notification_categories'
 * @property int $id the progressive notification category
 * @property string $category the name of category
 * @property string $type the name of type operation
 * @property string $role_name the role associated with the notification category
 */
class NotificationCategories extends CActiveRecord
{
	
	const CREATE = 'create';
	const SUBMIT = 'submit';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return NotificationCategory the static model class
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
		return 'notification_categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('id','unique'),
			
			//required
			array('category,type,role_name', 'required'),
			
			//safe
			array('role_name', 'safe'),
			
			//The following rule is used by search().
			array('category,type,role_name', 'safe', 'on'=>'search'),
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
			'role'=>array(self::BELONGS_TO, 'Roles', 'role_name'),
			'notifications'=>array(self::HAS_MANY, 'Notifications','id'),
			'settings'=>array(self::HAS_MANY, 'Settings','id'),
			'users'=>array(self::HAS_MANY,'Users',array('role_name'=>'role_name'),'through'=>'roles'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category' => 'Users category',
			'type' => 'Operation',
			'role_name' => 'Referring role'
		);
	}
}