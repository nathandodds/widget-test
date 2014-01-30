<?php

class Identity
{
	/**
	 * The storage object to use
	 * 
	 * @var object $storage
	 */
	private $storage;

	public function __Construct($id = null, $roles = null, $key = 'user')
	{
		$this->storage = new Storage($key);

		if (!!$id) {
			$this->set_id($id);
		}
	}

	/**
	 * Set the storage ID
	 * 
	 * @param string $id 
	 */
	public function set_id($id)
	{	
		$result = $this->storage->set('id', $id);

		return $result;
	}
	
}

?>