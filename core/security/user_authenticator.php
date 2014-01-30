<?php

class User_Authenticator extends Abstract_authenticator implements IAuthenticator
{
	/**
	 * Authenticates a users credentials
	 * against the database using the users model
	 * 
	 * @param array $credentials
	 * @return boolean
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$user = new $this->_model();

		$one = $user->find_where_username_password(array('username' => $username,
												  		 'password' => sha1($password)
											       ));

		if (!!$user->id) {

			if (!!$user->roles) {
				// Roles are stored as serialized strings within the database
				$roles = unserialize($user->roles);
			}

			$identity = new Identity($user->id, $roles);

			$result = true;

		} else {
			$result = false;
		}

		return $result;
	}
}

?>