<?php

class home extends C_Controller
{
    public function index()
    {
              
        $this->addTag ('title', 'Home');
        $this->addTag ( 'meta_keywords', 'Pegisis');
        $this->addTag ( 'meta_desc', 'Pegisis');

        $this->addStyle('layout');

        $this->setView('home/index');
    }
    
}
