<?php

class pagination
{
    public $per_page;
    public $current_page;

    public $total_pages;

    public $offset;
    public $limit;

    /**
     * A limit and page limit are required
     * If one or the other is not supplied a exception is thrown
     *
     * @param int $limit
     * @param int $per_page
     */
    public function __Construct($per_page, $current_page, $total)
    {
        if (!!$per_page && !!$current_page && !!$total) {
            $this->limit = $limit;
            $this->per_page = $per_page;
            $this->current_page = $current_page;

            $this->total_pages = ceil( $total / $per_page );

            $this->limit = $per_page;
            $this->offset = ( $current_page * $per_page ) - $per_page;
        } else {
            throw new Exception( 'A page limit, current page and total rows are required to use the pagination class' );
        }

        print_r( $this );
    }

    /**
     * Works out the next page and returns it
     *
     * @return $next int
     */
    public function next()
    {
        $next = $this->current_page + 1;

        if ($next > $this->total_pages) {
            $next = FALSE;
        }

        return $next;
    }

    /**
     * Works out the previous pages and returns it
     *
     */
    public function previous()
    {
        $previous = $this->current_page - 1;

        if ($this->current_page == 1) {
            $previous = FALSE;
        }

        return $previous;
    }

    /**
     * Returns some middle pages
     * Maximum 3 either side of the current page
     *
     * @return array $pages
     */
    public function middle()
    {
        $pages = array();

        //Up
        for ($i = $this->current_page + 1; $i < $this->current_page + 4; $i++) {
            if ($i <= $this->total_pages) {
                $pages[] = $i;
            }
        }

        //Down
        for ($i = $this->current_page - 1; $i > $this->current_page - 4; $i--) {
            if ($i >= $this->total_pages) {
                $pages[] = $i;
            }
        }

        print_r( $pages );
    }

    public function get($prop)
    {
        return $this->$prop;
    }
}
