<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airlines extends PVA_Controller
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
	    redirect('airlines/main');

	$this->data['meta_title'] = 'Phoenix Virtual Airways Airlines';
	$this->data['title'] = 'Airlines';

	$this->data['airlines'] = array();

	$airline = new Airline();

	if ($type == 'regional')
	{
	    $airline->regional = TRUE;
	}
	else
	{
	    $airline->regional = FALSE;
	}
	$airline->active = TRUE;

	$airlines = $airline->find_all();

	$this->data['airlines'] = $airlines;
	$this->session->set_flashdata('return_url', 'airlines/' . $type);
	$this->_render();
    }

    public function view($id, $page = NULL)
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Airlines';
	$this->data['breadcrumb']['airlines'] = 'Airlines';

	$airline = new Airline($id);

	// send back to index if $airline is not found
	if (!$airline->name)
	    $this->index();

	$this->data['title'] = $airline->name;

	if ($page == 'destinations')
	{
	    // destinations
	    $airports = $airline->get_destinations();
	    $this->data['airports'] = $airports;
	}
	else
	{
	    // fleet
	    $fleet = $airline->get_fleet();
	    $this->data['fleet'] = $fleet;
	}

	$this->data['airline'] = $airline;
	$this->session->set_flashdata('return_url', 'airlines/view/' . $id);
	$this->_render('airline_view');
    }

    public function edit_airline($id = NULL)
    {
	$this->_check_access('manager');
	$this->load->library('form_validation');
	$this->load->helper('url');

	$this->data['title'] = 'Edit Airline';
	$this->data['breadcrumb']['airlines'] = 'Airlines';

	$airline = New Airline($id);

	$this->form_validation->set_rules('id', 'ID', '');
	$this->form_validation->set_rules('iata', 'IATA', 'alpha-numberic|trim|xss_clean');
	$this->form_validation->set_rules('icao', 'ICAO', 'alpha-numberic|trim|xss_clean');
	$this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');

	$this->data['categories'] = $airline->get_category_dropdown();

	$this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');

	if ($this->form_validation->run() == FALSE)
	{
	    $this->data['errors'] = validation_errors();
	    $this->data['record'] = $airline;
	    $this->session->keep_flashdata('return_url');
	    $this->_render('admin/airline_form');
	}
	else
	{
	    $airline->id = $this->input->post('id', TRUE);
	    $airline->iata = $this->form_validation->set_value('iata');
	    $airline->icao = $this->form_validation->set_value('icao');
	    $airline->name = $this->form_validation->set_value('name');
	    $airline->category = $this->form_validation->set_value('category');
	    $airline->active = $this->input->post('active', TRUE);

	    $airline->save();

	    $this->_alert('Airline - Record Saved', 'success', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }

    public function edit_aircraft($aircraft_id = NULL)
    {
	$this->_check_access('manager');
	$this->load->library('form_validation');
	$this->load->helper('url');

	$this->data['title'] = 'Edit Aircraft';
	$this->data['breadcrumb']['airlines'] = 'Airlines';

	$airline = New Airline();
	$aircraft = $airline->get_aircraft($aircraft_id);

	$airline = new Airline($aircraft->airline_id);
	$airframe = new Airframe($aircraft->airframe_id);

	$aircraft->name = $airline->name . ' - ' . $airframe->name;

	$this->form_validation->set_rules('id', 'ID', '');
	$this->form_validation->set_rules('pax_first', 'First Class Seating', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_business', 'Business Class Seating', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_economy', 'Economy Class Seating', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('payload', 'Payload Capacity', 'integer|trim|xss_clean');

	$this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');

	if ($this->form_validation->run() == FALSE)
	{
	    $this->data['errors'] = validation_errors();
	    $this->data['record'] = $aircraft;
	    $this->session->keep_flashdata('return_url');
	    $this->_render('admin/aircraft_form');
	}
	else
	{
	    $aircraft->id = $this->input->post('id', TRUE);
	    $aircraft->pax_first = $this->form_validation->set_value('pax_first');
	    $aircraft->pax_business = $this->form_validation->set_value('pax_business');
	    $aircraft->pax_economy = $this->form_validation->set_value('pax_economy');
	    $aircraft->payload = $this->form_validation->set_value('payload');

	    $aircraft->save();

	    $this->_alert('Aircraft - Record Saved', 'success', TRUE);
	    redirect($this->session->flashdata('return_url'));
	}
    }
    
    function autocomplete()
    {
	$airline = new Airline();
	if (isset($_GET['term']))
	{
	    $data = strtolower($_GET['term']);
	    echo $airline->get_autocomplete($data);
	}
    }

}
