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
