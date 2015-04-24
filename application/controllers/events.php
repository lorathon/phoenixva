<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends PVA_Controller
{
    public function index()
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Events';
	$this->data['title'] = 'Events';
	
	$this->data['events'] = array();
	
	$this->load->helper('url');
	
	$this->data['stylesheets'][] = base_url('assets/admin/vendor/fullcalendar/fullcalendar.css');
	$this->data['stylesheets'][] = base_url('assets/css/calendar.css');
	
	$this->data['scripts'][] = base_url('assets/vendor/jquery/jquery.js');
	$this->data['scripts'][] = base_url('assets/admin/vendor/fullcalendar/lib/moment.min.js');
	$this->data['scripts'][] = base_url('assets/admin/vendor/fullcalendar/fullcalendar.js');
	$this->data['scripts'][] = base_url('assets/js/events.calendar.js');
	
	$event = new Event();
	
	if ($events = $event->find_all())
	{
	    foreach ($events as $event)
	    {
		$this->data['events'][$event->id] = $event->name;
	    }
	}

	$this->_render();
    }
    
    public function event_types()
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Events';
	$this->data['title'] = 'Event Types';
	$this->data['breadcrumb']['events'] = 'Events';
	
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
	$this->_render();
    }  

    public function view($id, $page = NULL)
    {	
	if (is_null($id))
	{
	    $this->index();
	}

	if (strlen($id) > 4)
	{
	    // Handle slug being passed in
	    $parts = explode('-', $id, 3);
	    $id = $parts[1];
	    if (count($parts) > 2)
	    {
		$page = $parts[2];
	    }

	    // Redirect to keep out duplicate content
	    $this->load->helper('url');
	    redirect("events/{$id}/{$page}");
	}

	$event = new Event($id);
	$event->find();

	$this->data['meta_title'] = 'Event: ' . $event->name;
	$this->data['title'] = $event->name;
	$this->data['body'] = '<p>This page has no content.</p>';
	$this->data['id'] = $id;
	$this->data['page'] = $page;
	$this->data['pages'] = $this->_event_navigation($id);
	$this->data['breadcrumb']['events'] = 'Events';
	$this->data['event'] = $event;
	$this->data['event_type'] = $event->get_type_name();
	$this->data['airline'] = $event->get_airline_name();
	$this->data['airport'] = $event->get_airport_name();
	$this->data['aircraft'] = $this->config->item('aircraft_cat');
	
	// fill with pilots who are participating ?
	$this->data['pilots'] = array();

	$article = new Article();
	$article->slug = $this->_build_slug($id, $page);
	$article->find();

	if ($article->body_html)
	{
	    $this->data['body'] = $article->body_html;
	}

	if ($page == 'logbook')
	{
	    $this->data['body'] .= '<p>Logbook not yet implemented</p>';
	}
	
	if ($page == 'awards')
	{
	    $this->data['event_awards'] = $event->get_event_awards();
	    $this->data['body'] = ' ';
	}
	
	if ($page == 'participants')
	{
	    $this->data['event_participants'] = $event->get_participants();
	    $this->data['body'] = ' ';
	}
	
	$this->session->set_flashdata('return_url','events/'.$id);	
	$this->_render();
    }    

    /**
     * Creates a new page for an event
     * 
     * @param string $id of the event to create a page for
     */
    public function create_page($id)
    {
	$this->_check_access('manager');

	$this->data['meta_title'] = 'PVA Admin: Create Event Page';
	$this->data['title'] = 'Create Event Page';
	$this->data['breadcrumb']['events'] = 'Events';

	$this->load->helper('url');
	$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
	$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');

	$this->data['slug'] = 'event-' . $id . '-';
	$this->data['edit_mode'] = FALSE;
	$this->data['pagetitle'] = '';
	$this->data['pagebody'] = '';

	$this->_render('admin/page_form');
    }

    /**
     * Edits a page for an event
     * 
     * Events can have multiple pages.
     */
    public function edit_page($id, $page = NULL)
    {
	$this->_check_access('manager');

	$this->data['meta_title'] = 'PVA Admin: Edit Event Page';
	$this->data['title'] = 'Edit Event Page';
	$this->data['breadcrumb']['events'] = 'Events';

	$this->load->helper('url');
	$this->data['scripts'][] = base_url('assets/sceditor/jquery.sceditor.bbcode.min.js');
	$this->data['stylesheets'][] = base_url('assets/sceditor/themes/default.min.css');

	$this->data['slug'] = $this->_build_slug($id, $page);
	$this->data['edit_mode'] = TRUE;
	$this->data['pagetitle'] = 'Event Home';
	if ($page == 'logbook')
	{
	    $this->data['pagetitle'] = 'Logbook';
	}
	$this->data['pagebody'] = '';

	$article = new Article();
	$article->slug = $this->_build_slug($id, $page);
	$article->find();

	if ($article->title)
	{
	    $this->data['pagetitle'] = $article->title;
	    $this->data['meta_title'] = 'PVA Admin: Edit Event Page';
	    $this->data['title'] = 'Edit Event Page';
	}
	if ($article->body_html)
	{
	    $this->data['pagebody'] = $article->body_html;
	}
	$this->session->set_flashdata('return_url', 'events/' . $id);

	$this->_render('admin/page_form');
    }

    /**
     * Returns an array of article links for the selected event.
     * 
     * @param string $id of the event to search
     * @return array of Article objects
     */
    protected function _event_navigation($id)
    {
	$navigation = array();

	$article = new Article();
	// Add the dash at the end so the home page isn't included (it's automatic)
	$article->slug = $this->_build_slug($id) . '-';
	$nav_list = $article->find_all(TRUE);
	if ($nav_list)
	{
	    foreach ($nav_list as $item)
	    {
		// Don't include default pages
		if (!strstr($item->slug, 'logbook'))
		{
		    $navigation[$item->slug] = $item->title;
		}
	    }
	}
	return $navigation;
    }

    protected function _build_slug($id, $page = NULL)
    {
	$slug = 'event-' . $id;
	if (!is_null($page))
	{
	    $slug .= '-' . $page;
	}
	return $slug;
    }
    
    public function create_event($id = NULL)
    {    
        $this->_check_access('manager');
	$this->data['title'] = 'Create Event';
	$this->data['breadcrumb']['events'] = 'Events';
        
        $event = New Event($id);
        
        if($event->name)
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
	        
        $this->data['scripts'][] = base_url('assets/admin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js');
	$this->data['scripts'][] = base_url('assets/js/typeahead.bundle.js');
	$this->data['scripts'][] = base_url('assets/js/prefetch.js');
	
	$this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
	
                
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
	    $event->enabled	    = $this->input->post('enabled', TRUE); 
	    $event->completed	    = $event->completed == NULL ? 0 : $event->completed;
                
            $event->save();
	    $this->_alert('Event - Record Saved', 'success', TRUE);
	    
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
	$this->_check_access('manager');
	$this->data['title'] = 'Create Event Type';
	$this->data['breadcrumb']['events'] = 'Events';
	$this->data['breadcrumb']['private/event-types'] = 'Events Types';
	
        $event_type = new Event_type($id); 
	
	if($event_type->name)
        {
            $this->data['title'] = 'Edit Event Type';
        }
	
	$this->load->library('form_validation');
        $this->load->helper('url');
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'alpha-numberic|trim|xss_clean');
	
	$this->data['calendar_colors'] = $this->config->item('calendar_colors');
        
        $this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
                
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
	    
	    $this->_alert('Event Type - Record Saved', 'success', FALSE);
            $this->index();
	}        
    }
    
    public function delete_event($id = NULL)
    {
	$this->_check_access('manager');
        // Delete record
        if ( is_null($id) )
	    return FALSE;
        
        $event = new Event($id);
        $event->delete();
	
	$this->_alert('Event - Record Deleted', 'info', FALSE);
        $this->index();
    }
    
    public function delete_event_type($id = NULL)
    {
	$this->_check_access('manager');
        // Delete record
        if ( is_null($id) ) 
	    return FALSE;
	
	$event_type = new Event_type($id);
        
	// Determine if there are any awards of this type
        $event = new Event();
	$event->event_type_id = $id;
	$events = $event->get_events_count();
		
	// If there are not any events of this type allow deletion of type
	if($events == 0)
	{
	    $event_type->delete();
	    $this->_alert('Event Type- Record Deleted', 'info', FALSE);
	    $this->index();
	}
	// If events of this type are found stop deletion and inform user
        else
	{
	    $this->load->helper('url');
	    $this->_alert('Event Type - All awards must be deleted first!', 'danger', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }   
    
    public function add_user($id = NULL)
    {
	if(! is_null($id))
	{
	    $user_id = $this->session->userdata('user_id');
	    $event = new Event($id);
	    $event->add_participant($user_id);
	}
	
	$this->load->helper('url');
	redirect('events/'.$id.'/participants');
    }
    
    public function remove_user($id = NULL)
    {
	if(! is_null($id))
	{
	    $user_id = $this->session->userdata('user_id');
	    $event = new Event($id);
	    $event->remove_participant($user_id);
	}
	
	$this->load->helper('url');
	redirect('events/'.$id.'/participants');
    }
    
    public function create_award($id = NULL)
    {
	
    }
    
    public function get_json()
    {
	$this->load->helper('url');
	
	$event = new Event();
	$events = $event->find_all();
	
	$linklist=array();
	$link=array();
	
	$colors = $this->config->item('calendar_colors');
		
	foreach($events as $ev)
	{
	    $start_date = new DateTime($ev->time_start);
	    $end_date = new DateTime($ev->time_end);	    
	    
	    $link["id"]		= $ev->id;
	    $link["title"]	= $ev->name;
	    $link["start"]	= $start_date->format(DateTime::ISO8601);
	    $link["end"]	= $end_date->format(DateTime::ISO8601);
	    $link["url"]	= base_url() . 'events/' . $ev->id;
	    $link["className"]	= 'fc-event-' . $colors[$ev->get_color_id()];
	    array_push($linklist,$link);
	}
	$this->output->enable_profiler(FALSE);
	echo json_encode($linklist);
    }

}
