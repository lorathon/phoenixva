<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hub_stat model
 * 
 * Provides all business logic for working with hub statistics in the system.
 * 
 * @author Chuck
 *
 */
class Hub_stat extends PVA_Model {
	
	/* Default properties */
	public $airport_id = NULL;
	public $total_pay = NULL;
	public $airlines_flown = NULL;
	public $aircraft_flown = NULL;
	public $airports_landed = NULL;
	public $fuel_used = NULL;
	public $fuel_cost = NULL;
	public $total_landings = NULL;
	public $landing_softest = NULL;
	public $landing_hardest = NULL;
	public $total_gross = NULL;
	public $total_expenses = NULL;
	public $flights_early = NULL;
	public $flights_ontime = NULL;
	public $flights_late = NULL;
	public $flights_manual = NULL;
	public $flights_rejected = NULL;
	public $hours_flights = NULL;
	public $pilots_assigned = NULL;
	public $pilots_flying = NULL;
	public $created = NULL;
	public $modified = NULL;
	
	public function __construct($id = NULL)
	{
		$this->_order_by = 'created desc';
		$this->_timestamps = TRUE;
		parent::__construct($id);
	}
	
	/**
	 * Updates the number of pilots assigned to the populated hub.
	 * 
	 * @throws Exception if the stat id or airport id is not populated.
	 */
	public function update_pilots_assigned()
	{
		if (is_null($this->id) && is_null($this->airport_id))
		{
			throw new Exception('ID or Airport required before updating Hub_stat');
		}
		
		if (is_null($this->airport_id))
		{
			$this->find();
		}
		
		log_message('debug', 'Updating number of pilots assigned to airport ID: '.$this->airport_id);
		
		$user = new User();
		$user->hub = $this->airport_id;
		$user->activated = 1;
		$this->pilots_assigned = $user->find_all(FALSE, TRUE);

		$this->save();
	}
	
	/**
	 * Save the stats
	 * 
	 * Overrides the base save to determine if the stats should create a new
	 * record or update the existing record.
	 * 
	 * (non-PHPdoc)
	 * @see PVA_Model::save()
	 */
	public function save()
	{
		log_message('debug', 'Saving hub stats.');
		if (is_null($this->id) && is_null($this->airport_id))
		{
			throw new Exception('ID or Airport required before saving Hub_stat');
		}
		
		// Figure out if we need to create a new record or update existing
		if ($this->id)
		{
			$comp = new Hub_stat($this->id);
		}
		else 
		{
			$comp = new Hub_stat();
			$comp->airport_id = $this->airport_id;
			$comp->find();
		}
		
		if (is_null($comp->id))
		{
			log_message('debug', 'No prior records for hub, creating new record');
			// No prior records for this airport, so create new.
			parent::save();
			return;
		}
		
		// Check the months
		$this_month = date('Y-m');
		$comp_month = date('Y-m', strtotime($comp->created));
		log_message('debug', 'This month: '.$this_month);
		log_message('debug', 'Comp month: '.$comp_month);
		
		if ($this_month == $comp_month)
		{
			log_message('debug', 'Updating existing record.');
			// Same month update
			$this->id = $comp->id;
			parent::save();
		}
		else 
		{
			log_message('debug', 'Creating new record.');
			// New month, create new
			$this->id = NULL;
			parent::save();
		}
	}
}
