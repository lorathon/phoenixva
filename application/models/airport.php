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
    public $autocomplete = NULL;
    
    /* Array of Airline Objects */
    protected $_airlines = NULL;

    function __construct($id = NULL)
    {
	$this->_order_by = 'icao asc';	
	parent::__construct($id);	
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
    
    function get_airlines()
    {
	if(is_null($this->_airlines))
	{
	    $airline = new Airline();
	    $this->_airlines = $airline->get_destination_airlines($this->id);
	}
	return $this->_airlines;
    }
    
    /**
     * Find ALL.
     * Can be removed during final deployment
     * XXX
     * 
     * @return array of Airport Objects
     */
    function get_all_airports()
    {
	$this->_limit = NULL;
	return $this->find_all();
    }

    /**
     * Search Airport tables autocomplete 
     * column using %LIKE%
     * 
     * @param string $search
     * @return json JSON search results
     */
    function get_autocomplete($search = NULL)
    {
	if(is_null($search))
	    echo json_encode($row_set);
	
	$this->autocomplete = $search;		
	$this->active = TRUE;
	
	$airports = $this->find_all(TRUE);
	if ($airports > 0)
	{
	    foreach ($airports as $row)
	    {		
		$new_row['label'] = htmlentities(stripslashes($row->autocomplete));
		$new_row['value'] = htmlentities(stripslashes($row->autocomplete));		
		$new_row['id'] = $row->id;
		$row_set[] = $new_row; //build an array
	    }
	    $this->output->enable_profiler(FALSE);
	    return json_encode($row_set); //format the array into json data
	}
    }
    
    /**
     * Create autocomplete string
     * and save to table.
     * Used for autocomplete searches
     */
    function create_autocomplete()
    {
	if(is_null($this->id))
	    return;
	
	if(! is_null($this->icao))
	    $code = '( ' . $this->iata . ' | ' . $this->icao . ' )';
	else
	    $code = '( ' . $this->iata . ' )';
	
	if(! is_null($this->state_code))
	    $state = $this->state_code . ', ';
	else
	    $state = NULL;
	
	$auto = "{$code} {$this->name}, {$this->city}, {$state}{$this->country_code}";
	
	$this->autocomplete = $auto;
	$this->save();	
    }
    
    function datatable()
    {
        $this->datatables->select('id,fs,iata,icao,name,city,state_code,country_name,utc_offset,elevation,classification,active,port_type')->from('airports');
        echo $this->datatables->generate();
    }
    
    function get_datatable()
    {
        return parent::get_datatable('id,fs,iata,icao,name,city,state_code,country_name,utc_offset,elevation,classification,active,port_type');
    }

}
