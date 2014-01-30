<?php

class Has_many extends Relationships
{
    /**
     * Holds the associations final data results
     *
     * @var assoc array
     */
    private $associations_data = array();

    /**
     * Set the options parameters for the relationship
     * upon instatntian of the class
     *
     * @param array $options
     */
    public function __Construct(array $options)
    {
        parent::__Construct($options);
    }

    /**
     * Retrieves the results of the relationship
     *
     * @return array
     */
    public function get()
    {
        return $this->build_relationship();
    }

    /**
     * Sets all associatve data to the class property
     * by making a new search query for every relationship
     *
     * @return mixed bool/array
     */
    public function build_relationship()
    {
        $model_table = $this->instantiate_model();
        $main_table = strtolower($model_table->clean_table());

        foreach ($this->associations as $many) {

            $_many = str_replace(':image', '', $many);
            $many_table = DB_SUFFIX.'_'.$_many;

            $image_query = $this->assign_image_association($many);

            $query = 'SELECT *'.$image_query.' FROM '.$many_table.' WHERE '.$main_table.'_id = :id';

            // Unset the add query for the next loop
            $image_query = "";

            $has_many = $model_table->table()->query->getAssoc($query, array('id' => $this->primary_key_value));

            // If theres a joint query we set it in the array
            if (!!$has_many) {
                $this->associations_data[$_many] = $has_many;
            }
        }

        return $this->get_association_data();
    }

    /**
     * Retrieves the built up association data
     * if there is any results otherwise returns false
     *
     * @return mixed array/bool
     */
    public function get_association_data()
    {
        if ( count($this->associations_data) > 0 ) {
            $result = $this->associations_data;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Sets the additional column for associated one-to-one images
     *
     * @param string $item - the has_many item from the model
     *
     * @return string/bool
     */
    public function assign_image_association($item)
    {
        preg_match('/:/', $item, $matches, PREG_OFFSET_CAPTURE);

        if (!!$matches[0][0]) {

            $add_select = explode(':', $item);

            if ($add_select[1] == 'image') {
                $result = ', (SELECT imgname FROM ' . DB_SUFFIX . '_image WHERE id = image_id) as image';
            }
        } else {
            $result = false;
        }

        return $result;
    }
}
