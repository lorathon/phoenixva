<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules extends PVA_Controller
{
    
    public function __construct()
    {
        parent::__construct();       
    }

    function index()
    {	
        $this->search_schedules();
    }
    
    function search_schedules()
    {
	$this->load->helper(array('form', 'url'));
	$this->load->library('form_validation'); 
	
	$this->form_validation->set_rules('flight_num', 'Flight Number', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('operator_id', 'Operator', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('carrier_id', 'Carrier', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('dep_airport_id', 'Departure', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('arr_airport_id', 'Arrival', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('aircraft_sub_id', 'Aircraft', 'numeric|trim|xss_clean');
	
	$this->data['scripts'][] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js';
	$this->data['scripts'][] = base_url('assets/js/custom.autocomplete.js');
	
	$schedules = array();
	
	if ($this->form_validation->run() == FALSE)
	{
	    $this->data['errors'] = validation_errors();
	    $this->data['search'] = TRUE;
	}
	else
	{
	    $schedule = new Schedule();
	    $schedule->flight_num = $this->form_validation->set_value('flight_num');
	    $schedule->operator_id = $this->form_validation->set_value('operator_id');
	    $schedule->carrier_id = $this->form_validation->set_value('carrier_id');
	    $schedule->dep_airport_id = $this->form_validation->set_value('dep_airport_id');
	    $schedule->arr_airport_id = $this->form_validation->set_value('arr_airport_id');
	    $schedule->aircraft_sub_id = $this->form_validation->set_value('aircraft_sub_id');
	    
	    $schedules = $schedule->find_all();
	    $this->data['search'] = FALSE;
	}
		
	$this->data['schedules'] = $schedules;
	$this->_render();
    }
    
    function bids($user_id = NULL)
    {
	$bid = new Schedule();
	$bids = $bid->get_bids($user_id, TRUE);
	
	$this->data['user'] = new User($user_id);
	$this->data['bids'] = $bids;
	$this->_render();
    }
    
    function create_bid($user_id = NULL, $schedule_id = NULL)
    {
	$bid = new Schedule($schedule_id);
	$bid->create_bid($user_id);
    }
    
    function delete_bid($bid_id = NULL)
    {
	$bid = new Schedule();
	$bid->delete_bid($bid_id);	
    }

}
