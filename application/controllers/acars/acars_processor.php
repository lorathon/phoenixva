<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acars_processor extends PVA_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	
	/**
	 * Files a position report
	 * 
	 * If the report does not include a PIREP ID and the pilot does not have an
	 * open PIREP, this will open a PIREP using the position report as the 
	 * departure point.
	 */
	public function update()
	{
		if ($this->form_validation->run())
		{
			$user_id = $this->form_validation->set_value('user_id');
			$client = $this->form_validation->set_value('client');
			$pirep_id = $this->form_validation->set_value('pirep_id'); 
			
			if ($pirep_id == 0)
			{
				$search = new Pirep();
				$search->user_id = $user_id;
				$pirep = $search->find_open();
					
				if (is_null($pirep->id))
				{
					$pirep->dep_lat = $this->form_validation->set_value('lat');
					$pirep->dep_long = $this->form_validation->set_value('long');
					$pirep->open();
				}
				$pirep_id = $pirep->id;
			}
				
			$position = new Position();
			$position->user_id = $user_id;
			$position->pirep_id = $pirep_id;
			$position->altitude = $this->form_validation->set_value('altitude');
			$position->altitude_agl = $this->form_validation->set_value('altitude_agl');
			$position->altitude_msl = $this->form_validation->set_value('altitude_msl');
			$position->bank = $this->form_validation->set_value('bank');
			$position->flown_time = $this->form_validation->set_value('flown_time');
			$position->fuel_onboard = $this->form_validation->set_value('fuel_onboard');
			$position->ground_speed = $this->form_validation->set_value('ground_speed');
			$position->heading = $this->form_validation->set_value('heading');
			$position->indicated_airspeed = $this->form_validation->set_value('indicated_airspeed');
			$position->ip_address = $this->form_validation->set_value('ip_address');
			$position->landed = $this->form_validation->set_value('landed');
			$position->lat = $this->form_validation->set_value('lat');
			$position->long = $this->form_validation->set_value('long');
			$position->load = $this->form_validation->set_value('load');
			$position->ontime = $this->form_validation->set_value('ontime');
			$position->phase = $this->form_validation->set_value('phase');
			$position->pitch = $this->form_validation->set_value('pitch');
			$position->remain_dist = $this->form_validation->set_value('remain_dist');
			$position->remain_time = $this->form_validation->set_value('remain_time');
			$position->true_airspeed = $this->form_validation->set_value('true_airspeed');
			$position->vertical_speed = $this->form_validation->set_value('vertical_speed');
			$position->warning = $this->form_validation->set_value('warning');
			$position->warning_detail = $this->form_validation->set_value('warning_detail');
			$position->save();
		}
		$this->_render();
	}
	
	/**
	 * Files a PIREP at the completion of a flight.
	 */
	public function file_pirep()
	{
		log_message('debug', 'Filing PIREP');
		if ($this->form_validation->run())
		{
			log_message('debug', 'PIREP passed validation.');
			$user_id = $this->form_validation->set_value('user_id');
			$client = $this->form_validation->set_value('client');

			// Grab the user prior to filing to determine if a promotion occurs
			$prefile_user = new User($user_id);
			
			$pirep = new Pirep();
			$pirep->ac_model = $this->form_validation->set_value('ac_model');
			$pirep->ac_title = $this->form_validation->set_value('ac_title');
			if ($client == 'PVACARSII')
			{
				// This client supports Away From Keyboard checks
				$pirep->afk_attempts = $this->form_validation->set_value('afk_attempts');
				$pirep->afk_elapsed = $this->form_validation->set_value('afk_elapsed');
			}
			
			// Find the airline_aircraft ID
			$airline_icao = substr($this->form_validation->set_value('flightnumber'), 0, 3);
			$airline = new Airline(array('icao' => $airline_icao));
			$airframe = new Airframe();
			$aircraft = $airline->get_aircraft($airframe->id);
			$pirep->airline_aircraft_id = $aircraft->id;
			$pirep->arr_icao = $this->form_validation->set_value('arr_icao');
			$pirep->arr_lat = $this->form_validation->set_value('arr_lat');
			$pirep->arr_long = $this->form_validation->set_value('arr_long');
			$pirep->cargo = $this->form_validation->set_value('cargo');
			$pirep->checkride = $this->form_validation->set_value('checkride');
			$pirep->client = $client;
			$pirep->dep_icao = $this->form_validation->set_value('dep_icao');
			$pirep->dep_lat = $this->form_validation->set_value('dep_lat');
			$pirep->dep_long = $this->form_validation->set_value('dep_long');
			$pirep->distance = $this->form_validation->set_value('distance');
			$pirep->event = $this->form_validation->set_value('event');
			$pirep->flight_level = $this->form_validation->set_value('flight_level');
			$pirep->flight_number = $this->form_validation->set_value('flightnumber');
			$pirep->flight_type = $this->form_validation->set_value('flight_type');
			$pirep->fuel_out = $this->form_validation->set_value('fuel_out');
			$pirep->fuel_off = $this->form_validation->set_value('fuel_off');
			$pirep->fuel_toc = $this->form_validation->set_value('fuel_toc');
			$pirep->fuel_tod = $this->form_validation->set_value('fuel_tod');
			$pirep->fuel_on = $this->form_validation->set_value('fuel_on');
			$pirep->fuel_in = $this->form_validation->set_value('fuel_in');
			$pirep->fuel_used = $this->form_validation->set_value('fuel_used');
			$pirep->hours_dawn = $this->form_validation->set_value('hours_dawn');
			$pirep->hours_day = $this->form_validation->set_value('hours_day');
			$pirep->hours_dusk = $this->form_validation->set_value('hours_dusk');
			$pirep->hours_night = $this->form_validation->set_value('hours_night');
			$pirep->hub_id = $prefile_user->hub;
			$pirep->landing_rate = $this->form_validation->set_value('landing_rate');
			$pirep->online = $this->form_validation->set_value('online');
			$pirep->pax_business = $this->form_validation->set_value('pax_business');
			$pirep->pax_economy = $this->form_validation->set_value('pax_economy');
			$pirep->pax_first = $this->form_validation->set_value('pax_first');
			$pirep->route = $this->form_validation->set_value('route');
			$pirep->time_out = $this->form_validation->set_value('time_out');
			$pirep->time_off = $this->form_validation->set_value('time_off');
			$pirep->time_on = $this->form_validation->set_value('time_on');
			$pirep->time_in = $this->form_validation->set_value('time_in');
			$pirep->user_id = $user_id;
			$pirep->file();
			
			if ($this->form_validation->set_value('comments') != '')
			{
				$pirep->set_note($this->form_validation->set_value('comments'), $user_id);
			}
			
			$this->data['pirep'] = $pirep;
			
			$postfile_user = new User($user_id);
			if ($prefile_user->rank_id != $postfile_user->rank_id)
			{
				$this->data['user'] = $postfile_user;
				$this->data['rank'] = new Rank($postfile_user->rank_id);
				$check_rank = new Rank($prefile_user->rank_id);
				if ($check_rank->next_rank() == $this->data['rank'])
				{
					$this->_send_email('promotion', $postfile_user->email, "You've Been Promoted!", $this->data);
				}
				else 
				{
					$this->_send_email('demotion', $postfile_user->email, "You've Been Demoted", $this->data);
				}
			}
			
			if ($pirep->status == Pirep::REJECTED)
			{
				// Notify user their PIREP was rejected
				$this->data['user'] = $postfile_user;
				$this->_send_email('pirep_rejected', $postfile_user->email, 'PIREP Rejected', $this->data);
			}
			
			if ($pirep->status == Pirep::HOLDING)
			{
				// Notify user that the PIREP is held
				$this->data['user'] = $postfile_user;
				$this->_send_email('pirep_holding', $postfile_user->email, 'PIREP Held', $this->data);
				
				// Notify the crew center staff
				if ($postfile_user->find_managers())
				{
					foreach ($postfile_user->find_managers() as $manager)
					{
						$this->_send_email('pirep_holding_staff', $manager->email, 'PIREP Held for '.$postfile_user->name, $this->data);
					}
				}
			}
		}
		else 
		{
			log_message('debug', '********* PIREP failed validation **********');
			log_message('debug', validation_errors());
			log_message('debug', '********************************************');
		}
		$this->_render();
	}
}
