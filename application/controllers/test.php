<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {	
	$airframe = new Airframe();
	$frames = $airframe->find_all();
	
	foreach($frames as $frame)
	{
	    $frame->check_sub();
	    $frame->save();
	}
    }
}
