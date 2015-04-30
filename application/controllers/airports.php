<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airports extends PVA_Controller
{

    public function __construct()
    {
	parent::__construct();
    }

    public function index($type = NULL)
    {
	$this->load->helper('url');

	// if $id is null then set $id to 1 and start over
	if (is_null($type))
	    redirect('airports/land');

	$this->data['meta_title'] = 'Phoenix Virtual Airways Airports';
	$this->data['title'] = 'Airports';

	$this->data['airports'] = array();

	$airport = new Airport();

	if ($type == 'heli')
	{
	    $airport->port_type = 'heli';
	}
	elseif ($type == 'sea')
	{
	    $airport->port_type = 'sea';
	}
	else
	{
	    $airport->port_type = 'land';
	}
	$airport->active = TRUE;

	$airports = $airport->find_all();

	$this->data['airports'] = $airports;
	$this->session->set_flashdata('return_url', 'airports/' . $type);
	$this->_render();
    }
    
    public function view($id, $page = NULL)
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Airports';
	$this->data['breadcrumb']['airports'] = 'Airports';

	$airport = new Airport($id);

	// send back to index if $airline is not found
	if (!$airport->name)
	    $this->index();

	$this->data['title'] = $airport->name;

	if ($page == 'departures')
	{
	    // departure schedules
	    $schedule = new Schedule();
	    $schedule->dep_airport_id = $airport->id;	    
	    $this->data['schedules'] = $schedule->find_all();
	}
	elseif ($page == 'arrivals')
	{
	    // arrival schedules
	    $schedule = new Schedule();
	    $schedule->arr_airport_id = $airport->id;	    
	    $this->data['schedules'] = $schedule->find_all();
	}
	elseif ($page == 'map')
	{
	    
	}
	else
	{	 
	    // airlines
	    $this->data['airlines'] = $airport->get_airlines();
	}

	$this->data['airport'] = $airport;
	$this->session->set_flashdata('return_url', 'airports/view/' . $id);
	$this->_render();
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
