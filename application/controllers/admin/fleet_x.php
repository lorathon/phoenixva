<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fleet_x extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
	
	
	$obj = new Aircraft();
	$this->data['rows'] = $obj->find_all();
	$this->data['title'] = 'All Aircraft';
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/fleet');
    }  
    
    public function missing_sub()
    {
	$this->load->helper('html');
	
	$obj = new Aircraft();
	$obj->aircraft_sub_id = 0;
	$this->data['rows'] = $obj->find_all();
	$this->data['title'] = 'Aircraft Missing Substitution Entry';
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/fleet');
    }
    
    public function substitutions()
    {        
	$this->load->helper('html');
	
	$obj = new Aircraft_sub();
	$this->data['rows'] = $obj->find_all();
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
        $this->_render('admin/fleet_sub');
    }     
    
    public function build_fleet()
    {
	$obj = new Aircraft();
	$obj->create_equip();
	
	$this->_alert_message('success', 'Aircraft Fleet Built');
        $this->index();
    }
    
    public function create_aircraft()
    {
	// Needed?  I am not sure.  A/C are built automatically
    }
    
    public function create_sub($id = NULL)
    {
	$obj = New Aircraft_sub($id);
	
	$this->load->library('form_validation'); 
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('designation', 'Designation', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('manufacturer', 'Manufacturer', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('equips', 'Airframes', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('hours_needed', 'Hours Needed', 'numeric|trim|required|xss_clean');
	
        $this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
	$this->data['hours'] = array(0, 100, 200, 300, 400, 500);
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $obj;
            $this->_render('admin/fleet_sub_form');
	}
	else
	{
            $obj->id		= $this->input->post('id', TRUE);
            $obj->designation	= $this->form_validation->set_value('designation');
            $obj->manufacturer	= $this->form_validation->set_value('manufacturer');
            $obj->equips	= strtoupper($this->form_validation->set_value('equips'));
            $obj->hours_needed	= $this->form_validation->set_value('hours_needed');
	    $obj->category	= intval($this->input->post('category', TRUE));
	    $obj->rated		= intval($this->input->post('rated', TRUE));
                
            $obj->save();
	    $this->_alert_message('success', 'Aircraft Substituion - Record Saved');
            $this->substitutions();
	}        
    }
}