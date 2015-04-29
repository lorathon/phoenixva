<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends PVA_Controller
{

    public function __construct()
    {
	parent::__construct();
    }

    public function index()
    {
	$this->load->helper('url');
        $this->data['scripts'][] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js';
	$this->data['scripts'][] = base_url('assets/js/views/view.test.js');
	$this->_render();
    }
    
    public function create()
    {
	$airport = new Airport();
	$airports = $airport->get_all_airports();
	
	foreach($airports as $obj)
	{
	    $obj->create_autocomplete();
	}
    }

    function get_airports()
    {
	$airport = new Airport();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    $airport->get_autocomplete($data);
	}
    }
    
    function results()
    {
	
    }
}
