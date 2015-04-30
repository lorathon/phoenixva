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
	$search = $this->input->get('term', TRUE);
	if (isset($search))
	{
	    $data = strtolower($search);
	    echo $airport->get_autocomplete($data);
	}
    }

}
