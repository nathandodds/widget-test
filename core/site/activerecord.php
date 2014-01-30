<?php

abstract class activerecord
{
    /**
     * Holds the models table attributes
     *
     * @var array
     */
    public $attributes = array();

    /**
     * Sets whether to overwrite the attributes or keep them
     *
     * @var bool
     */
    protected $_clean = true;

    /**
     * Holds an array of where conditions for a query
     *
     * @var array
     */
    public $where_conditions = array();

    /**
     * Holds the binded values for a where condition
     *
     * @var array
     */
    protected $_binds = array();

    /**
     * Array of columns used in a query
     *
     * @var array
     */
    public $columns = array();

    /**
     * Holds has one associations
     *
     * @var mixed string/array
     */
    public $has_one;

    /**
     * Holds has many associations
     *
     * @var mixed string/array
     */
    public $has_many;

    /**
     * Used to say whether to overwrite values from a save
     * 
     * @var bool
     */
    protected $_take_over;

    /**
     * Store the inner joins for the query has
     * one relationship
     *
     * @var string
     */
    private $_inner_joins;

    /**
     * Holds an errors occurred when saving a model
     *
     * @var array
     */
    public $errors = array();

    /**
     * Holds the limit of the query
     *
     * @var string
     */
    protected $_limit;

    /**
     * Overwritten by model whether to
     * use the migrations in the model
     * 
     * @var bool
     */
    protected $_migrate = false;

    /**
     * Constructs the class - setting attributesa and overwrites
     *
     * @param optional array $attributes
     * @param optional bool  $clean
     */
    public function __Construct($attributes = array(), $clean=false)
    {
        if ($this->_migrate == true) {
            Migration::migrate( $this->full_table() );
        }   

        if ( count($attributes) > 0 && !$clean ) {
            $this->set_attributes($attributes);
            $this->_clean = $clean;
        }

        $this->set_up_source_columns();
    }

    /**
     * Appends a condition to the where condition for a query
     *
     * @param string          $condition
     * @param optional string $value
     *
     * @return void
     */
    public function where($condition, $value="")
    {
        if (!!$condition && !!$value) {
            $condition = $condition.'= '.$condition;
        } else {
            $condition = $condition;
        }

        $this->where_conditions[] = $condition;

        return $this;
    }

    /**
     * Set the limit of the query
     *
     * @param string          $limit
     * @param optional string $offset
     *
     * @return void
     */
    public function limit($limit, $offset="")
    {
        if (!!$offset) {
            $limit .= ', '.$offset;
        }

        $this->_limit = $limit;

        return $this;
    }

    /**
     * Set the order of the query
     * 
     * @param string $column
     * @param optional string $direction
     * 
     * @return void
     */
    public function order($column, $direction="DESC")
    {
        $this->_order_by = $this->full_table().'.'.$column . ' '. $direction;

        return $this;
    }

