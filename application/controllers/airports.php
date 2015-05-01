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
	$this->load->helper('url');
	
	if(is_null($page))
	    redirect('airports/view/'.$id.'/airlines');
	
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
    
    function edit_airport($id = NULL)
    {
	$this->_check_access('manager');
	$this->load->library('form_validation');
	$this->load->helper('url');

	$this->data['title'] = 'Edit Airport';
	$this->data['breadcrumb']['airports'] = 'Airports';

	$airport = New Airport($id);

	$this->form_validation->set_rules('id', 'ID', '');
	$this->form_validation->set_rules('iata', 'IATA', 'alpha-numeric|trim|xss_clean');
	$this->form_validation->set_rules('icao', 'ICAO', 'alpha-numeric|trim|xss_clean');
	$this->form_validation->set_rules('name', 'Name', 'alpha-numeric|trim|required|xss_clean');
	$this->form_validation->set_rules('lat', 'Latitude', 'numeric|trim|required|xss_clean');
	$this->form_validation->set_rules('long', 'Logitude', 'numeric|trim|required|xss_clean');
	$this->form_validation->set_rules('elevation', 'Elevation', 'numeric|trim|xss_clean');

	$this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');

	if ($this->form_validation->run() == FALSE)
	{
	    $this->data['errors'] = validation_errors();
	    $this->data['record'] = $airport;
	    $this->session->keep_flashdata('return_url');
	    $this->_render('admin/airport_form');
	}
	else
	{
	    $airport->id = $this->input->post('id', TRUE);
	    $airport->iata = $this->form_validation->set_value('iata');
	    $airport->icao = $this->form_validation->set_value('icao');
	    $airport->name = $this->form_validation->set_value('name');
	    $airport->lat = $this->form_validation->set_value('lat');
	    $airport->long = $this->form_validation->set_value('long');
	    $airport->elevation = $this->form_validation->set_value('elevation');
	    $airport->active = $this->input->post('active', TRUE);
	    $airport->hub = $this->input->post('hub', TRUE);

	    $airport->save();

	    $this->_alert('Airport - Record Saved', 'success', TRUE);
	    //redirect($this->session->flashdata('return_url'));
	}
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
