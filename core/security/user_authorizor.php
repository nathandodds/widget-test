<?php

class User_Authorizor
{
	private $storage;

	public function __Construct()
	{
		$this->storage = new Storage('user');
	}

	public function check()
	{
		if (!!$this->storage->get()) {
			return true;
		}
	}

	public static function authorize()
	{
		$auth = new self();
		
		return $auth->check();
	}
}

?>