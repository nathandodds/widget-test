<?php

abstract class API_connector
{
	/**
	 * Set of allowed methods for the API
	 * 
	 * @var array
	 */
	protected $allowed_methods = array('get', 
									   'post', 
									   'put', 
									   'delete'
								  );

	/**
	 * The raw requested URI as a string before delt with
	 * 
	 * @var string
	 */
	protected $request_uri_raw;


	/**
	 * The requested URI once it has been deciphered
	 * 
	 * @var array
	 */
	protected $request_uri = array();


	/**
	 * Additional parameters raw string - this is anything
	 * that is a query string within the URI
	 * 
	 * @var string
	 */
	protected $additional_params_raw;


	/**
	 * This is an associative array of specifically the 
	 * key to value for the query strings
	 * 
	 * @var array
	 */
	protected $additional_params = array();


	/**
	 * The output object that calls the class to handle
	 * the output type and format
	 * 
	 * @var object
	 */
	protected $output;


	/**
	 * The current method that has been requested
	 * Defaulting at always being get if not provided
	 * 
	 * @var string
	 */
	protected $method = 'get';


	/**
	 * The model table to point all queries to
	 * 
	 * @var object
	 */
	protected $model;


	/**
	 * Holds the binds for any query
	 * 
	 * @var array
	 */
	protected $binds = array();


	/**
	 * Constructor immediately defines the output class
	 * to use for the API - then actions the request.
	 */
	public function __Construct()
	{
		$this->process_incoming_request();
	}

	protected function setup()
	{
		$output_type = 'json';

		$table = $this->request_uri[0];

		$table_bits = explode('.', $table);

		if (!!$table_bits[1]) {
			$output_type = $table_bits[1];
		}

		$output_class = 'Output_'.$output_type;

		$this->output = new $output_class();

		$this->load_model($table_bits[0]);
	}


	/**
	 * Processes the request and determines what to do next
	 */
	public function process_incoming_request()
	{
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);

		if (!in_array($this->method, $this->allowed_methods)) {
			$this->output->error_handler(400, '"'.$this->method.'": Method not supported');
		}

		$this->request_uri_raw = strtolower(str_replace(array(DIRECTORY, 'api/'), '', $_SERVER['REQUEST_URI']));

		$request_uri = explode("/", $this->request_uri_raw);
		
		$this->request_uri = $request_uri;

		// Pass in extra arguments if their set
		if ($this->method == 'get') {
			$this->build_uri_get_params($request_uri);	
		} elseif ($this->method == 'post') {
			$this->additional_params = $_POST;
		}
	}


	/**
	 * Builds up any URI parameters into a raw string and associative array
	 * 
	 * @param array $uri
	 */
	protected function build_uri_get_params($uri = array())
	{
		foreach ($uri as $key => $value) {
			if ((string)strpos($value, "?") != "") {
				$this->additional_params_raw = substr($value, 1, strlen($value));
				$this->relate_get_params();
			}
		}
	}


	/**
	 * Relates all get parameters from the additional parameters property
	 * into an accociative array - which can be used directly for inserting values
	 * or be used to build up where clauses
	 */
	protected function relate_get_params()
	{
		$params = explode('&', $this->additional_params_raw);

		foreach ($params as $param) {
			$_param = explode('=', $param);

			if (count($_param) > 0) {
				if ($_param[0] == 'password') {
					$_param[1] = sha1($_param[1]);
				}
				$this->additional_params[$_param[0]] = $_param[1];
			}
		}

		return $this;
	}

	/**
	 * Builds and loads the relevent corresponding
	 * table model
	 */
	protected function load_model($table)
	{
		$model = $table.'_model';

		if (class_exists($model)) {
			$this->model = new $model();
		} else {
			$this->output->error_handler(500, 'Table "'.$table.'"" can not be found');
		}
	}

}

?>