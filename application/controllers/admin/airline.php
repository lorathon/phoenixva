<?php

class Airline extends PVA_Controller
{
    protected $_table_name = 'airlines';
    protected $_order_by = 'fs';
    protected $_image_size = array('X' => 130, 'Y' => 35);
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $this->_render('admin/airlines');
    }     
}