<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $first_name
 * @property string $last_name
 * @property string $municipality
 * @property string $organisation
 * @property string $title
 * @property string $email
 * @property string $username
 * @property string $password
 * @property boolean $active
 * @property integer $role_name
 * @property boolean $approved
 * @property string $last_login
 * @property string $activation_link
 */
class Users extends CActiveRecord
{
	
	/**
	 * @var string the 'repeat password' field in the create form (this field is only for the form; it isn't saved into db)
	 */
	public $repeat_password;
	
	/**
	 * @var string the character to be used to split the activation link into two parts: username and link
	 * @TODO put this into config file
	 */
	private $ACTIVATION_LINK_SEPARATOR = '-';
	
	/**
	 * @var string a 'salt'. It is used to hash the user password. NOTE: this must be secret!
	 * @todo put this into config file 
	 */
	private $salt = "9fUzRJ6dJHDtiiTQJQaJjQNduGZjEF";
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			//primary key
			array('username','unique'),
			
			//unique
			array('email','unique'),
			
			//required
			array('username, first_name, last_name, municipality, email, password, role_name', 'required'),
			array('repeat_password', 'required','on'=>'new_user'),
			
			//max_length
			array('first_name, last_name, municipality, organisation, title, username, password, ', 'length', 'max'=>255),
			array('repeat_password','length','max'=>255),
			array('email', 'length', 'max'=>100),
			
			//min_length
			array('password','length','min'=>4),
			array('repeat_password','length','min'=>4),
			
			// compare password to repeated password
            array('password', 'compare', 'compareAttribute'=>'repeat_password','on'=>'new_user'),
            
			//role validator
			array('role_name', 'roleValidator', 'on'=>'new_user'),
			
			//safe
			array('active, approved, last_login', 'safe'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('first_name, last_name, municipality, organisation, title, email, username, password, active, role, approved, last_login ', 'safe', 'on'=>'search'),
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
			'categories'=>array(self::BELONGS_TO, 'NotificationCategories',array('role_name'=>'role_name')),
			'notifications'=>array(self::HAS_MANY,'Notifications',array('id'=>'notification_category_ptr'),'through'=>'categories','joinType'=>'INNER JOIN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'municipality' => 'Municipality',
			'organisation' => 'Organisation', 
			'title' => 'Title',
			'email' => 'Email',
			'username' => 'Username',
			'password' => 'Password',
			'repeat_password' => 'Repeat Password',
			'active' => 'Active',
			'role' => 'Role',
			'role_name' => 'Role',
			'approved' => 'Approved',
			'last_login' => 'Last Login',
			'activation_link' => 'Activation Link',
		);
	}

	/**
 	* @param string $attribute the name of the attribute to be validated
 	* @param array $params options specified in the validation rule
 	*/
	public function roleValidator($attribute,$params)
	{
		$roles = Roles::model()->registration_roles()->findAll();
		foreach ($roles as $role) {
			if ($role->name===$this->$attribute)
				return true;
		}
		$this->addError($attribute, 'Specify a right role!');
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('activation_link',$this->activation_link,true);
		$criteria->order = 'last_name ASC';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/** 
	 * Returns a string that contains the user's first name and last name separated by a space
	 * @return string the user's first name and last name
	 */
	public function getName()
	{
		return $this->first_name.' '.$this->last_name;
	}
	
	public function getRoleDescription()
	{
		return $this->role->description;
	}
	
	public function getRoleName()
	{
		return $this->role->name;
	}
		
	/** Generates an encrypted password, calculating the md5 hash
	 * @param string $password a password to be encrypted
	 * @return string encrypted password
	 */
    public function hashPassword($password) {
        return md5($this->salt.$password);
    }
	
	/**
	 * For a given password, it generates an encrypted password using { @link hashPassword } and compare 
	 * that with the password field, returning true if they are equal (false otherwise) 
	 * @param string $password
	 * @return boolean true if hash password and password field are equal (false otherwise)
	 */
	public function validatePassword($password) {
        return $this->hashPassword($password)===$this->password;
    }
	
	/**
	 * Generates a random password
	 * @param int $length the number of characters
	 * @return string encrypted password
	 */
	public function generatePassword($length = 8) {

		$password = "";

		// define possible characters ( to avoid confusing users, pairs of characters which look similar have also been left out )
		$possibleChars = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
		$maxlength = strlen($possibleChars);
  
		// check for length overflow and truncate if necessary
		if ($length > $maxlength) {
			$length = $maxlength;
		}
	
		// set up a counter for how many characters are in the password so far
		$i = 0; 
    
		// add random characters to $password until $length is reached
		while ($i < $length) { 

			// pick a random character from the possible ones
			$char = substr($possibleChars, mt_rand(0, $maxlength-1), 1);
        
			//verify if we have already used this character in $password
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}

		return $password;
	}
	
	/** 
	 * This method is invoked before saving a record.
	 * It encrypts password with a hash of password field, generated by { @link hashPassword }
	 * @return boolean whether the saving should be executed
	 */
    public function beforeSave() {
        
		//Make sure you call the parent implementation so that the event is raised properly
		if(parent::beforeSave()) {
		
			//perform this operation only before FIRST save into db
			if($this->isNewRecord) {
				//hash password	
				$pass = $this->hashPassword($this->password);
				$this->password = $pass;
		
				//generate the activation link prefixed by username and ACTIVATION_LINK_SEPARATOR
				//activation_link=<username>-<sha1(mt_rand(10000,99999).time().email)>
				//i.e. :
				//username=foo
				//activation_link=foo-8e84f52a000831136c2cbf5500d51a7b4055a7c
				$activation_link = $this->username.$this->ACTIVATION_LINK_SEPARATOR.sha1(mt_rand(10000,99999).time().$this->email);
				$this->activation_link = $activation_link;
				
				//activate user account
				$this->active = 1;
				$this->approved = 1;
				if($this->role->name == 'planner') {
					//if role is planner, the registration must be approved by admin
					//$this->active = 0;
					$this->approved = 0;
				}					
				
				//last login			
				$this->last_login = null;
			}
			return true;
		}
		else
			return false;
    }

	/** 
	 * This method is invoked after saving a record.
	 * It creates a new Notification item
	 */
	public function afterSave()
	{
		//perform this operation only in the first save		
  		if($this->isNewRecord && $this->role->name == 'planner') {
			//Notification
			Notifications::generate('Users',$this->username,'users','create');
  		}
  		return parent::afterSave();
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
	
	/**
	 * Converts timestamp into a string human readable
	 * The datetime format is specifed by dateTimeFormat param in config/main.php
	 * 
	 */
	public function getTimestampHR() {
		$date = strtotime($this->last_login);
		try{
			$ret = date(Yii::app()->params['dateTimeFormat'],$date);
		}
		catch(Exception $e){
			$ret = date('j F Y, H:i',$date);//default datetime format
		}
		return $ret;
	}
	
}