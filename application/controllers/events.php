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

    public function view($id, $page = NULL)
    {
	log_message('debug', 'Event page called');
	
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
	    log_message('debug', 'Splitting event slug');
	    log_message('debug', 'ID = ' . $id);
	    log_message('debug', 'Page = ' . $page);

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
	$this->data['award_1'] = new Award($event->award_id_winner);
	$this->data['award_2'] = new Award($event->award_id_participant);
	$this->data['user_1'] = new User($event->user_id_1);
	$this->data['user_2'] = new User($event->user_id_2);
	$this->data['user_3'] = new User($event->user_id_3);
	
	
	// fill with pilots who are participating ?
	$this->data['pilots'] = array();

	$article = new Article();
	$article->slug = $this->_build_slug($id, $page);
	$article->find();

	if ($article->body)
	{
	    $this->data['body'] = $article->body;
	}

	if ($page == 'logbook')
	{
	    $this->data['body'] .= '<p>Logbook not yet implemented</p>';
	}
	
	$this->session->set_flashdata('return_url','events/'.$id);	
	$this->_render();
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
	$this->form_validation->set_rules('landing_rate', 'Landing Rate', 'numeric|trim|xss_clean');
        
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
	    
	    redirect($this->session->flashdata('return_url'));
	    //$this->view($id);
	}        
    }

    /**
     * Creates a new page for an event
     * 
     * @param string $id of the event to create a page for
     */
    public function create_page($id)
    {
	log_message('debug', 'Events page create called');
	$this->_check_access('manager');

	$this->data['meta_title'] = 'PVA Admin: Create Event Page';
	$this->data['title'] = 'Create Event Page';

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
	log_message('debug', 'Event page edit called');
	$this->_check_access('manager');

	$this->data['meta_title'] = 'PVA Admin: Edit Event Page';
	$this->data['title'] = 'Edit Event Page';

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
	if ($article->body)
	{
	    $this->data['pagebody'] = $article->body;
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
