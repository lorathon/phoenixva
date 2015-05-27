<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'acars_base.php';
/**
 * ACARS controller for the kACARS client by Jeffrey Kobus (www.fs-products.net).
 * 
 * Updated to the new ACARS Communications Specification:
 * https://github.com/cjtop/phoenixva/wiki/ACARS-Communications-Specification
 *
 * @author Chuck Topinka
 * @author Jeffrey Kobus
 *
 */
class Kacars extends Acars_Base
{
	protected $xml;
	
	public function __construct()
	{
	    $this->_client = 'kACARS';
	    
	    $this->xml = simplexml_load_string(file_get_contents('php://input'));
	    
	    log_message('debug', 'Incoming kACARS Message:');
	    log_message('debug', print_r($this->xml, TRUE));
	    log_message('debug', '-----------------------------');
	    
	    if ($this->xml->authToken)
	    {
	        $this->_auth_token = $this->xml->authToken;
	    }
	     
	    parent::__construct();
	}
	
	/**
	 * Handle incoming request
	 * 
	 * KACARS and its derivitives use a front controller pattern. An XML document
	 * is submitted as input with the switch element set to indicate what function
	 * to execute.
	 */
	public function index()
	{		
		// Switch data contains the function to run
		$func = (string)$this->xml->switch;
		log_message('debug', 'Method: '.print_r($func, TRUE));
		
		if (method_exists($this, $func))
		{
			$this->$func($this->xml);
		}
		else 
		{
		    $this->sendError("Function {$func} does not exist");
		}				
	}
	
	/**
	 * Logs the ACARS client in
	 * 
	 * @param SimpleXMLElement $xml
	 */
	protected function login($xml)
	{		
	    $this->_user_id = $xml->data->pilotID;
	    $this->_password = $xml->data->password;
	    
	    if ( ! is_int($this->_user_id))
	    {
	    	// Probably used PVA#### instead of just ####
	    	$this->_user_id = substr($this->_user_id, 3);
	    }
	    
	    $user = parent::login();
	    
		if ($user)
		{
			log_message('debug', 'kACARS user logged in');
			// Login was successful, prepare result
			// XXX This needs to be updated based on https://github.com/cjtop/phoenixva/wiki/ACARS-Communications-Specification#login-1 
			$this->_params['authToken'] = $this->_auth_token;
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
			
			// Respond to the ACARS client
			$this->sendXML('login');
		}
		else 
		{
			// Login failed
			log_message('debug', 'kACARS user login failed');
			$this->sendError('Unable to log in with that user and password.');
		}
	}

	protected function getBid($xml)
	{
		if (! is_null($this->_user_id)) {
		    $bid = $this->get_bid();
		    
		    $this->_params['aircraftid'] = $bid->aircraft_sub_id;
		    $this->_params['carrierid'] = $bid->carrier_id;
		    $this->_params['operatorid'] = $bid->operator_id;
		    $this->_params['flightnumber'] = $bid->flight_num;
		    $this->_params['flighttype'] = 'unknown';
		    $this->_params['depairport'] = $bid->get_airport()->icao;
		    $this->_params['deptime'] = $bid->dep_time_utc;
		    $this->_params['arrairport'] = $bid->get_airport(TRUE)->icao;
		    $this->_params['arrtime'] = $bid->arr_time_utc;
		    $this->_params['paxfirst'] = $bid->get_airframe()->pax_first;
		    $this->_params['paxbusiness'] = $bid->get_airframe()->pax_business;
		    $this->_params['paxeconomy'] = $bid->get_airframe()->pax_economy;
		    $this->_params['cargo'] = $bid->get_airframe()->payload;
		    
		    $this->sendXML('bid');
		}
		else
		{
		    $this->sendError('Invalid authToken');
		}
	}
	
	protected function getFlight($xml)
	{
	    $this->sendError("The get flight function has not been implemented yet");
	}
	
