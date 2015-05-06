<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fleet extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
	$this->data['meta_title'] = 'Phoenix Virtual Airways Fleet';
    }
    
    public function index($id = NULL)
    {	
	$this->load->helper('url');
	
	// if $id is null then set $id to 1 and start over
	if(is_null($id))
	    redirect('fleet/1');	
	
	$this->data['title'] = 'Fleet';
	
	$this->data['fleet_cat'] = $this->config->item('aircraft_cat');
	$fleet = array();
	
	$airframe = new Airframe();
	$airframe->category = $id;	
	$airframe->enabled = TRUE;
	$fleet = $airframe->find_all();
	
	$this->data['fleet'] = $fleet;
	$this->session->set_flashdata('return_url','fleet/'.$id);	
        $this->_render();
    }
    
    public function view($id = NULL, $tab = NULL)
    {
	if(is_null($id))
	    $this->index();
	
	$this->load->helper('url');
	
	if(is_null($tab))
	    redirect('fleet/view/'.$id.'/pireps');
	
	$airframe = new Airframe($id);
	
	// Send back to index if $airframe is not found
	if(! $airframe->name)
	    $this->index();	
	
	if($tab == 'pireps')
	{
	    // Get pireps using this airframe
	    $this->data['flights'] = array();
	}
	elseif($tab == 'flights')
	{
	    // Get schedules using this airframes aircraft sub id
	    $this->data['flights'] = array();
	    $sched = new Schedule();
	    $sched->aircraft_sub_id = $airframe->aircraft_sub_id;
	    $this->data['flights'] = $sched->find_all();
	}
	elseif($tab == 'aircraft')
	{
	    // Get Airline Airfraft with the same Airframe_id
	    $this->data['aircraft'] = array();	    
	}
	else
	{
	    // Get airlines that use this airframe
	    $this->data['airlines'] = array();
	    $airline = new Airline();
	    $this->data['airlines'] = $airline->get_fleet_airlines($id);
	    $this->data['airline_categories'] = $airline->get_categories();
	}
	
	$this->data['title'] = $airframe->name;
	$this->data['breadcrumb']['fleet'] = 'Fleet';
	$this->data['airframe'] = $airframe;
	$this->_render();	
    }
    
    // Aircraft Substitution Table
    public function view_sub($id = NULL)
    {
	if(is_null($id))
	    $this->index();
		
	$sub = new Aircraft_sub($id);
	
	if(! $sub->designation)
	    $this->index();
	
	$aircraft = new Airframe();
	$aircraft->aircraft_sub_id = $id;
	$aircraft->enabled = 1;
	$sub_fleet = array();
	$sub_fleet = $aircraft->find_all();	
	
	$this->data['title'] = 'Sub Chart: ' . $sub->designation;
	$this->data['breadcrumb']['fleet'] = 'Fleet';
	$this->data['sub'] = $sub;
	$this->data['sub_fleet'] = $sub_fleet;;
	$this->_render();
    }
    
    function autocomplete_sub()
    {
	$aircraft = new Aircraft_sub();	
	$search = $this->input->get('term', TRUE);
	if (isset($search))
	{
	    $data = strtolower($search);
	    echo $aircraft->get_autocomplete($data);
	}
    }
}
