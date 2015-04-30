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
	$bid = new Bid(1);
	
	print_r($bid);
	
    }
    
    function results()
    {
	
    }
}
