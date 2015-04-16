<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pirep extends PVA_Model {
	
	// PIREP properties
	public $user_id = NULL;
	public $hub_id = NULL;
	public $airline_aircraft_id = NULL;
	public $client = NULL;
	public $flight_number = NULL;
	public $flight_type = NULL;
	public $dep_icao = NULL;
	public $dep_lat = NULL;
	public $dep_long = NULL;
	public $arr_icao = NULL;
	public $arr_lat = NULL;
	public $arr_long = NULL;
	public $flight_level = NULL;
	public $route = NULL;
	public $pax_first = NULL;
	public $pax_business = NULL;
	public $pax_economy = NULL;
	public $cargo = NULL;
	public $time_out = NULL;
	public $time_off = NULL;
	public $time_on = NULL;
	public $time_in = NULL;
	public $hours_dawn = NULL;
	public $hours_day = NULL;
	public $hours_dusk = NULL;
	public $hours_night = NULL;
	public $distance = NULL;
	public $status = NULL;
	public $landing_rate = NULL;
	public $fuel_out = NULL;
	public $fuel_off = NULL;
	public $fuel_toc = NULL;
	public $fuel_tod = NULL;
	public $fuel_on = NULL;
	public $fuel_in = NULL;
	public $fuel_used = NULL;
	public $afk_elapsed = NULL;
	public $afk_attempts = NULL;
	public $online = NULL;
	public $event = NULL;
	public $checkride = NULL;
	public $ac_model = NULL;
	public $ac_title = NULL;
	
	// Calculated fields
	protected $_price_first = NULL;
	protected $_price_business = NULL;
	protected $_price_economy = NULL;
	protected $_price_cargo = NULL;
	protected $_gross_income = NULL;
	protected $_fuel_price = NULL;
	protected $_fuel_cost = NULL;
	protected $_pilot_pay_rate = NULL;
	protected $_pilot_pay_total = NULL;
	protected $_expenses = NULL;
	
	// Constants
	const OPEN = 0;
	const APPROVED = 1;
	const REJECTED = 2;
	const HOLDING = 3;
	
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Set defaults
		$this->_order_by = 'created desc';
		$this->_timestamps = TRUE;
		log_message('debug', 'Pirep model Initialized');
	}
	
	/**
	 * Opens a new PIREP
	 * 
	 * For those ACARS clients that support opening a PIREP with position reports
	 * or something, this method will create a stub PIREP record to get a PIREP
	 * ID that can be used for subsequent position reports.
	 * 
	 * @return Pirep
	 */
	public function open()
	{
		if (is_null($this->user_id))
		{
			throw new Exception('User ID must be populated to open a PIREP');
		}
		
		// Delete any open PIREPs and positions. A user can only have one PIREP open at a time.
		$pirep = new Pirep();
		$pirep->user_id = $this->user_id;
		$pirep->status = self::OPEN;
		$open_list = $pirep->find_all();
		
		if ($open_list)
		{
			foreach ($open_list as $open_pirep)
			{
				// Delete positions
				$pos_search = new Position();
				$pos_search->pirep_id = $open_pirep->id;
				$position_list = $pos_search->find_all();
				if ($position_list)
				{
					foreach ($position_list as $position)
					{
						$position->delete();
					}
				}
				
				// Delete PIREP
				$open_pirep->delete();
			}
		}
		
		// Set the status for an open PIREP
		$this->status = self::OPEN;
		$this->save();
		
		$this->set_note('PIREP '.$this->id.' opened.', $this->user_id);
		
		return $this;
	}
	
	/**
	 * Files a PIREP
	 * 
	 * This function will do all of the calculations and database updates when
	 * a PIREP is filed.
	 * @return Pirep
	 */
	public function file()
	{		
		if (is_null($this->id))
		{
			$this->id = $this->find_open()->id;
		}
		
		// Calculate finances
		$user = new User($this->user_id);
		$this->_pilot_pay_rate = $user->get_pay_rate();
		$this->_pilot_pay_total = $this->_pilot_pay_rate * $this->hours_total();
		
		// Update PIREP
		$this->save();
		log_message('debug', 'PIREP '.$this->id.' filed.');
		
		$user->process_pirep($this);
		
		// Update hub
		
		// Update Airline
		
		// Update Airframe
		
		// Update Airline_airframe
		
		// Update Airport
		
		if ($this->event)
		{
			// Update event
		}
		
		// Update Passengers
		
		// Update Cargo
		
		return $this;
	}
	
	// Override base save() so only certain fields can be modified.
	public function save()
	{
		
	}
	
	/**
	 * Finds open PIREP for the user.
	 * 
	 * Usage Note: This function returns a PIREP. It does not populate the current
	 * PIREP model.
	 * 
	 * @return Pirep
	 */
	public function find_open()
	{
		// Return populated PIREP if this PIREP was already opened.
		$pirep = new Pirep(array(
				'user_id' => $this->user_id,
				'status' => self::OPEN,
		));
		return $pirep;
	}
	
	/**
	 * The total hours flown for this PIREP
	 * 
	 * @return number
	 */
	public function hours_total()
	{
		return array_sum(array(
				$this->hours_dawn,
				$this->hours_day,
				$this->hours_dusk,
				$this->hours_night,
		));
	}	
}
