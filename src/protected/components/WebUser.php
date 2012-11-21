<?php

/**
 * Overload of CWebUser to set some more methods.
 */
class WebUser extends CWebUser
{
		
	/**
	 * Store model to not repeat query.
	 */ 
	private $_model;
	
	
	/**
	 * Returns users's first name.
	 * Access it by Yii::app()->user->first_name
	 */ 
	function getFirstName(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user)
			return $user->first_name;
	}
	
	/**
	 * Returns users's name, that is fisrt name and last name.
	 * Access it by Yii::app()->user->name
	 */ 
	function getName(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user)
			return $user->first_name.' '.$user->last_name;
	}
	
	/**
	 * Returns user's view page url.
	 * Access it by Yii::app()->user->profile
	 */ 
	function getProfile(){
		$username=Yii::app()->user->id;
		$route='user/view';
		$params=array('id'=>$id);
		return Yii::app()->createUrl($route,$params); 
	}
	

	/**
	 * Returns active field
	 * Access it by Yii::app()->user->active
	 */ 
	public function getActive() {
        $user = $this->loadUser(Yii::app()->user->id);
		if ($user)
			return $user->active;
    }
	
	/**
	 * Returns email address
	 * Access it by Yii::app()->user->email
	 */ 
	public function getEmail() {
        $user = $this->loadUser(Yii::app()->user->id);
		if ($user)
			return $user->email;
    }

    /**
     * Returns municipality
     * Access it by Yii::app()->user->municipality
     */
    public function getMunicipality() {
    	$user = $this->loadUser(Yii::app()->user->id);
    	if ($user)
    		return $user->municipality;
    }
    
	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to sys_admin, that means it's sys_admin
	 * access it by Yii::app()->user->isSysAdmin
	 */ 
	function getIsSysAdmin(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return $user->getRoleName() === 'sys_admin';
		}
		else
			return false;
	}


	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to planner, that means it's planner
	 * access it by Yii::app()->user->isPlanner
	 */ 
	function getIsPlanner(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'planner' && $user->approved);
		}
		else
			return false;
	}

	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to WRUT or WRUA
	 * access it by Yii::app()->user->isWRU
	 */ 
	function getIsWRU(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return (($user->getRoleName() === 'wrut' || $user->getRoleName() === 'wrua') && $user->approved);
		}
		else
			return false;
	}
	
	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to WRUT, that means it's WRUT
	 * access it by Yii::app()->user->isWRUT
	 */ 
	function getIsWRUT(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'wrut' && $user->approved);
		}
		else
			return false;
	}
	
	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to WRUA, that means it's WRUA
	 * access it by Yii::app()->user->isWRUA
	 */ 
	function getIsWRUA(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'wrua' && $user->approved);
		}
		else
			return false;
	}
	
	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to citizen, that means it's citizen
	 * access it by Yii::app()->user->isCitizen
	 */ 
	function getIsCitizen(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'citizen' || Yii::app()->authManager->isAssigned('citizen',Yii::app()->user->id));
		}
		else
			return false;
	}

	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to developer, that means it's developer
	 * access it by Yii::app()->user->isDeveloper
	 */ 
	function getIsDeveloper(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'developer' && $user->approved);
		}
		else
			return false;
	}	
	
	/**
	 * This is a function that checks the field 'role'
	 * in the User model to be equal to member, that means it's registered user
	 * access it by Yii::app()->user->isMember()
	 */ 
	function isMember(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user) {
			return ($user->getRoleName() === 'member' && $user->approved);
		}
		else
			return false;
	}

	/**
	 * Returns role object
	 * Access it by Yii::app()->user->role->name
	 */
	function getRole(){
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->getRoleName();
	}
	
	/**
	 * Checks if there are unread notifications sent to the user logged
	 *
	 */
	public function unreadNotifications() {
		if(!Yii::app()->user->isGuest) {
			$user = $this->loadUser(Yii::app()->user->id);
			$notifications = $user->notifications(array('condition'=>'read=false'));
			if($notifications != null)
				return count($notifications);
			else
				return 0;
		}
		return 0;
	}
	
	/**
	 * Checks if there are categories of notifications associated with the role of the user logged
	 * Returns true if they exist, false otherwise
	 *
	 */
	public function haveSettings() {
		if(!Yii::app()->user->isGuest) {
			$user = $this->loadUser(Yii::app()->user->id);
			$settings =NotificationCategories::model()->find('role_name=:role_name',array(':role_name'=>Yii::app()->user->getRole()));
			if(empty($settings))
				return false;

			return true;
		}
	}
	
	/**
	 * Load user model.
	 */
	protected function loadUser($username=null) {
        if($this->_model===null) {
            if($username!==null)
                $this->_model=Users::model()->findByPk($username);
        }
        return $this->_model;
    }
    
    /**
     * Load recently used srids.
     */
    public function getSrids()
    {
    	// Notare che l'array è formattato per essere direttamente inserito in dropDownList
    	$retarray = array('32232'=>'32232','3003'=>'3003','4326'=>'4326','900913'=>'900913');
    	if(Yii::app()->db->schema->getTable('user_srids')){
    		//Yii::log('La tabella c\'e\'!', CLogger::LEVEL_INFO, 'getSRIDS' );
      		$usersrids = UserSrids::model()->findAllByAttributes(array('username'=>$this->_model->username));
    		foreach ($usersrids as $key => $value)
    			if(!array_key_exists($value->srid, $retarray))
    				$retarray[$value->srid] = $value->srid;
    	}
    	else {
    		Yii::log('La tabella NON c\'e\'!', CLogger::LEVEL_INFO, 'getSRIDS' );
    	}
    	$retarray['other'] = 'Altro';  // TODO: internazionalizzare 'altro'
    	return $retarray;
    }
    
    /**
     * Update recently used srids.
     * @param string $srid Used srid
     */
    public function usedSrid($srid=null){
    	
    	if($srid == null)
    		return;
    	
    	$user = $this->loadUser(Yii::app()->user->id);
    	$lowest_id = null;
    	
    	if(Yii::app()->db->schema->getTable('user_srids')){
    		//Yii::log('La tabella c\'e\'!', CLogger::LEVEL_INFO, 'usedSrid' );
    		$usersrids = UserSrids::model()->findAllByAttributes(array('username'=>$user->username));
    		foreach ($usersrids as $key => $value){
    			if($value->srid == $srid)
    				return;  // No need to update table
    			if($lowest_id===null)
    				$lowest_id = $value->id;
    			else
    				if($lowest_id>$value->id)
    					$lowest_id = $value->id;
    		}
    		// Yii::log('$lowest_id = '.$lowest_id, CLogger::LEVEL_INFO, 'usedSrid' );  // DEBUG
    		// srid not found, must insert
    		if(count($usersrids)>=6){ // TODO: parametrizzare il 6?
    			// remove oldest srid
    			UserSrids::model()->deleteByPk($lowest_id);
    			// Yii::log('Deletted ID = '.$lowest_id, CLogger::LEVEL_INFO, 'usedSrid' );  // DEBUG
    		}
    		// add new srid
    		$newsrid = new UserSrids();
    		$newsrid->srid = $srid;
    		$newsrid->username = $this->_model->username;
    		$newsrid->save();
    	}
    	else {
    		Yii::log('La tabella NON c\'e\'!', CLogger::LEVEL_INFO, 'usedSrid' );
    	}
    	return;
    }
    /**
     * This is a function returns all statuses a user can see
     * access it by Yii::app()->user->whatCanSee
     */
    function whatCanSee(){
    	
    	if(Yii::app()->params['allstatuses'])
    		return array(
						WaterRequests::SAVED_STATUS,
						WaterRequests::SUBMITTED_STATUS,
						WaterRequests::CANCELLED_STATUS,
						WaterRequests::APPROVED_STATUS,
						WaterRequests::REJECTED_STATUS,
						WaterRequests::IN_FUTURE_STATUS,
						WaterRequests::CONFIRMED_STATUS,
						WaterRequests::REFUSED_STATUS,
						WaterRequests::IN_PROGRESS_STATUS,
						WaterRequests::TIMEOUT_STATUS,
						WaterRequests::COMPLETED_STATUS,
						);
    	    	    	
    	$auth = array(
		 		'planner' => array(
						WaterRequests::SAVED_STATUS,
						WaterRequests::SUBMITTED_STATUS,
						WaterRequests::CANCELLED_STATUS,
						WaterRequests::APPROVED_STATUS,
						WaterRequests::REJECTED_STATUS,
						WaterRequests::IN_FUTURE_STATUS,
						WaterRequests::CONFIRMED_STATUS,
						WaterRequests::REFUSED_STATUS,
						WaterRequests::IN_PROGRESS_STATUS,
						WaterRequests::TIMEOUT_STATUS,
						WaterRequests::COMPLETED_STATUS,
						),
  	 			'wrut' => array(
    					WaterRequests::SAVED_STATUS,
    					WaterRequests::CANCELLED_STATUS,
    					WaterRequests::SUBMITTED_STATUS,
    					WaterRequests::REJECTED_STATUS,
    			    	WaterRequests::APPROVED_STATUS,
    					),
    			'wrua' => array(
    					WaterRequests::APPROVED_STATUS,
    					WaterRequests::REFUSED_STATUS,
    					WaterRequests::IN_FUTURE_STATUS,
    					WaterRequests::CONFIRMED_STATUS,
    					WaterRequests::IN_PROGRESS_STATUS,
    					WaterRequests::COMPLETED_STATUS,
    					)
    			);
    	    	
    	$user = $this->loadUser(Yii::app()->user->id);
    	if ($user) {
    		//Yii::log('dentro if', CLogger::LEVEL_INFO, 'canSee');
    		$role_name = $user->getRoleName();
    		if(array_key_exists($role_name, $auth )){
    			return $auth[$role_name];
    		}
    		else return	array(
								WaterRequests::TEMP_STATUS,
		    					WaterRequests::SAVED_STATUS,
								WaterRequests::SUBMITTED_STATUS,
								WaterRequests::CANCELLED_STATUS,
								WaterRequests::APPROVED_STATUS,
								WaterRequests::REJECTED_STATUS,
								WaterRequests::IN_FUTURE_STATUS,
								WaterRequests::CONFIRMED_STATUS,
								WaterRequests::REFUSED_STATUS,
								WaterRequests::IN_PROGRESS_STATUS,
								WaterRequests::TIMEOUT_STATUS,
								WaterRequests::COMPLETED_STATUS,
						); // default, all statuses
    	}
    	else
    		return array();  // user not found, empty array
    }

    /**
     * This is a function that checks if the user can see a WaterRequest
     * access it by Yii::app()->user->canSee
     */
    function canSee($status=null){
    	//Yii::log(print_r($status, true), CLogger::LEVEL_INFO, 'canSee');
    	if(!$status)
    		return false;
    	    	
    	$user = $this->loadUser(Yii::app()->user->id);
    	if ($user) {
    		//Yii::log('dentro if', CLogger::LEVEL_INFO, 'canSee');
    		$status_list = Yii::app()->user->whatCanSee();

    		return in_array($status, $status_list);

    	}
    	else
    		return false;  // user not found

    }

     /**
     * This is a function that checks if the user can see a WaterRequestHistory
     * access it by Yii::app()->user->canSeeHistory()
     */
    function canSeeHistory($status=null){
    	//Yii::log(print_r($status, true), CLogger::LEVEL_INFO, 'canSee');
    	if(!$status)
    		return false;
    
    	$auth = array(
	   			'planner'=>array(
	    					WaterRequests::IN_FUTURE_STATUS,
	    					WaterRequests::REJECTED_STATUS,
	    					WaterRequests::REFUSED_STATUS,
	    			)
  			);
    
    	$user = $this->loadUser(Yii::app()->user->id);
    	if ($user) {
    		$role_name = $user->getRoleName();
    		if(array_key_exists($role_name, $auth )){
    			return in_array($status, $auth[$role_name]);
    		}
    		return true; // default, allow anyone
    	}
    	else
    		return false;  // user not found
    }
    
}