    /**
     * Build the where condition out of the class where array
     *
     * @return mixed bool/array
     */
    public function build_where_clause()
    {
        if ( count($this->where_conditions) > 0 ) {
            $result = implode(' AND ', $this->where_conditions);
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Retrieves one item from the database and
     * sets as object
     *
     * @return mixed bool/array
     */
    public function find()
    {
        $args = func_get_args();

        $this->retrieve_has_one_joins();

        $column = 'id';
        if (!!$args[1]) {
            $column = $args[1];
        }

        $where = $this->build_where_clause();

        if ($where != false) {
            $options['where'] = $where;
            $options['binds'] = $args[0];
        } else {
            $options['where'] = $this->full_table().'.'.$column.' = :'.$column;
            $options['binds'] = array($column => $args[0]);
        }

        $options['columns'] = $this->columns;
        $options['joins'] = $this->_inner_joins;

        $output = $this->table()->find($options);

        if ($output) {
            $has_many = $this->retrieve_has_many_relationship($output->id);

            $this->set_attributes($output);
            $this->set_attributes($has_many);
        }

        return $this;
    }

    /**
     * Retrieves an associative array of results
     *
     * @param array $binds
     * @param bool  $joins - will set a group by if there is a join query associated
     *
     * @return assoc array
     */
    public function all($binds=array(), $joins=true)
    {
        $this->retrieve_has_one_joins();

        if (!$joins) {
            $options['group'] = 'GROUP BY '.$this->full_table().'.id';
        }

        $options['columns'] = $this->columns;
        $options['joins'] = $this->_inner_joins;
        $options['all'] = true;
        $options['binds'] = $binds;
        $options['order_by'] = $this->_order_by;
        $options['where'] = $this->build_where_clause();
        $options['limit'] = $this->_limit;

        $output = $this->table()->find( $options );

        if ( count($output) > 0 ) {
            $result = $output;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Handles the insertion and updating of a model
     *
     * @param optional array $attributes
     * @param optional bool  $validate   - whether to validate the input
     *
     * @return int
     */
    public function save($attributes=array(), $validate=true)
    {
        $this->set_attributes($attributes);

        $valid = true;

        if ($validate) {
            $valid = $this->validate_attributes_to_save();
        }

        $method = 'insert';

        if (!!$this->attributes['id']) {
            $method = 'update';
        }

        if ($valid) {
            $output = $this->{$method}();
        }

        if ($output == NULL) {
            $output = false;
        }

        return $output;
    }

    /**
     * Deletes an entry - if arguments not set, defaults to class instances
     *
     * @param optional string $column
     * @param optional string $value
     */
    public function delete($column="", $value="")
    {
        if ($column == "") {
            $column = 'id';
        }

        if ($value == "" && !!$this->attributes['id']) {
            $value = $this->attributes['id'];
        }

        $result = $this->table()->query->plain( 'DELETE FROM '. $this->full_table() . ' WHERE ' . $column . ' = :' . $column, array( $column => $value ) );

        return $result;
    }

    /**
     * Arranges all the data to be saved - by cross referencing the table columns
     * to those set within the attributes array
     *
     * @param optional string $table - sort a specific tables data
     *
     * @return array $data
     */
    public function cleanup_save_data($table = "")
    {
        $assocs = $this->has_many;

        if ( property_exists(get_class($this), 'has_one') ) {
            $has_one = $this->has_one;
        }

        if (!$table) {
            $table = $this->full_table();
        } else {
            $table = $table;
        }

        $columns = $this->table()->columns( $table );

        $data = array();

        foreach ($this->attributes as $att => $value) {
            for ( $i=0; $i<=count($columns); $i++ ) {
                if ($columns[$i] == $att) {
                    $data[$att] = $value;
                }

                if ($att == $assocs[$i]) {
                    $this->_assoc_data[$att] = $value;
                }

                if ($att == $has_one[$i]) {
                    $this->_assoc_data[$att] = $value;
                }
            }
        }

        return $data;
    }

    /**
     * Inserts a record and any associated table data
     *
     * @return int/bool $output of the insert call
     */
    public function insert()
    {
        $data = $this->cleanup_save_data();

        $output = $this->table()->insert( $data );

        $this->_take_over = true;

        $this->find( $output )->handle_saving_of_associations();
        $this->find( $output )->handle_saving_of_has_one_association();
        $this->find( $output );

        return $output;
    }

    /**
     * Updates a record and all assocated data tables
     *
     * @return bool - output of the update
     */
    public function update()
    {
        $data = $this->attributes;

        unset( $data['id'] );
        unset( $data['create_date'] );

        $output = $this->table()->update( $this->cleanup_save_data(), $this->attributes['id'] );

        $this->_take_over = true;

        $this->find( $this->attributes['id'] )->handle_saving_of_associations();
        $this->find( $this->attributes['id'] );

        return $output;
    }

    /**
     * Handles the saving (inserting/updating) of any associated table data
     *
     * @return bool/int $output of update/insert
     */
    private function handle_saving_of_associations()
    {
        if ( count( $this->has_many ) > 0 && !!$this->has_many ) {
            foreach ($this->has_many as $assoc) {
                if (!!$assoc && $assoc != "") {
                    $data = $this->attributes[$assoc];

                    if (!!$data) {
                        if( $this->attributes['id'] )
                            $data[$this->clean_table().'_id'] = $this->attributes['id'];

                        $assoc = explode(':', $assoc);
                        if (count($assoc) > 0) {
                            $assoc = $assoc[0];
                        }

                        $table = DB_SUFFIX.'_'.$assoc;

                        $sql_query = new operations();
                        $sql_query->table( $table );

                        // Validate associations data
                        if ( $this->validate_attributes_to_save( $table ) ) {
                            if (!!$data['id']) {
                                $output = $sql_query->update( $data, $data['id'] );
                            } else {
                                $output = $sql_query->insert( $data );
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * This needs to be thoroughly tested
     * This will loop through the single inheritance and just save them to the database
     * however on the first insertion updating the main source table to the ID of the item aved
     * which builds up the 'has_one' relationship
     *
     * @return bool
     */
    private function handle_saving_of_has_one_association()
    {
        if ( property_exists(get_class($this), 'has_one') ) {

            // Convert if not an array
            if ( !is_array($this->has_one) ) {
                $has_one[] = $this->has_one;
                $this->has_one = $has_one;
            }

            if ( !!$this->has_one && count( $this->has_one ) > 0 ) {
                foreach ($this->has_one as $one) {
                    if (!!$one) {

                        $data = $this->attributes[$one];

                        if (!!$data) {
                            $table = DB_SUFFIX.'_'.$one;
                            $source = get_class($this);

                            $sql_query = new operations();
                            $sql_query->table( $table );

                            // Validate associations data
                            if ( $this->validate_attributes_to_save( $table ) ) {

                                if (!!$data['id']) {

                                    $output = $sql_query->update( $data, $data['id'] );
                                } else {
                                    // We only want to do something if we actually have properties!
                                    if ( count( $data ) > 0 ) {

                                        $output = $sql_query->insert( $data );

                                        // On the first insertion and save - we update the main record with the id
                                        // of the main item that was saved

                                        $this->attributes[$one.'_id'] = $output;

                                        $output = $this->update();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Validates any attributes going into the save method
     *
     * @param string $table
     *
     * @return bool
     */
    private function validate_attributes_to_save($table = "")
    {
        if( $table == "" )
            $table = $this->full_table();
        else
            $table = $table;

        $validation = new Validation();

        $rules = $this->validates;

        $columns = $this->table()->columns( $table );

        foreach ($columns as $column) {
            for ( $i=0; $i<=count($rules); $i++ ) {
                $rule = $rules[$i][0];
                $col = $rules[$i][1];

                if ($column == $col) {
                    switch ($rule) {
                        case 'not_empty':
                            $validation->not_empty( $col, $this->attributes[$col], $rules[$i][2] );
                            break;

                        case 'valid_email':
                            $validation->valid_email( $this->attributes[$col], $rules[$i][1] );
                            break;
                    }
                }
            }
        }

        $this->errors = $validation->errors;

        return $validation->pass;
    }

    /**
     * Set the class attributes to have values to be
     * accessed outside of the class
     *
     * @param array $attributes
     *
     * @return void
     */
    public function set_attributes($attributes)
    {
        if ($this->_clean) {
            if ( count($attributes) > 0 ) {
                foreach ($attributes as $column => $value) {
                    $this->attributes[ $column ] = $value;
                }
            }
        } else {
            $this->attributes['id'] = $attributes->id;
        }

        return $this;
    }

    /**
     * Retrieves has one additional join queries
     * and appends the has one tables columns to the
     * main columns array
     *
     * @return array/bool
     */
    public function retrieve_has_one_joins($id)
    {
        if ( property_exists($this, 'has_one') && $this->has_one != "" && count( $this->has_one ) > 0 ) {

            $options = array(
                'associations' => $this->has_one,
                'table' => $this->clean_table()
            );

            $has_one = new Has_one($options);
            $result = $has_one->get();

            if ( count($result) > 0 ) {

                $this->columns[] = $result['columns'];
                $result = $result['joins'];

                $this->_inner_joins = $result;

            } else {
                $result = false;
            }

        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Sets out to get all has many relationship status
     * for a model
     *
     * @param string $id - the association id
     *
     * @return mixed array/bool
     */
    public function retrieve_has_many_relationship($id)
    {
        if ( property_exists($this, 'has_many') && $this->has_many != "" ) {

            $options = array(
                    'associations' => $this->has_many,
                    'id' => $id,
                    'table' => $this->clean_table()
                );

            $has_many = new Has_many($options);
            $result = $has_many->get();

            if ( count($result) <= 0 ) {
                $result = false;
            }

        }

        return $result;
    }

    /**
     * Sets the columns for the class queries
     *
     * @return void
     */
    public function set_up_source_columns()
    {
        foreach ( $this->table()->columns($this->full_table()) as $column ) {
            if ($column != "") {
                $this->columns[] = $this->full_table().'.'.$column;
            }
        }

        if ($this->_has_image) {
            $image_column = '(SELECT imgname FROM ' . DB_SUFFIX . '_image WHERE id = ' . $this->full_table() . '.image_id) as image';

            $this->columns[] = $image_column;
        }

        if ($this->_has_upload) {
            $upload_columns = '( SELECT name FROM ' . DB_SUFFIX . '_uploads WHERE id = ' . $this->full_table() . 'uploads_id ) as upload_name
                               , ( SELECT title FROM ' . DB_SUFFIX . '_uploads WHERE id = ' . $this->full_table() . 'uploads_id ) as upload_title';

            $this->columns[] =  $upload_columns;
        }

        return $this;
    }

    /**
     * Method to delete rows by something other than the id
     *
     * @param string $column
     * @param string $value
     *
     * @return bool
     *
     * @access public
     */
    public function delete_by_column ( $column = "", $value = "" )
    {
        if (!!$column && !!$value) {
            $this->table()->query->plain( 'DELETE FROM '. $this->full_table() . ' WHERE ' . $column . ' = :' . $column, array ( $column => $value ) );

            return TRUE;
        } else
            throw new Exception ( "A column and a value is needed to use the 'delete_by_column' method." );
    }

    /**
     * @return table name without DB_SUFFIX
     */
    public function clean_table()
    {
        $class = get_class( $this );

        $class = str_replace( "_model", "", $class );

        return strtolower($class);
    }

    /**
     * @return table with DB_SUFFIX prepended
     */
    public function full_table()
    {
        $class = $this->clean_table();
        $class = strtolower(DB_SUFFIX . '_' . str_replace( "_model", "", $class ));

        return $class;
    }

    /**
     * Holds direct port to the table class for manipulating a table
     */
    public function table()
    {
        $class = $this->full_table();

        return Table::load($class);
    }

    /**
     * Dynamically set a class attribute
     *
     * @param string $key
     * @param string $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Dynamically retrieve class attribute
     *
     * @param  string      $key
     * @return bool/string
     */
    public function __get($key)
    {
        if ( isset($this->attributes[$key]) ) {
            $result = $this->attributes[$key];
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Builds up the where clause given by the dynamically _call method
     *
     * @return bool
     */
    private function arrange_missing_where($where)
    {
        $where = explode('_', $where);

        if ($where[0] != 'where') {
            return false;
        }

        $where[0] = "";
        $_w;
        for ( $i=0; $i<=count($where); $i++ ) {
            $column = $where[$i];

            if ($column != "") {
                $opera = ' = ';

                if ($where[$i+1] == 'like') {
                    $opera = ' LIKE ';
                    // Set the next one to be blank, as we don't wish to use this again
                    // As we know that this is the like operator
                    $where[$i+1] = "";
                }

                if ($where[$i+1] == 'id') {
                    $column .= '_id';
                    $where[$i+1] = "";
                }                
                
                $this->where( $this->full_table().'.'.$column.$opera.':'.$column );
            }
        }

        return true;
    }


    /**
     * Dynamically called for building a query
     *
     * @param string $method     - the query to build such as: find_where_title_like
     * @param array  $parameters - the binds to be passed in to the query for the where clause
     *
     * @return array
     */
    public function __call($method, $parameters)
    {
        $binds = $parameters[0];

        if ( starts_with($method) == 'find' ) {
            $where = $this->arrange_missing_where( substr($method, 5) );

            $this->find( $binds );

            return $this->attributes;
        } elseif ( starts_with( $method ) == 'get' ) {

            $where = $this->arrange_missing_where( substr($method, 4) );

            if ($where) {

                return $this->all( $binds );
            }
        }
    }

}
