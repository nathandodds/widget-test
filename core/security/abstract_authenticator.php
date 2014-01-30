<?php

class Abstract_authenticator
{
	protected $_model = 'Users_model';

	/**
	 * Public constructor sets the model to use
	 * to authenticate against
	 *
	 * @param optional string $model 
	 */
	public function __Construct($model = null)
	{
		if (!!$model) {
			$this->_model = ucwords($model).'_model';
		}
	}
}

?>