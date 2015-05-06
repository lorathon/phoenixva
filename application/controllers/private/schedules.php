<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules extends PVA_Controller
{

    public function __construct()
    {
	parent::__construct();
    }

    public function index()
    {
	$this->search_schedules();
    }

    public function search_schedules()
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Schedules';
	$this->data['title'] = 'Schedules';

	$this->load->helper(array('form', 'url'));
	$this->load->library('form_validation');

	$this->form_validation->set_rules('flight_num', 'Flight Number', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('operator_id', 'Operator', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('carrier_id', 'Carrier', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('dep_airport_id', 'Departure', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('arr_airport_id', 'Arrival', 'numeric|trim|xss_clean');
	$this->form_validation->set_rules('aircraft_sub_id', 'Aircraft', 'numeric|trim|xss_clean');

	$this->data['scripts'][] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js';

	$schedule = new Schedule();
	$schedules = array();

	if ($this->form_validation->run() == FALSE)
	{
	    $this->data['errors'] = validation_errors();
	    $this->data['search'] = TRUE;
	}
	else
	{
	    $schedule->flight_num = $this->form_validation->set_value('flight_num');
	    $schedule->operator_id = $this->form_validation->set_value('operator_id');
	    $schedule->carrier_id = $this->form_validation->set_value('carrier_id');
	    $schedule->dep_airport_id = $this->form_validation->set_value('dep_airport_id');
	    $schedule->arr_airport_id = $this->form_validation->set_value('arr_airport_id');
	    $schedule->aircraft_sub_id = $this->form_validation->set_value('aircraft_sub_id');

	    $schedules = $schedule->find_all();
	    $this->data['search'] = FALSE;
	}

	/* Set input fields with last search criteria */
	$schedule->dep_airport = $this->input->post('dep_airport', TRUE);
	$schedule->arr_airport = $this->input->post('arr_airport', TRUE);
	$schedule->operator = $this->input->post('operator', TRUE);
	$schedule->carrier = $this->input->post('carrier', TRUE);
	$schedule->aircraft_sub = $this->input->post('aircraft_sub', TRUE);

	$this->data['schedule'] = $schedule;
	$this->data['schedules'] = $schedules;
	$this->_render();
    }

    public function bids($user_id = NULL)
    {
	$this->data['meta_title'] = 'Phoenix Virtual Airways Bids';
	$this->data['scripts'][] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js';

	if (is_null($user_id) || $this->_check_access('manager'))
	    $user_id = $this->data['userdata']['user_id'];

	$user = new User($user_id);

	if ($user_id == $this->data['userdata']['user_id'])
	    $this->data['title'] = 'Your Bids';
	else
	    $this->data['title'] = $user->name . ' - Bids';

	$bid = new Schedule();
	$bids = $bid->get_bids($user_id, TRUE);

	$this->data['user'] = $user;
	$this->data['bids'] = $bids;
	$this->_render();
    }

    public function create_bid($user_id = NULL, $schedule_id = NULL)
    {
	if (is_null($user_id) || is_null($schedule_id))
	    return FALSE;

	$bid = new Schedule($schedule_id);
	$bid->create_bid($user_id);
	$this->bids($user_id);
    }

    public function delete_bid($user_id = NULL, $bid_id = NULL)
    {
	if (is_null($user_id) || is_null($schedule_id))
	    return FALSE;

	$bid = new Schedule();
	$bid->delete_bid($bid_id);
	$this->bids($user_id);
    }

    public function reorder_bids()
    {
	$post = $this->input->get('item', TRUE);
	$bid = new Schedule();
	$bid->reorder_bids($post);
    }

}
