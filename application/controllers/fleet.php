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
	
	$aircraft = new Airframe();
	$aircraft->category = $id;	
	$aircraft->enabled = 1;
	$fleet = $aircraft->find_all();
	
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
	
	$aircraft = new Airframe($id);
	
	// Send back to index if $aircraft is not found
	if(! $aircraft->name)
	    $this->index();	
	
	if($tab == 'pireps')
	{
	    // Get pireps using this airframe
	    $this->data['flights'] = array();
	}
	elseif($tab == 'flights')
	{
	    // Get schedules using this airframe
	    $this->data['flights'] = array();
	    $sched = new Schedule();
	    $sched->airframe_id = $aircraft->id;
	    $this->data['flights'] = $sched->find_all();
	}
	else
	{
	    // Get airlines that use this airframe
	    $this->data['airlines'] = array();
	    $airline = new Airline();
	    $this->data['airlines'] = $airline->get_fleet_airlines($id);
	    $this->data['airline_categories'] = $airline->get_categories();
	}
	
	$this->data['title'] = $aircraft->name;
	$this->data['breadcrumb']['fleet'] = 'Fleet';
	$this->data['aircraft'] = $aircraft;
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
}
