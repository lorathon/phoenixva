<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fleet extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct();
	$this->load->helper(array('form', 'url', 'html'));
	$this->load->library('form_validation'); 
    }
    
    public function index()
    {        
	$aircraft = new Aircraft();
	$aircraft->create_equip();
	
	
	//$this->data['fleet'] = $aircraft->find_all();
	
	//$this->_render('admin/fleet');
    }     
}