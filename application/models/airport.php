<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Airport extends PVA_Model
{
    /* Airport properties */

    public $fs = NULL;
    public $iata = NULL;
    public $icao = NULL;
    public $name = NULL;
    public $city = NULL;
    public $state_code = NULL;
    public $country_code = NULL;
    public $country_name = NULL;
    public $region_name = NULL;
    public $utc_offset = NULL;
    public $lat = NULL;
    public $long = NULL;
    public $elevation = NULL;
    public $classification = NULL;
    public $active = NULL;
    public $port_type = NULL;
    public $hub = NULL;
    public $delay_url = NULL;
    public $weather_url = NULL;
    public $version = NULL;

    function __construct($id = NULL)
    {
	parent::__construct($id);

	// Set default order
	$this->_order_by = 'icao asc';
	log_message('debug', 'Airport model Initialized');
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
	$this->hub = 1;
	return $this->find_all();
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

    /**
     * Returns the full name of the airport with ICAO
     * 
     * @return string
     */
    function get_full_name()
    {
	return $this->icao . ' - ' . $this->name;
    }

    /**
     * Gets ALL airports and constructs an array
     * to be used in a dropdown form item
     * [airport_id]airport_name
     * 
     * @param Airport Status boolean $active
     * @return array 
     */
    function get_dropdown()
    {
	$this->_limit = NULL;
	$this->_order_by = 'name ASC';
	$this->active = TRUE;

	$rows = $this->find_all();

	$data = array();
	$data[0] = '-- NONE --';
	foreach ($rows as $row)
	{
	    $data[$row->id] = $row->name;
	}
	return $data;
    }

    function get_autocomplete($name = NULL, $iata = NULL, $icao = NULL)
    {
	if(! is_null($name))
	    $this->name = $name;
	
	if(! is_null($iata))
	    $this->iata = $iata;
	
	if(! is_null($icao))
	    $this->icao = $icao;
		
	$airports = $this->find_all(TRUE);
	if ($airports > 0)
	{
	    foreach ($airports as $row)
	    {
		if(! is_null($name))
		{
		    $new_row['label'] = htmlentities(stripslashes($row->name));
		    $new_row['value'] = htmlentities(stripslashes($row->name));
		}
		if(! is_null($iata))
		{
		    $new_row['label'] = htmlentities(stripslashes($row->iata));
		    $new_row['value'] = htmlentities(stripslashes($row->iata));
		}
		if(! is_null($icao))
		{
		    $new_row['label'] = htmlentities(stripslashes($row->icao));
		    $new_row['value'] = htmlentities(stripslashes($row->icao));
		}
		
		$new_row['id'] = $row->id;
		$new_row['name'] = $row->name;
		$new_row['icao'] = $row->icao;
		$new_row['iata'] = $row->iata;
		$row_set[] = $new_row; //build an array
	    }
	    $this->output->enable_profiler(FALSE);
	    echo json_encode($row_set); //format the array into json data
	}
    }

}
