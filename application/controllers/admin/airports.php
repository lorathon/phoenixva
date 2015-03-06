<?php

class Airports extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $this->_render('admin/airports');
    }     
}