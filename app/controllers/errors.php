<?php

class errors extends C_Controller
{
	public function error_404()
	{
		header("HTTP/1.0 404 Not Found");
		$this->setView('errors/404');
	}
}

?>