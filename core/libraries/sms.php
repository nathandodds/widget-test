<?php

class sms
{
	private $textmarketer = null;

	public $from_number = 'Text marketer';

	/**
	 * Immediately set the textmarketer api with the username and password
	 * 
	 * @param string $username
	 * @param string $password
	 * @param optional string $from - the number to be recipent
	 */
	public function __Construct($username, $password, $from=null)
	{
		$this->textmarketer = new Textmarketer_api($username, $password);

		if (!!$from) {
			$this->from_number = $from;
		}
	}

	/**
	 * Send the SMS message
	 * 
	 * @param string $message - the $_GET['message'] parameter
	 * @param string $number - the $_GET['number'] parameter
	 * @return bool
	 */
	public function send($message, $number)
	{
		if (!!$message && !!$number) {
			
			$output = $this->textmarketer->send($number, $message, $this->from_number);	

			return $output;

		} else {
			return false;
		}
	}

}

?>