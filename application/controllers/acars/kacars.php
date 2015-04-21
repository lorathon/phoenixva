<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'acars_base.php';
/**
 * ACARS controller for the kACARS client by Jeffrey Kobus (www.fs-products.net).
 *
 * @author Chuck Topinka
 * @author Jeffrey Kobus
 *
*/
class Kacars extends Acars_Base
{
	/**
	 * User ID for the incoming request
	 * 
	 * @var integer
	 */
	private $_user_id = NULL;
	
	/**
	 * Identifier for this ACARS client
	 * 
	 * @var string
	 */
	private $_client = 'PVACARSII';
	
	/**
	 * Handle incoming request
	 * 
	 * KACARS and its derivitives use a front controller pattern. An XML document
	 * is submitted as input with the switch element set to indicate what function
	 * to execute.
	 */
	public function index()
	{
		// XXX Probably need more security here.
		$input = file_get_contents('php://input');
		$xml = simplexml_load_string($input);
		
		log_message('debug', 'Incoming kACARS Message:');
		log_message('debug', print_r($xml, TRUE));
		log_message('debug', '-----------------------------');
		
		// Switch data contains the function to run
		$function = $xml->switch->data;
		
		if (method_exists($this, $function))
		{
			$this->_user_id = $xml->data->pilotID;
			if ( ! is_int($this->_user_id))
			{
				// Probably used PVA#### instead of just ####
				$this->_user_id = substr($this->_user_id, 3);
			}
			$this->$function($xml);
		}
				
		// Respond to the ACARS client
		$this->sendXML($this->_params, $function);
	}
	
	/**
	 * Logs the ACARS client in
	 * 
	 * @param SimpleXMLElement $xml
	 */
	protected function login($xml)
	{		
		$user = new User();
		$user->id = $this->_user_id;
		$user->password = $xml->data->password;
		$user->last_ip = $_SERVER['REMOTE_ADDR'];
		if ($user->login())
		{
			// Login was successful, prepare result
			$this->_params['loginStatus'] = 1;
			$this->_params['pilotcode'] = $user->id;
			
			$parts = explode(' ', $user->name, 2);
			$firstname = $parts[0];
			$lastname = '';
			if (isset($parts[1]))
			{
				$lastname = $parts[1];
			}
			$this->_params['firstname'] = $firstname;
			$this->_params['lastname'] = $lastname;

			$this->_params['hub'] = $user->hub;
			$this->_params['currlocation'] = $user->get_user_stats()->current_location;
			$this->_params['totalflights'] = $user->get_user_stats()->total_flights();
			$this->_params['totalhours'] = $user->get_user_stats()->total_hours();
			$this->_params['totalpay'] = $user->get_user_stats()->total_pay;
					
			$rank = new Rank($user->rank_id);
			$this->_params['rank'] = $rank->rank;
			$this->_params['short'] = $rank->short;
			$this->_params['rankimage'] = $rank->rank_image;
			
			// Not sure what is expected here
			$this->_params['acars_ver'] = 'TEST';
			$this->_params['acars_msg'] = 'TEST';
			$this->_params['acars_afk'] = '';
			$this->_params['acars_paxcap'] = '';
			$this->_params['acars_cargocap'] = '';
			$this->_params['ftp_address'] = '';
			$this->_params['ftp_user'] = '';
			$this->_params['ftp_pw'] = '';
			$this->_params['chat_address'] = '';
			$this->_params['chat_port'] = '';
			$this->_params['ss_time'] = '';
			
			($user->is_manager()) ? $staff = 1 : $staff = 0;
			$this->_params['staff'] = $staff;
		}
		else 
		{
			// Login failed
			$this->_params['loginStatus'] = 0;
			$this->_params['message'] = 'Unable to log in with that user and password.';
		}
	}

	protected function getBid($xml)
	{
		
	}
	
	protected function getFlight($xml)
	{
		
	}
	
