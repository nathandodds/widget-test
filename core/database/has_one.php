<?php

class Has_one extends Relationships
{
    /**
     * Holds the model object
     *
     * @var object
     */
    private $_model = null;

    /**
     * Columns of the associated table
     * Used to pass into the main get query
     *
     * @var array
     */
    public $columns = array();

    /**
     * Set the relationship properties
     * and instantiate the main table model
     *
     * @param array $options
     */
    public function __Construct(array $options)
    {
        parent::__Construct($options);

        $this->_model = $this->instantiate_model();
    }

    /**
     * Returns the concatenated JOINS and
     * their corresponding columns for the query
     *
     * @return array
     */
    public function get()
    {
        $joins = $this->build_relationship();

        return array('columns' => implode(',', $this->columns),
                     'joins' => $joins );
    }

    /**
     * Builds up the associated join queries for the main query
     *
     * @return mixed bool/array
     */
    public function build_relationship()
    {
        $joins = array();

        $main_table = strtolower($this->_model->clean_table());

        foreach ($this->associations as $one) {

            if (!!$one) {
                $table = strtolower(DB_SUFFIX.'_'.$one);

                $clean_one = str_replace( DB_SUFFIX .'_', '', $table );

                $joins[] = 'LEFT JOIN '.$table.' ON '.DB_SUFFIX.'_'.$main_table.'.'.$clean_one.'_id = '.$table.'.id';

                // Set the columns within the one loop
                $this->get_has_one_columns($one);
            }
        }

        if ( count($joins) > 0 ) {
            $result = implode( ' ', $joins );
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Retrieves and sets the tables columns
     *
     * @param string $table
     *
     * @return void
     */
    public function get_has_one_columns($table)
    {
        $columns = $this->_model->table()->columns(DB_SUFFIX.'_'.$table);

        foreach ($columns as $col) {
            $this->columns[] = DB_SUFFIX.'_'.$table.'.'.$col.' as '.str_replace(DB_SUFFIX.'_', '', $table).'_'.$col;
        }

        if ($this->_model->_has_image) {
            $image_column = '(SELECT imgname FROM ' . DB_SUFFIX . '_image WHERE id = ' . DB_SUFFIX.'_'.$table . '.image_id) as ' . $table . '_image';

            $this->columns[] = $image_column;
        }

        if ($this->_model->_has_upload) {
            $upload_columns = '( SELECT name FROM ' . DB_SUFFIX . '_uploads WHERE id = ' . DB_SUFFIX.'_'.$table . '.uploads_id ) as ' . $table . '_upload_name
                               , ( SELECT title FROM ' . DB_SUFFIX . '_uploads WHERE id = ' . DB_SUFFIX.'_'.$table . '.uploads_id ) as ' . $table . '_upload_title';

            $this->columns[] =  $upload_columns;
        }

        return $this;
    }
}
