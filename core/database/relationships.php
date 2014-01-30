<?php

abstract class relationships implements Interface_relationship
{
    /**
     * The main table to associate
     *
     * @var string
     */
    public $table;

    /**
     * Holds the actual primary key column
     *
     * @var string
     */
    public $primary_key;

    /**
     * Holds the main tables primary key value
     *
     * @var string
     */
    public $primary_key_value;

    /**
     * Holds the models list of associations
     *
     * @var array
     */
    public $associations = array();

    /**
     * Set the options parameters for the relationship
     * upon instatntian of the class
     *
     * @param array $options
     */
    public function __Construct(array $options)
    {
        $this->table = $options['table'];

        if ( !is_array($options['associations']) ) {
            $associations[] = $options['associations'];
            $options['associations'] = $associations;
        }

        $this->associations = $options['associations'];
        $this->primary_key = (!!$options['primary_key'] ? $options['primary_key'] : 'id');

        if (!!$options['id']) {
            $this->primary_key_value = $options['id'];
        }
    }

    /**
     * Returns an instantited object of the model
     *
     * @return object
     */
    public function instantiate_model()
    {
        $model = ucwords($this->table).'_model';

        $model = new $model();

        return $model;
    }
}
