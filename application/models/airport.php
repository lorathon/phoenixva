<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airport extends PVA_Model {
	
	/* Airport properties */
	public $icao            = '';
	public $iata            = '';
	public $name            = '';
	public $city            = '';
	public $state_code      = '';
	public $country         = '';
	public $utc_offset      = 0;
	public $lat             = 0;
	public $long            = 0;
	public $elevation       = 0;
	public $classification  = 0;
	public $type            = '';
	public $active          = 0;
	public $delay_index_url = '';
	public $weather_url     = '';
	public $hub             = 0;
	
	function __construct()
	{
		parent::__construct();
		
		// Set default order
		$this->_order_by = 'icao asc';
	}
	
	/**
	 * Finds all airports that are set as hubs.
	 * 
	 * This is intended to be called on an empty Airport object:
	 * $airport = new Airport();
	 * $hubs = $airport->find_hubs();
	 * 
	 * @return array|bool Array of airport objects for each hub or FALSE on failure.
	 */
	function find_hubs()
	{
		$this->db->select()
		         ->from($this->_table_name)
		         ->where('hub',1);
		// Query the database
		$query = $this->db->get();
		 
		return parent::_get_objects($query);
	} 
	
	/**
	 * Determines if the airport is a hub.
	 * 
	 * @return boolean TRUE if the airport is a hub.
	 */
	function is_hub()
	{
		return ($this->hub == 1);
	}
}