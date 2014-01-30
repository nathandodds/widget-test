<?php

class migration
{
    private $_q;

    private $_table;
    private $_clean_table;

    public function __Construct()
    {
        $this->_q = new Query();
    }

    public static function migrate($table)
    {
        $_this = new Migration();
        $_this->set( 'table', $table );
        $_this->set( 'clean_table', str_replace( DB_SUFFIX . '_' , '', $table ) );

        $exists = $_this->check_table_exists();

        if (!$exists) {
            $_this->create_table_from_schema();
        } else {
            //Get the current database schema
            $columns = $_this->_q->getAssoc( 'DESCRIBE ' . $_this->_table );
            //Filter the columns into a structured array the same as the schema one
            $current_schema = $_this->get_current_schema( $columns );
            //Compare this against the schema in the file
            $mod = $_this->_clean_table . '_schema';
            $alter = $_this->workout_if_alter_is_needed( $current_schema, $mod::get_schema() );

            //Make any necessary changes
            if (!!$alter) {
                $_this->alter_table( $alter );
            }
        }
    }

    public function set($name, $value)
    {
        $this->{ '_' . $name } = $value;
    }

    public function check_table_exists()
    {
        $exists = FALSE;

        $tables = $this->_q->getAssoc( 'SHOW TABLES LIKE "' . $this->_table . '"' );

        if (!!$tables) {
            $exists = TRUE;
        }

        return $exists;
    }

    public function create_table_from_schema()
    {
        $mod = $this->_clean_table . '_schema';
        $schema = $mod::get_schema();
        $query = $this->generate_query_from_schema( $schema );

        //Execute the query
        if (!!$query) {
            $this->_q->plain( $query );
        }
    }

    public function generate_query_from_schema($schema)
    {
        if (!$schema) {
            throw new Exception( 'Migration: A schema is needed to create a new table' );
        } else {

            //Unset the id and create_date because these have to be handled differently
            unset( $schema[ 'id' ] );
            unset( $schema[ 'create_date' ] );

            $query = 'CREATE TABLE `' . $this->_table . '` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,';

            foreach ($schema as $row) {
                $query .= '`' . $row[ 'name' ] . '` ' . strtoupper( $row[ 'type' ] ) . ( !!$row[ 'limit' ] ? '(' . $row[ 'limit' ] . ')' : '' ) . ', ';
            }

            $query .= '`create_date` TIMESTAMP )';
        }

        return $query;
    }

    public function get_current_schema($columns)
    {
        if (!!$columns) {

            $schema = array();

            foreach ($columns as $column) {

                $info = explode( '(', $column[ 'Type' ] );

                $type = $info[0];
                $limit = trim( $info[1], ')' );

                $schema[ $column[ 'Field' ] ] = array( 'name' => $column[ 'Field' ],
                                                       'type' => $type,
                                                       'limit' => $limit );
            }

            return $schema;
        }
    }

    public function workout_if_alter_is_needed($current_schema, $schema)
    {
        if ($current_schema == $schema) {
            $return = FALSE;
        } else {
            //Workout what fields need to be added
            //To reduce errors I will not be removing columns that are in the database but not the current schema

            //array_diff() didnt work so I had to loop through
            $difference = array();

            foreach ($schema as $key => $col) {
                if (!$current_schema[ $key ]) {
                    $difference[ $key ] = $col;
                }
            }

            $return = $difference;
        }

        return $return;
    }

    public function alter_table($columns)
    {
        if (!!$columns) {
            foreach ($columns as $col) {
                $this->_q->plain( 'ALTER TABLE ' . $this->_table . ' ADD ' . $col[ 'name' ] . ' ' . strtoupper( $col[ 'type' ] ) . '' . ( !!$col[ 'limit' ] ? '(' . $col[ 'limit' ] . ')' : '' ) );
            }
        }
    }
}
