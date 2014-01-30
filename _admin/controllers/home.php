<?php

class home extends C_Controller
{
    public function index ()
    {
        $this->addStyle ( 'login' );
        $this->setScript ( 'login' );

        $this->addTag ( 'dont_show_menu', TRUE );
        $this->addTag ( 'dont_show_header', TRUE );
        $this->setView ( 'home/index' );

    }
}
