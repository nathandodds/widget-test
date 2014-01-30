<?php

class cacher
{
    /**
     * The file to that will be cached
     * @var string
     */
    public $cache_file;

    /**
     * The time to cache the file
     * @var int
     */
    public $cache_time = 33;

    /**
     * Construct the class and set the file to be cached
     *
     * @param string $cache_file
     */
    public function __Construct($cache_file="")
    {
        $this->cache_file = PATH.'cache/'.$cache_file.'.cache.txt';
    }

    /**
     * Creates the cache file
     */
    public function create_cache_file()
    {
        $result = false;

        $file = fopen($this->cache_file, 'w');

        fclose($file);

        if ( file_exists($this->cache_file) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Checks the cache time of a file
     * If no file is present it will create one
     *
     * @return bool
     */
    public function check_cache_time()
    {
        if ( !file_exists($this->cache_file) ) {

            $this->create_cache_file();
            $output = true;

        } else {

            if ( (time()-$this->cache_time >= filemtime($this->cache_file)) ) {
                $output = true;
            } else {
                $output = false;
            }

        }

        return $output;
    }

    /**
     * Caches the data to the file
     *
     * @param  string $data
     * @return bool
     */
    public function cache_data($data)
    {
        $this->delete_cache();

        $file = fopen($this->cache_file, 'w');

        $output = file_put_contents($this->cache_file, serialize($data));

        fclose( $file );

        return $this->check_cache_data();
    }

    /**
     * Check that the cache file has data within it
     *
     * @return bool
     */
    public function check_cache_data()
    {
        $data = file_get_contents($this->cache_file);

        $result = false;
        if (!!$data) {
            $result = true;
        }

        return $result;
    }

    /**
     * Retrieve the cached data from the file
     *
     * @return mixed array/bool
     */
    public function get_cache_data()
    {
        if ( file_exists($this->cache_file) ) {

            $output = file_get_contents($this->cache_file);
            $output = unserialize($output);

        } else {

            $logger = new Logger('system');
            $logger->set('Core/Libraries/Cacher: Failed attempting to cache to file '.$this->cache_file.' - as it does not exist')
                   ->write();

            $output = false;
        }

        return $output;
    }

    /**
     * Remove the cached file
     */
    public function delete_cache()
    {
        unlink($this->cache_file);

        $result = false;
        if ( !file_exists($this->cache_file) ) {
            $result = true;
        }

        return $result;
    }

}