	/**
	 * Processes a position update
	 * 
	 * Uses asynchronous communication
	 * 
	 * @param SimpleXMLElement $xml
	 */
	protected function liveupdate($xml)
	{
		if ($xml->data)
		{
			$update = $xml->data;
			
			$report = new stdClass();
			
			// Translate values
			$report->lat = $update->lat;
			$report->long = $update->lng;
			$report->altitude = $update->alt;
			$report->heading = $update->heading;
			$report->ground_speed = $update->gs;
			$report->true_airspeed = $update->tas;
			$report->indicated_airspeed = $update->ias;
			$report->vertical_speed = $update->vs;
			$report->bank = $update->bank;
			$report->pitch = $update->pitch;
			$report->fuel_onboard = $update->fob;
			$report->flown_time = $update->flown_time;
			$report->landed = $update->landed;

			$this->position_report($report);
			log_message('debug', 'Live Update returning to client');
			
			$this->_params['message'] = 'Update processed';
			$this->sendXML('liveupdate');
		}
		else
		{
			$this->sendError('No data provided');
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
		if ($xml->data)
		{
			$data = $xml->data;
			
			// Translate KACARS to PIREP fields
			$pirep = new Pirep();
			
			$pirep->client = $this->_client;
			$pirep->user_id = $this->_user_id;
			$pirep->airline_aircraft_id = $data->aircraftid;
			$pirep->carrier_id = $data->carrierid;
			$pirep->operator_id = $data->operatorid;
			$pirep->flight_number = $data->flightnumber;
			$pirep->flight_type = $data->flighttype;
			$pirep->dep_airport_id = $data->depairport;
			$pirep->arr_airport_id = $data->arrairport;
			$pirep->pax_first = $data->paxfirst;
			$pirep->pax_business = $data->paxbusiness;
			$pirep->pax_economy = $data->paxeconomy;
			$pirep->cargo = $data->cargo;
			$pirep->route = $data->route;
			$pirep->flight_level = $data->flightlevel;
			// XXX Starttime
			$pirep->hours_dawn = $data->time_dawn;
			$pirep->hours_day = $data->time_day;
			$pirep->hours_dusk = $data->time_dusk;
			$pirep->hours_night = $data->time_night;
			$pirep->hours_total = $data->flown_time;
			$pirep->fuel_out = $data->fuelstart;
			$pirep->fuel_off = $data->fuel->load_takeoff;
			$pirep->fuel_toc = $data->fuel->load_toc;
			$pirep->fuel_tod = $data->fuel->load_tod;
			$pirep->fuel_on = $data->fuel->load_landed;
			$pirep->fuel_in = $data->fuel->load_end;
			$pirep->fuel_used = $data->fuelused;
			$pirep->landing_rate = $data->landing;
			$pirep->online = $data->online;
			$pirep->event = $data->event;
			$pirep->checkride = $data->checkride;
			$pirep->afk_elapsed = $data->afk_elapsed;
			$pirep->afk_attempts = $data->afk_attempts;
			$pirep->ac_model = $data->ac_model;
			$pirep->ac_title = $data->ac_title;
			
			$this->file_pirep($pirep);			
			log_message('debug', 'PIREP Returning to client');
			
			// Respond to the ACARS client
			$this->_params['message'] = 'PIREP filed';
			$this->sendXML('pirep');
		}
		else 
		{
			$this->sendError('No PIREP data to file.');
		}
	}
	
	protected function parseRoute($xml)
	{
	    $this->sendError("The parse route function has not been implemented");
	}
	
	protected function chatConnect($xml)
	{
	    $this->sendError("Chat functions are not supported");
	}
	
	protected function chatRetrieve($xml)
	{
	    $this->sendError("Chat functions are not supported");
	}
	
	protected function chatSend($xml)
	{
	    $this->sendError("Chat functions are not supported");
	}
	
	protected function previousRoutes($xml)
	{
	    $this->sendError("The previous routes function has not been implemented");
	}
	
	protected function getPax($xml)
	{
	    $this->sendError("The getPax function should no longer be needed");
	}
	
	protected function getCargo($xml)
	{
	    $this->sendError("The getCargo function should no longer be needed");
	}
	
	protected function sendError($msg)
	{
	    $this->_params['message'] = $msg;
	    $this->sendXML('error');
	}
}