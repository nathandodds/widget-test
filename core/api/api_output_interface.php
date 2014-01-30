<?php

interface Api_output_interface
{
	/**
	 * Builds the relevent formatted error message
	 * 
	 * @param int $status
	 * @param string $message
	 */
	public static function build_error($status, $message="");

	/**
	 * Builds up the relevent content
	 */
	public function build_output_contents($content, $status);

	/**
	 * Display the content for the API
	 */
	public function display_output();
}

?>