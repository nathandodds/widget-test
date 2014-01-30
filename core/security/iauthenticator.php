<?php

interface IAuthenticator 
{
	/**
	 * The method to authenticate by a given set of
	 * credentials
	 * 
	 * @param array $credentials
	 */
	public function authenticate(array $credentials);
}

?>