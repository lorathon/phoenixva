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