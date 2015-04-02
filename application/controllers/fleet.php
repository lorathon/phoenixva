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
	
	$aircraft = new Aircraft();
	$aircraft->category = $id;	
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
	
	$aircraft = new Aircraft($id);
	
	// send back to index if $aircraft is not found
	if(! $aircraft->equip)
	    $this->index();	
	
	if($tab == 'pireps')
	{
	    $this->data['flights'] = array();
	}
	elseif($tab == 'flights')
	{
	    $this->data['flights'] = array();
	    $sched = new Schedule();
	    $sched->equip = $aircraft->equip;
	    $this->data['flights'] = $sched->find_all();
	}
	elseif($tab == 'main')
	{
	    $this->data['airlines'] = array();
	    $this->data['airlines'] = $aircraft->get_airlines();
	}
	else
	{
	    $this->data['airlines'] = $aircraft->get_airlines(FALSE);
	}
	
	$this->data['title'] = $aircraft->equip . ' - ' . $aircraft->name;
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
	
	$aircraft = new Aircraft();
	$aircraft->aircraft_sub_id = $id;
	$sub_fleet = array();
	$sub_fleet = $aircraft->find_all();	
	
	$this->data['title'] = 'Sub Chart: ' . $sub->designation;
	$this->data['breadcrumb']['fleet'] = 'Fleet';
	$this->data['sub'] = $sub;
	$this->data['sub_fleet'] = $sub_fleet;;
	$this->_render();
    }
    
    public function edit_aircraft($id = NULL)
    {
	$this->_check_access('manager');
		
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Edit Aircraft';
	$this->data['breadcrumb']['fleet'] = 'Fleet';
	
        $aircraft = new Aircraft($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('pax_first', '1st Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_business', 'Business Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_economy', 'Economy Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('max_cargo', 'Cargo Capacity', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('oew', 'Operating Empty Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mzfw', 'Maximum Zero Fuel Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mlw', 'Maximum Landing Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mtow', 'Meximum Take Off Weight', 'integer|trim|xss_clean');
        
        $this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $aircraft;
            $this->_render('admin/aircraft_form');
	}
	else
	{
            $aircraft->id	    = $this->input->post('id', TRUE);
            $aircraft->name	    = $this->form_validation->set_value('name');
	    $aircraft->pax_first    = $this->form_validation->set_value('pax_first');
	    $aircraft->pax_business = $this->form_validation->set_value('pax_business');
	    $aircraft->pax_economy  = $this->form_validation->set_value('pax_economy');
	    $aircraft->max_cargo    = $this->form_validation->set_value('max_cargo');
	    $aircraft->oew	    = $this->form_validation->set_value('oew');
	    $aircraft->mzfw	    = $this->form_validation->set_value('mzfw');
	    $aircraft->mlw	    = $this->form_validation->set_value('mlw');
	    $aircraft->mtow	    = $this->form_validation->set_value('mtow');
                
            $aircraft->save();
	    
	    $this->_alert('Aircraft - Record Saved', 'success', FALSE);
	    $this->index();
	}        
    }
}
