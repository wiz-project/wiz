<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
		
	//private $_username;
		
	const ERROR_USERNAME_INACTIVE = 3;
	const DEFAULT_ROLE = 'citizen';
	
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
        //retrive user
        $record=Users::model()->findByAttributes(array('username'=>$this->username));
		
		//check if user exists
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else {
        	//check if password is correct
			if(!$record->validatePassword($this->password))
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			else {
				//check if account is active
				if (!$record->active)
					$this->errorCode = self::ERROR_USERNAME_INACTIVE;
				else {
					//authenticate and assign role
					$this->username=$record->username;
					$auth=Yii::app()->authManager;
					if(!$auth->isAssigned($record->role_name,$this->username)) {
						if(!$record->approved) {
							if(!$auth->isAssigned(self::DEFAULT_ROLE,$this->username)) {
								if($auth->assign(self::DEFAULT_ROLE,$this->username))
									Yii::app()->authManager->save();
							}
						} else {
							//revoke the previous assignment 
							if($auth->isAssigned(self::DEFAULT_ROLE,$this->username)) {
								if($auth->revoke(self::DEFAULT_ROLE,$this->username) && $auth->assign($record->role_name,$this->username))
									Yii::app()->authManager->save();
							} else {
								if($auth->assign($record->role_name,$this->username))
									Yii::app()->authManager->save();
							}
						}
					} 

					$this->errorCode=self::ERROR_NONE;
				}
			}
		}
        return !$this->errorCode;
    }
	
	/*
	public function getUsername() {
        return $this->_username;
    }*/
}