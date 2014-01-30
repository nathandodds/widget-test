<?php

class Storage
{
	/**
	 * The main key of the storage array
	 * 
	 * @var string $type
	 */
	public $type;

	public function __Construct($key = "") 
	{
		if (!!$key) {
			$this->type = $key;
		}
	}

	/**
	 * Set the session with the key and value
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value)
	{
		if (!isset($_SESSION[$this->type][$key])) {
			$_SESSION[$this->type][$key] = $value;

			$result = $_SESSION;
		} else {
			$result = false;
		}

		return $result;
	}

	/**
	 * Retrieve a storage item by a given key
	 * 
	 * @param optional string|int $key
	 * @param optional string $sub 
	 * @return array
	 */
	public function get($key = null, $sub = null)
	{
		$storage = $_SESSION[$this->type];

		if (!!$key) {
			$storage = $storage[$key];	

			if (!!$sub) {
				$storage = $storage[$sub];
			}
		}

		return $storage;
	}
}


?>