<?php

class Flightstats extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }
    
    public function index()
    {        	
	$this->_render('admin/flightstats');
    }   
    
    public function activate()
    {
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->form_validation->set_rules('activate_number', 'Activation Count', 'integer|trim|xss_clean');
	
	if ($this->form_validation->run())
	{             
	    $count = $this->input->post('activate_number');
	    
	    if($count > 1000)
		$count = 1000;
	    
	    $schedule = new Schedule();
	    $done = $schedule->activate($count);
	    
	    $this->_alert('Activation - '.$done.' Schedules Activated', 'success', FALSE);
	    $this->index();
	}
	else
	{
	    $this->_alert('Activate Schedules', 'error', FALSE);
	    $this->index();
	}
    }
}