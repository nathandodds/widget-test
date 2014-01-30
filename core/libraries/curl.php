<?php

class curl
{
    /**
     * The url to send the curl request to
     * @access private
     */
    private $_url;

    /**
     * The type of request
     * @access private
     */
    private $_curl_type = 'PUT';

    /**
     * The type of content
     * @access private
     */
    private $_content_type;

    /**
     * The options for the curl request
     * @access private
     */
    private $options = array();

    /**
     * Main responsibility is to check the URL exists
     * @param string $url
     *
     * @access public
     */
    public function __Construct(String $url)
    {
        if (!$url) {
            throw new Exception( 'Curl::__Construct requires a URL' );
        } else {
            $this->_url = $url;
        }
    }

    /**
     * Sets the options based on a JSON object
     * @param string $data
     *
     * @return object $this
     *
     * @access public
     */
    public function set_json($data)
    {
        if (!$data) {
            throw new Exception( 'Curl::set_json needs some data' );
        } else {
            $this->_options = array( CURLOPT_URL => $this->url,
                                         CURLOPT_RETURNTRANSFER => 1,
                                         CURLOPT_PUT => TRUE,
                                         CURLOPT_HTTPHEADER => array( 'Content-type: text/json', 'Content-length: ' . strlen( $data ) ),
                                         CURLOPT_INFILE => $this->create_temp_file( $data ),
                                     CURLOPT_INFILESIZE => strlen( $data ) );
        }

        return $this;
    }

    /**
     * Sets the options based on a XML object
     * @param string $data
     *
     * @return object $this
     *
     * @access public
     */
    public function set_xml($data)
    {
        if (!$data) {
            throw new Exception( 'Curl::set_xml needs some data' );
        } else {
            $this->_options = array( CURLOPT_URL => $this->url,
                                         CURLOPT_RETURNTRANSFER => 1,
                                         CURLOPT_PUT => TRUE,
                                         CURLOPT_HTTPHEADER => array( 'Content-type: text/xml', 'Content-length: ' . strlen( $data ) ),
                                         CURLOPT_INFILE => $this->create_temp_file( $data ),
                                     CURLOPT_INFILESIZE => strlen( $data ) );
        }

        return $this;
    }

    /**
     * Initiates the curl request
     * @param csv | json $data
     *
     * @access public
     */
    public function send()
    {
        $curl = curl_init();
        curl_setopt_array( $curl, $this->_options );
        $result = curl_exec( $curl );
        curl_close( $curl );

        return $result;
    }

    /**
     * Create and returns a temporary file
     * @param string $data
     *
     * @access private
     */
    private function create_temp_file($data)
    {
        $temp_data = tmpfile();
        fwrite( $temp_data, $data );
        fseek( $temp_data, 0 );

        return $temp_data;
    }

    /**
     * Just incase I want to set more than one property
     * @param array $data
     *
     * @access public
     */
    public function setter(Array $data)
    {
        foreach ($data as $key => $value) {
            $this->{ $key } = $value;
        }

        return $this;
    }

    /**
     * Sets a property
     * @param string   $name
     * @param anything $value
     *
     * @access public
     */
    public function __set(String $name, $value)
    {
        $this->{ $name } = $value;
    }
}
