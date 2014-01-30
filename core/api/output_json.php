<?php

class Output_json extends API_Output implements api_output_interface
{
	private $output_contents;

	/**
	 * Builds the error feedback message specific to JSON
	 * 
	 * @param int $status
	 * @param string $message
	 * @return object|json
	 */
	public static function build_error($status, $message="")
	{
		$output = array();
		$output['status'] = $status;
		$output['error'] = $message;

		$result = json_encode($output);

		return $result;
	}

	/**
	 * Builds up the output response for the API
	 * 
	 * @param string $content
	 * @param int $status
	 */
	public function build_output_contents($content, $status)
	{
		$output = array();

		$output['status'] = $status;

		$output['message'] = $this->status_codes[$status];

		$output['data'] = $content;

		$this->output_contents = json_encode($output);

		$this->display_output();
	}

	/**
	 * Displays the actual content 
	 */
	public function display_output()
	{
		header("Content-type: application/json", true);

		echo ($this->output_contents);
	}
}

?>