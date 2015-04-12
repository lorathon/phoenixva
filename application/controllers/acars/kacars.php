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
	public function index()
	{
		$input = file_get_contents('php://input');
		$xml = simplexml_load_string($input);
		
		// Switch data contains the function to run
		$function = $xml->switch->data;
		
		if (function_exists($this->$function))
		{
			$params = $this->$function($xml);
		}
				
		// Respond to the ACARS client
		$this->sendXML($params, $function);
	}
	
	protected function login($xml)
	{		
		$params = array();
		
		$user = new User();
		$user->id = $xml->data->pilotID;
		$user->password = $xml->data->password;
		$user->last_ip = $_SERVER['REMOTE_ADDR'];
		if ($user->login())
		{
			$params['loginStatus'] = 1;
			$params['pilotcode'] = $user->id;
			
			$parts = explode(' ', $user->name, 2);
			$firstname = $parts[0];
			$lastname = '';
			if (isset($parts[1]))
			{
				$lastname = $parts[1];
			}
			$params['firstname'] = $firstname;
			$params['lastname'] = $lastname;

			$params['hub'] = $user->hub;
			$params['currlocation'] = $user->get_user_stats()->current_location;
			$params['totalflights'] = $user->get_user_stats()->total_flights();
			$params['totalhours'] = $user->get_user_stats()->total_hours();
			$params['totalpay'] = $user->get_user_stats()->total_pay;
					
			$rank = new Rank($user->rank_id);
			$params['rank'] = $rank->rank;
			$params['short'] = $rank->short;
			$params['rankimage'] = $rank->rank_image;
			$params['acars_ver'] = 'TEST';
			$params['acars_msg'] = 'TEST';
			$params['acars_afk'] = '';
			$params['acars_paxcap'] = '';
			$params['acars_cargocap'] = '';
			$params['ftp_address'] = '';
			$params['ftp_user'] = '';
			$params['ftp_pw'] = '';
			$params['chat_address'] = '';
			$params['chat_port'] = '';
			$params['ss_time'] = '';
			
			($user->is_manager()) ? $staff = 1 : $staff = 0;
			$params['staff'] = $staff;
		}
		else 
		{
			$params['loginStatus'] = 0;
			$params['message'] = 'Unable to log in with that user and password.';
		}
		
		return $params;
	}

	protected function getBid($xml)
	{
		
	}
	
	protected function getFlight($xml)
	{
		
	}
	
	protected function liveUpdate($xml)
	{
		
	}
	
	protected function pirep($xml)
	{
		$params = array();
		
		if ($xml->pirep)
		{
			$message  = 'user_id='.$xml->pirep->pilotID;
			$message .= 'flight_num='.$xml->pirep->flightnumber;
			$message .= 'flight_level='.$xml->pirep->flightlevel;
			$message .= 'dep_airport='.$xml->pirep->depicao;
			$message .= 'arr_airport='.$xml->pirep->arricao;
			$message .= 'time_total='.$xml->pirep->flighttime;
			$message .= 'flight_type='.$xml->pirep->flighttype;
			$message .= 'time_dawn='.$xml->pirep->time_dawn;
			$message .= 'time_day='.$xml->pirep->time_day;
			$message .= 'time_dusk='.$xml->pirep->time_dusk;
			$message .= 'time_night='.$xml->pirep->time_night;
			$message .= 'fuel='.$xml->pirep->fuelused;
			$message .= 'fuel_start='.$xml->pirep->fuelstart;
			$message .= 'pax='.$xml->pirep->pax;
			$message .= 'cargo='.$xml->pirep->cargo;
			$message .= 'landing='.$xml->pirep->landing;
			$message .= 'comments='.$xml->pirep->comments;
			$message .= 'log='.$xml->pirep->log;
			$message .= 'online='.$xml->pirep->online;
			$message .= 'event='.$xml->pirep->event;
			$message .= 'afk_elapsed='.$xml->pirep->afk_elapsed;
			$message .= 'afk_attempts='.$xml->pirep->afk_attempts;
			$message .= 'checkride='.$xml->pirep->checkride;
			$message .= 'ac_model='.$xml->pirep->ac_model;
			$message .= 'ac_title='.$xml->pirep->ac_title;
			$message .= 'date='.$xml->pirep->submitdate;

			// Dispatch to appropriate handler
			$this->dispatch($message, '/cjtop/acars/pirep_processor');
			log_message('debug', 'PIREP Returning to client');
			$params['pirepStatus'] = 1;
			$params['pirepID'] = time();
		}
		else 
		{
			$params['pirepStatus'] = 0;
			$params['message'] = 'No PIREP data to file.';
		}
		
		return $params;
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