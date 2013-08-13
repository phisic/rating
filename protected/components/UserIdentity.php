<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	private $_id;
	private $_username;

	public function authenticate()
	{
		$attributes=array('username'=>$this->username);
		$users_model=Users::model()->findByAttributes($attributes);
		if($users_model===null){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}elseif(!$users_model->validatePassword($this->password)){
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}else
		{
			$this->_id=$users_model->id;
			$this->_username=$users_model->first_name.' '.$users_model->last_name;
			//$this->setState('name', $user_model->first_name.' '.$user_model->last_name);
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	/**
     * get user id
     * @return return user id
     */
	public function getId()
	{
		return $this->_id;
	}

	/**
     * get user name
     * @return userName
     */
	public function getName()
	{
		return $this->_username;
	}
}