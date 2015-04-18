<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends PVA_Model {
	
	public $user_id = Null;
	public $ip_address = Null;
	public $pirep_id = Null;
	public $load = Null;
	public $lat = Null;
	public $long = Null;
	public $altitude = Null;
	public $altitude_agl = Null;
	public $altitude_msl = Null;
	public $heading = Null;
	public $ground_speed = Null;
	public $true_airspeed = Null;
	public $indicated_airspeed = Null;
	public $vertical_speed = Null;
	public $bank = Null;
	public $pitch = Null;
	public $fuel_onboard = Null;
	public $phase = Null;
	public $warning = Null;
	public $warning_detail = Null;
	public $remain_dist = Null;
	public $remain_time = Null;
	public $flown_time = Null;
	public $landed = Null;
	public $ontime = Null;
	public $created = Null;
	
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Set defaults
		$this->_order_by = 'created desc';
		$this->_timestamps = TRUE;
		log_message('debug', 'Positions model Initialized');
	}
	
	/**
	 * Finds either the departure or arrival position
	 * 
	 * This actually finds the first or last position related to the provided
	 * PIREP. If the pilot repositions after starting ACARS or before filing
	 * a PIREP, an innaccurate position could be returned.
	 * 
	 * @param integer $pirep_id
	 * @param boolean $departure set to FALSE to get the arrival position
	 * @return Position|boolean a Position model if found or FALSE if not.
	 */
	public static function find_position($pirep_id, $departure = TRUE)
	{
		$position = new Position();
		$position->pirep_id = $pirep_id;
		if ($departure)
		{
			$position->_order_by = 'created asc';
		}
		else 
		{
			$position->_order_by = 'created desc';
		}
		return $position->find();		
	}	
}