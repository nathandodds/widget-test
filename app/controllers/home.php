<?php

class home extends C_Controller
{
    public function index()
    {
        /*
        $data_parse = new Data_parser();
        die( $data_parse->format( array( array( 'id' => 1, 'name' => 'Foo' ),
                                         array( 'id' => 2, 'name' => 'Dave' ),
                                         array( 'id' => 3, 'name' => 'Bar' ) ), '', 'json' ) );

        */
        
        
        $this->addTag ('title', 'Home');
        $this->addTag ( 'meta_keywords', 'Pegisis');
        $this->addTag ( 'meta_desc', 'Pegisis');

        $this->addStyle('layout');

        $this->setView('home/index');
    }
    
}
