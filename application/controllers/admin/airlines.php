<?php

class Airlines extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $this->_render('admin/airlines');
    }     
}