	/**
	 * Processes a position update
	 * 
	 * Uses asynchronous communication
	 * 
	 * @param SimpleXMLElement $xml
	 */
	protected function liveUpdate($xml)
	{
		if ($xml->liveupdate)
		{
			$update = $xml->liveupdate;
			
			// Translate KACARS to Live Update fields
			$fields = array(
					'client='.$this->_client,
					'user_id='.$this->_user_id,
					'ip_address='.$_SERVER['REMOTE_ADDR'],
					'pirep_id=0',
					'load=',
					'lat='.$update->lat,
					'long='.$update->lng,
					'altitude='.$update->alt,
					'altitude_agl=',
					'altitude_msl=',
					'heading='.$update->heading,
					'ground_speed='.$update->gs,
					'true_airspeed=',
					'indicated_airspeed=',
					'vertical_speed='.$update->vs,
					'bank=',
					'pitch=',
					'fuel_onboard='.$update->fob,
					'phase='.$update->phase,
					'warning=',
					'warning_detail=',
					'remain_dist='.$update->remain_dist,
					'flown_time='.$update->flown_time,
					'landed='.$update->landed,
			);
			
			$message = implode($this->_field_separator, $fields);

			// Dispatch to appropriate handler
			$this->dispatch($message, $this->_acars_processor_path.'update');
			log_message('debug', 'Live Update returning to client');
		}
		else
		{
			// No liveupdate data provided
		}
	}
	
	/**
	 * File a PIREP
	 * 
	 * Uses asynchronous communication so only assumes that the PIREP filed
	 * 
	 * @param SimpleXMLElement $xml
	 */
	protected function pirep($xml)
	{
		if ($xml->pirep)
		{
			$update = $xml->liveupdate;
			$pirep = $xml->pirep;
			
			// Translate KACARS to PIREP fields
			$fields = array(
					'client='.$this->_client,
					'user_id='.$this->_user_id,
					'hub_id='.$xml->data->crewcenter,
					'flight_number='.$pirep->flightnumber,
					'flight_type='.$pirep->flighttype,
					'dep_icao='.$pirep->depicao,
					'dep_lat=0',
					'dep_long=0',
					'arr_icao='.$pirep->arricao,
					'arr_lat=0',
					'arr_long=0',
					'flight_level='.$pirep->flightlevel,
					'route='.$update->route,
					'pax_first=0',
					'pax_business=0',
					'pax_economy=0',
					'pax_total='.$pirep->pax,
					'cargo='.$pirep->cargo,
					'time_out=0',
					'time_off=0',
					'time_on=0',
					'time_in=0',
					'hours_dawn='.$pirep->time_dawn,
					'hours_day='.$pirep->time_day,
					'hours_dusk='.$pirep->time_dusk,
					'hours_night='.$pirep->time_night,
					'time_total='.$pirep->flighttime,
					'distance=',
					'status=',
					'landing_rate='.$pirep->landing,
					'fuel_out='.$pirep->fuelstart,
					'fuel_off=',
					'fuel_toc=',
					'fuel_tod=',
					'fuel_on=',
					'fuel_in=',
					'fuel_used='.$pirep->fuelused,
					'comments='.$pirep->comments,
					'afk_elapsed='.$pirep->afk_elapsed,
					'afk_attempts='.$pirep->afk_attempts,
					'log='.$pirep->log,
					'online='.$pirep->online,
					'event='.$pirep->event,
					'checkride='.$pirep->checkride,
					'ac_model='.$pirep->ac_model,
					'ac_title='.$pirep->ac_title,
					'date='.$pirep->submitdate,
			);
			
			$message = implode($this->_field_separator, $fields);

			// Dispatch to appropriate handler
			$this->dispatch($message, $this->_acars_processor_path.'file_pirep');
			log_message('debug', 'PIREP Returning to client');
			$this->_params['pirepStatus'] = 1;
			
			// Won't be able to return the actual PIREP ID due to asynch comms
			$this->_params['pirepID'] = time();
		}
		else 
		{
			$this->_params['pirepStatus'] = 0;
			$this->_params['message'] = 'No PIREP data to file.';
		}
	}
	
	protected function parseRoute($xml)
	{
		
	}
	
	protected function chatConnect($xml)
	{
		
	}
	
	protected function chatRetrieve($xml)
	{
		
	}
	
	protected function chatSend($xml)
	{
		
	}
	
	protected function previousRoutes($xml)
	{
		
	}
	
	protected function getPax($xml)
	{
		
	}
	
	protected function getCargo($xml)
	{
	
	}
}