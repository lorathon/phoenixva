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
    
    public function view_sub()
    {
        $this->load->helper('url');
        
        $this->data['stylesheets'][] = base_url('assets/css/datatables.css');
        //$this->data['scripts'][] = "//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js";
        $this->data['scripts'][] = "//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js";
        $this->data['scripts'][] = "//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js";
        $this->data['scripts'][] = base_url('assets/js/datatables.js');
        
	$sub = new Aircraft_sub();	
	$this->data['airframe_subs'] = $sub->find_all();
	$this->data['cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/airframe_sub');	
    }
    
    public function create_airframe($id = NULL)
    {		
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Edit Airframe';
	
	if(is_null($id))
	    $this->data['title'] = 'Create Airframe';
	
        $aircraft = new Airframe($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('pax_first', '1st Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_business', 'Business Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_economy', 'Economy Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('payload', 'Payload Capacity', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('max_range', 'Maximum Range', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('oew', 'Operating Empty Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mzfw', 'Maximum Zero Fuel Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mlw', 'Maximum Landing Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mtow', 'Meximum Take Off Weight', 'integer|trim|xss_clean');
	
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
	    $aircraft->helicopter   = $this->input->post('helicopter', TRUE);
	    $aircraft->pax_first    = $this->form_validation->set_value('pax_first');
	    $aircraft->pax_business = $this->form_validation->set_value('pax_business');
	    $aircraft->pax_economy  = $this->form_validation->set_value('pax_economy');
	    $aircraft->payload	    = $this->form_validation->set_value('payload');
	    $aircraft->max_range    = $this->form_validation->set_value('max_range');
	    $aircraft->oew	    = $this->form_validation->set_value('oew');
	    $aircraft->mzfw	    = $this->form_validation->set_value('mzfw');
	    $aircraft->mlw	    = $this->form_validation->set_value('mlw');
	    $aircraft->mtow	    = $this->form_validation->set_value('mtow');
                
            $aircraft->save();
	    
	    $this->_alert('Airframe - Record Saved', 'success', FALSE);
	    $this->index();
	}        
    }
    
    public function create_sub($id = NULL)
    {		
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Edit Substitution';
	
	if(is_null($id))
	    $this->data['title'] = 'Create Substitution';
	
        $aircraft = new Aircraft_sub($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('designation', 'Designation', 'alpha-numeric|trim|required|xss_clean');
        $this->form_validation->set_rules('manufacturer', 'Manufacturer', 'alpha-numeric|trim|xss_clean');
	$this->form_validation->set_rules('equips', 'Airframes', 'alpha-numeric|trim|xss_clean');
	$this->form_validation->set_rules('hours_needed', 'TR Hours Needed', 'integer|trim|xss_clean');
	
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
	$this->data['hours'] = array('1'=>1, '100'=>100, '200'=>200, '300'=>300, '400'=>400, '500'=>500, '600'=>600, '700'=>700, '800'=>800, '900'=>900);
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $aircraft;
            $this->_render('admin/airframe_sub_form');
	}
	else
	{
            $aircraft->id	    = $this->input->post('id', TRUE);
            $aircraft->designation  = $this->form_validation->set_value('designation');
	    $aircraft->manufacturer = $this->form_validation->set_value('manufacturer');
	    $aircraft->equips	    = $this->form_validation->set_value('equips');
	    $aircraft->hours_needed  = $this->form_validation->set_value('hours_needed');
	    $aircraft->category	    = $this->input->post('category', TRUE);
	    $aircraft->rated	    = $this->input->post('rated', TRUE);
                
            $aircraft->save();
	    
	    $this->_alert('Aircraft Substitution - Record Saved', 'success', FALSE);
	    $this->view_sub();
	}        
    }
}
