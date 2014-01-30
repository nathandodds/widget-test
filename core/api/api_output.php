<?php

abstract class API_output
{
	/**
	 * An array of status codes used for callback
	 * 
	 * @var array
	 */
	protected $status_codes = array('200' => 'OK', 
									'201' => 'Created',
									'204' => 'No content',
									'400' => 'Bad Request',
									'403' => 'Forbidden', 
									'401' => 'Unauthorised', 
									'404' => 'Not found',
									'405' => 'Method not allowed'

	);


	/**
	 * The output type - set by the child class
	 * 
	 * @var string
	 */
	protected $output_type = 'json';


	/**
	 * Handles the callback of the error if there is one for the API
	 * 
	 * @param int $status
	 * @param optional string $message
	 */
	public function error_handler($status, $message="")
	{
		if ($this->output_type == 'json') {

			$content_type = 'Content-Type: application/json; charset=UTF-8';
			$output = Output_json::build_error($status, $message);
			
		} else {

			$content_type = 'Content-Type: application/xml';
			$output = Output_xml::build_error($status, $message);
		}

		$this->header_status($status, $this->status_codes[$status]);

		header($content_type);

		exit($output);
	}


	/**
	 * Set's the header status for the output content
	 * 
	 * @param int $code
	 * @param optional string $text
	 */
	protected function header_status($code, $text="")
	{
		header('HTTP/1.1 '.$code.' '.$text, true, $code);
	}
}

?>