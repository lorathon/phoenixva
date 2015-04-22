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

    function get_airports_name()
    {
	$airport = new Airport();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    $airport->get_autocomplete($data, NULL, NULL);
	}
    }
    
    function get_airports_iata()
    {
	$airport = new Airport();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    $airport->get_autocomplete(NULL, $data, NULL);
	}
    }
    
    function get_airports_icao()
    {
	$airport = new Airport();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    $airport->get_autocomplete(NULL, NULL, $data);
	}
    }

}
