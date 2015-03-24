<?php

class Flightstats extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }
    
    public function index()
    {        	
	$this->_render('admin/flightstats');
    }     
}