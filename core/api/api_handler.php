<?php

class API_handler extends API_Connector
{
	/**
	 * Holds the binds for any query
	 * 
	 * @var array
	 */
	protected $binds = array();

	/**
	 * Handles and routes the overall request 
	 */
	public function call()
	{
		$this->setup();

		$this->process_action();
	}

	private function search_by_id($id)
	{
		$this->model->where('id = :id');
		$this->binds['id'] = $id;
	}

	/**
	 * Processes the action to be made
	 * determing which database function to call by
	 * what is within the URI
	 */
	protected function process_action()
	{
		$uri_item = $this->request_uri[1];

		$get = true;

		if (!!$uri_item) {

			// If it's not a mutator call it's to find an id row (as thats default)
			// And check that it hasn't picked up a query string as the parameter
			if (is_numeric($uri_item)) {

				$this->model->where('id = :id');
				$this->binds['id'] = $uri_item;

			} elseif ($uri_item == 'create' || $uri_item == 'update') {
				$get = false;
			} else {

				if (substr($uri_item, 0, 1) != '?') {
					$this->output->error_handler(404, 'Method provided not found.');
				}

			}

		} 

		// Choose whether to query the database with a get or whether
		// the table is to be modified
		if ($get) {
			$this->handle_get_request();
		} else {

			// If an ID is set, it's going to be updating the record
			if (!!$this->request_uri[2] && substr($this->request_uri[2], 0, 1) != '?') {
				$this->model->id = $this->request_uri[2];
			}

			$this->handle_saving_request();
		}
	}

	/**
	 * Handle the saving of incoming requests 
	 */
	protected function handle_saving_request()
	{
		if (count($this->additional_params) > 0) {

			$result = $this->model->save($this->additional_params);
			
			if ($result) {
				$this->output->build_output_contents($this->model->attributes, 201);
			} else {
				$this->output->error_handler(400, 'Could not save record.');
			}
		} else {
			$this->output->error_handler(400, 'No values provided to save record');
		}
	}


	/**
	 * Build up the models where clause property by the additional
	 * parameters array
	 */
	protected function build_up_where_from_additional_params()
	{
		foreach ($this->additional_params as $key => $value) {

			if ($key != 'output_format') {
				$this->model->where($key.' = :'.$key);
				$this->binds[$key] = $value;
			}
		}

		return $this;
	}


	/**
	 * Action for the API if the request was a get
	 * Builds up the where array and queries the database
	 * returning either an error or the results
	 */
	protected function handle_get_request()
	{
		$this->build_up_where_from_additional_params();

		$result = $this->model->all($this->binds);

		if (count($result) > 0 && $result != false) {
			$this->output->build_output_contents($result, 200);
		} else {
			$this->output->error_handler(200, 'No results found.');
		}
	}

}


?>