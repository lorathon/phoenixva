<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {	
	$airline = new Airline();
	$airline->set_regional();
    }
}
