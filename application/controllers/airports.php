<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airports extends PVA_Controller
{

    public function __construct()
    {
	parent::__construct();
    }

    public function index($type = NULL)
    {
	$this->_render();
    }
    
    public function view($id = NULL)
    {
	
    }
    
    function autocomplete()
    {
	$airport = new Airport();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    echo $airport->get_autocomplete($data);
	}
    }

}
