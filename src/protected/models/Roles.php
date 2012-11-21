<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles'
 * @property string $name
 * @property string $description
 * @property boolean $active
 * @property boolean $on_registration
 * @property boolean $searchable
 */
class Roles extends CActiveRecord
{
	
	private $DEFAULT_ROLE = 'citizen';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Roles the static model class
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
		return 'roles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('name','unique'),
			
			//required
			array('name,description', 'required'),
			
			//max_length
			array('description', 'length', 'max'=>50),
			
			//safe
			array('description', 'safe'),
			
			// The following rule is used by search().
			array('name,description', 'safe', 'on'=>'search'),
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
			'users'=>array(self::HAS_MANY, 'Users', 'role_name'),
			
		);
	}
	
	/**
	 * 
	 */
	public function scopes()
    {
        return array(
            'active_roles'=>array(
                'condition'=>'active=true',
            ),
            'registration_roles'=>array(
                'condition'=>'active=true AND on_registration=true',
            ),
            'searchable_roles'=>array(
                'condition'=>'searchable=true',
            ),
			'admin_role'=>array(
                'condition'=>'name=\'sys_admin\'',
            ),
			'planner_role'=>array(
                'condition'=>'name=\'planner\'',
            ),
			'citizen_role'=>array(
                'condition'=>'name=\'citizen\'',
            ),
            'wrut_role'=>array(
                'condition'=>'name=\'wrut\'',
            ),
            'wrua_role'=>array(
                'condition'=>'name=\'wrua\'',
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'description' => 'Descrizione',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	    $criteria=new CDbCriteria;
		$criteria->compare('name',$this->id,true);
		$criteria->compare('description',$this->description,true);
	    return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function defaultRole() {
		return $this->DEFAULT_ROLE;
	}
	
	public function getRolename() {
		return Yii::t('roles', $this->name);
	}
	
	public static function registrationList()
	{
		$roles = Roles::model()->registration_roles()->findAll();
		$list = CHtml::listData($roles, 'name', 'rolename');
		return $list;
	}
	
	public static function searchableList()
	{
		$roles = Roles::model()->searchable_roles()->findAll();
		$list = CHtml::listData($roles, 'name', 'rolename');
		return $list;
	}
	
	public static function adminRole()
	{
		return Roles::model()->admin_role()->find()->name;
	}
	
	public static function plannerRole()
	{
		return Roles::model()->planner_role()->find()->name;
	}
	
	public static function citizenRole()
	{
		return Roles::model()->citizen_role()->find()->name;
	}
	
	public static function wrutRole()
	{
		return Roles::model()->wrut_role()->find()->name;
	}
	
	public static function wruaRole()
	{
		return Roles::model()->wrua_role()->find()->name;
	}
}