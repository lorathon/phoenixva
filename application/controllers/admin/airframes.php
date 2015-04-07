<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airframes extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {		
	$airframe = new Airframe();	
	$this->data['airframes'] = $airframe->find_all();
	$this->data['cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/airframes');
    }
}
