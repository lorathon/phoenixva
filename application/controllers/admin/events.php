<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct();        
        $this->load->helper(array('form', 'url', 'html'));
	$this->load->library('form_validation'); 
    }
    
    public function index()
    {   
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
            $event->color_id	= $event_type->img_folder;            
            $this->data['events'][] = $event;
        }
        
        $this->_render('admin/events');
    }    
    
    public function event_types()
    {
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
        $event = New Event($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('event_image', 'Event Image', 'trim|required|xss_clean');
        $this->form_validation->set_rules('event_type_id', 'Event Type', 'numeric|trim|required|xss_clean');
        
        $event_type = new Event_type();
        $this->data['event_types'] = $event_type->get_dropdown();
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $event;
            $this->_render('admin/event_form');
	}
	else
	{
            $event->id              = $this->input->post('id', TRUE);
            $event->name            = $this->form_validation->set_value('name');
            $event->event_type_id   = $this->form_validation->set_value('event_type_id');
            $event->description     = $this->form_validation->set_value('description');
            $event->event_image     = $this->form_validation->set_value('event_image');
                
            $event->save();
            $this->_flash_message('success', 'Event', 'Record Saved');
            $this->index();
	}        
    }
    
    public function create_event_type($id = NULL)
    {
        $event_type = new Event_type($id); 
                
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
            $event_type->color_id	= $this->form_validation->set_value('color_id');
                
            $event_type->save();
            $this->_flash_message('success', 'Event Type', 'Record Saved');
            
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