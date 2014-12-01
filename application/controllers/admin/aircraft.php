<?php

class Aircraft extends PVA_Controller
{
    protected $_table_name = 'aircraft';
    protected $_order_by = 'icao';
    protected $_image_size = array('X' => 130, 'Y' => 35);
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $this->_render('admin/aircraft');
    }     
}