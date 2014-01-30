<?php

class API 
{
	public function __Construct()
	{
		$this->process();
	}

	public function process()
	{
		$api = new API_handler();		
		$api->call();
	}
}

?>