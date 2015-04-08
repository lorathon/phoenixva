<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airframes extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {		
	$airframe = new Airframe();	
	$this->data['airframes'] = $airframe->find_all();
	$this->data['cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/airframes');
    }
    
    public function edit_airframe($id = NULL)
    {
	$this->_check_access('manager');
		
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Edit Aircraft';
	
        $aircraft = new Airframe($id);
                
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
        
        //$this->data['scripts'][] = base_url('assets/admin/vendor/jquery-validation/jquery.validate.js');
	//$this->data['scripts'][] = base_url('assets/js/forms.validation.js');
	
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $aircraft;
            $this->_render('admin/airframe_form');
	}
	else
	{
            $aircraft->id	    = $this->input->post('id', TRUE);
            $aircraft->name	    = $this->form_validation->set_value('name');
	    $aircraft->category	    = $this->input->post('category', TRUE);
	    $aircraft->regional	    = $this->input->post('regional', TRUE);
	    $aircraft->turboprop    = $this->input->post('turboprop', TRUE);
	    $aircraft->jet	    = $this->input->post('jet', TRUE);
	    $aircraft->widebody	    = $this->input->post('widebody', TRUE);
	    $aircraft->pax_first    = $this->form_validation->set_value('pax_first');
	    $aircraft->pax_business = $this->form_validation->set_value('pax_business');
	    $aircraft->pax_economy  = $this->form_validation->set_value('pax_economy');
	    $aircraft->max_cargo    = $this->form_validation->set_value('max_cargo');
	    $aircraft->oew	    = $this->form_validation->set_value('oew');
	    $aircraft->mzfw	    = $this->form_validation->set_value('mzfw');
	    $aircraft->mlw	    = $this->form_validation->set_value('mlw');
	    $aircraft->mtow	    = $this->form_validation->set_value('mtow');
                
            $aircraft->save();
	    
	    $this->_alert('Airframe - Record Saved', 'success', FALSE);
	    $this->index();
	}        
    }
}
