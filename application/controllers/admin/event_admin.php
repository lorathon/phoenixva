<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Event_admin extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct(); 
    }
    
    public function index()
    {   
	$this->load->helper('html');
	
        $event = New Event();
        $events = $event->find_all();
        
	if(! $events)
	{
	    $events = array();
	    $this->data['events'] = $events;
	}
	
        foreach($events as $event)
        {            
            $event_type = $event->get_event_type();            
            $event->type	= $event_type->name;
            $event->color_id	= $event_type->color_id;            
            $this->data['events'][] = $event;
        }
        $this->session->set_flashdata('return_url','admin/event_admin');
        $this->_render('admin/events');
    }    
        
    public function event_types()
    {
	$this->load->helper('html');
	
	$event_type = New Event_type();
	$event_types = $event_type->find_all();
	
	if(! $event_types)
	{
	    $event_types = array();
	    $this->data['types'] = $event_types;
	}	
	
	$this->data['calendar_colors'] = $this->config->item('calendar_colors');
        $this->data['types'] = $event_types;
        $this->_render('admin/event_types');
    }  
    
    public function create_event($id = NULL)
    {    
        $this->_check_access('manager');
	$this->data['title'] = 'Create Event';
        
        $event = New Event($id);
        
        if($event)
        {
            $this->data['title'] = 'Edit Event';
        }
	
	$this->load->library('form_validation'); 
        $this->load->helper('url');
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('event_type_id', 'Event Type', 'numeric|trim|required|xss_clean');
	$this->form_validation->set_rules('landing_rate', 'Landing Rate', 'numeric|required|trim|xss_clean');
	$this->form_validation->set_rules('flight_time', 'Flight Time', 'numeric|required|trim|xss_clean');
        
        $event_type = new Event_type();
        $this->data['event_types'] = $event_type->get_dropdown();
	
	$airline = new Airline();
	$this->data['airlines'] = $airline->get_dropdown();
	
	$airport = new Airport();
	$this->data['airports'] = $airport->get_dropdown();
	
	$this->data['aircraft_cats'] = $this->config->item('aircraft_cat');
	
	$this->data['zero_to_ten'] = array(0,1,2,3,4,5,6,7,8,9);
	
	$award = new Award();
	$this->data['awards'] = $award->get_dropdown();
        
        $this->data['scripts'][] = base_url('assets/admin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js');
	$this->data['scripts'][] = base_url('assets/js/typeahead.bundle.js');
	$this->data['scripts'][] = base_url('assets/js/prefetch.js');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $event;
	    $this->session->keep_flashdata('return_url');
            $this->_render('admin/event_form');
	}
	else
	{	
	    $_time_start = strtotime($this->input->post('time_start', TRUE));
	    $_time_start = date("Y-m-d H:i:s", $_time_start);
	    
	    $_time_end = strtotime($this->input->post('time_end', TRUE));
	    $_time_end = date("Y-m-d 23:59:59", $_time_end);
	    
            $event->id              = $this->input->post('id', TRUE);
            $event->name            = $this->form_validation->set_value('name');            
            $event->description     = $this->form_validation->set_value('description');
	    $event->time_start	    = $_time_start;
	    $event->time_end	    = $_time_end;
	    $event->event_type_id   = $this->form_validation->set_value('event_type_id');
	    $event->waiver_js	    = $this->input->post('waiver_js', TRUE); 
	    $event->waiver_cat	    = $this->input->post('waiver_cat', TRUE);
	    $event->airline_id	    = intval($this->input->post('airline_id', TRUE));
	    $event->airport_id	    = intval($this->input->post('airport_id', TRUE));
	    $event->aircraft_cat_id = intval($this->input->post('aircraft_cat_id', TRUE));
	    $event->landing_rate    = $this->form_validation->set_value('landing_rate');
	    $event->total_flights   = intval($this->input->post('total_flights', TRUE));
	    $event->flight_time	    = $this->input->post('flight_time', TRUE);
	    $event->bonus_1	    = intval($this->input->post('bonus_1', TRUE));
	    $event->bonus_2	    = intval($this->input->post('bonus_2', TRUE));
	    $event->bonus_3	    = intval($this->input->post('bonus_3', TRUE));
	    $event->award_id_winner = intval($this->input->post('award_id_winner', TRUE));
	    $event->award_id_participant    = intval($this->input->post('award_id_participant', TRUE));
	    $event->enabled	    = $this->input->post('enabled', TRUE); 
	    
	    $event->completed	    = $event->completed == NULL ? 0 : $event->completed;
	    $event->user_id_1	    = $event->user_id_1 == NULL ? 0 : $event->user_id_1;
	    $event->user_id_2	    = $event->user_id_2 == NULL ? 0 : $event->user_id_2;
	    $event->user_id_3	    = $event->user_id_3 == NULL ? 0 : $event->user_id_3;
                
            $event->save();
	    $this->_flash_message('success', 'Event', 'Event - Record Saved');
	    
	    $url = $this->session->flashdata('return_url');	    
	    if($url)
	    {
		redirect($this->session->flashdata('return_url'));
	    }
	    else
	    {
		$this->index();
	    }	  
	}        
    }
    
    public function create_event_type($id = NULL)
    {
        $event_type = new Event_type($id); 
	
	$this->load->library('form_validation'); 
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|xss_clean');
	
	$this->data['calendar_colors'] = $this->config->item('calendar_colors');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $event_type;
            $this->_render('admin/event_type_form');
	}
	else
	{
            $event_type->id		= $this->input->post('id', TRUE);
            $event_type->name		= $this->form_validation->set_value('name');
            $event_type->description	= $this->form_validation->set_value('description');
            $event_type->color_id	= $this->input->post('color_id', TRUE);
                
            $event_type->save();
            $this->_alert_message('success', 'Event Type - Record Saved');
            $this->event_types();
	}        
    }
    
    public function delete_event($id = NULL)
    {
        // Delete record
        if ( is_null($id) )
	    return FALSE;
        
        $event = new Event($id);
        $event->delete();
        
        $this->_flash_message('info', 'Event', 'Record Deleted');
        $this->index();
    }
    
    public function delete_event_type($id = NULL)
    {
        // Delete record
        if ( is_null($id) ) 
	    return FALSE;
        
        $event = new Event();
        
        $event->_event_type->id = $id;
        $event->_event_type->delete();
        
        $this->_flash_message('info', 'Event Type', 'Record Deleted');
        $this->event_types();
    }   
    
}