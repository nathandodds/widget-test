<?php

class Data_parser
{
    /**
     * The data to be sent
     * @access private
     */
    private $_data;

    /**
     * The data type
     * @access private
     */
    private $_type;

    /**
     * Format some data into either xml | json | csv
     * @param array | object $data
     * @param string         $type ( default xml )
     *
     * @return $formatted
     *
     * @access public
     */
    public function format($data, $filename = '', $type = 'xml')
    {
        if (!$data) {
            throw new Exception( 'Data_parser::format needs some data to format in the form of a array or object' );
        } else {

            $this->_type = $type;

            if ($type == 'xml') {
                $formatted = $this->format_xml( $data );
            } elseif ($type == 'json') {
                $formatted = $this->format_json( $data );
            } elseif ($type == 'csv') {
                $formatted = $this->format_csv( $data );
            }
        }

        $return = 'An error occurred.';

        if ( $this->save_file( $formatted, $filename ) ) {
            $return = 'Your file was saved successfully.';
        }

        return $return;
    }

    public function save_file($formatted, $filename)
    {
        $return = FALSE;

        if (!$filename) {
            $filename = date( "d-m-Y" ) . '-' . $this->_type;
        }

        mkdir( PATH . 'generated_' . $this->_type );

        if ( file_put_contents( PATH . 'generated_' . $this->_type . '/' . $filename . '.' . $this->_type , $formatted ) ) {
            $return = TRUE;
        }

        return $return;
    }

    /**
     * Format array or object into xml format
     * @param array | object $data
     *
     * @return xml $formatted
     *
     * @access private
     */
    public function format_xml($data)
    {
        $xml = new SimpleXmlElement( '<test/>' );

        if ( gettype( $data ) == 'array' ) {

            foreach ($data as $key => $value) {
                if ( is_array( $value ) ) {
                    $xml = $this->add_nodes_from_array( $key, $value, $xml );
                } else {
                    $xml->addChild( $key, $value );
                }
            }

            $formatted = $xml->asXml();
        } elseif ( gettype( $data ) == 'object' ) {
            /**
             * I'll do this later
             */
        }

        return $formatted;
    }

    /**
     * An array is passed in and each key / value is added to the current XML object and returned
     * @param array  $item
     * @param object $xml
     *
     * @access private
     */
    private function add_nodes_from_array($name, $item, $xml)
    {
        $node = $xml->addChild( $name );

        foreach ($item as $key => $value) {
            if ( is_array( $value ) ) {
                $this->add_nodes_from_array( $key, $value, $node );
            } else {
                $node->addChild( $key, $value );
            }
        }

        return $xml;
    }

    /**
     * Format array or object into json format
     * @param array | object $data
     *
     * @access private
     */
    public function format_json($data)
    {
        return json_encode( $data );
    }

    /**
     * Format array or object into csv format
     *
     * A CSV can be constructed via two routes
     * - With the row headers as the first element
     * - With the row headers as the keys of each element
     *
     * The default is the second option
     *
     * @param array | object $data
     *
     * @access private
     */
    public function format_csv($data)
    {
        $first_line = FALSE;
        $csv = implode( ',', array_keys( $data[0] ) );

        foreach ($data as $value) {
            $csv .= implode( ',', array_values( $value ) ) . '\r\n';
        }

        return $csv;
    }

    /**
     * Just incase I want to set many properties from a array
     * @param array $data
     *
     * @access public
     */
    public function setter($data)
    {
        foreach ($data as $key => $value) {
            $this->{ '_' . $key } = $value;
        }

        return $this;
    }


    /**
     * So we can write to properties without a setter method
     * @param string $name
     * @param string $value
     *
     * @access public
     */
    public function __set($name, $value)
    {
        $this->{ '_' . $name } = $value;
    }
}
