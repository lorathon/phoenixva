<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Airline extends PVA_Model
{
    
    public $fs = NULL;
    public $iata = NULL;
    public $icao = NULL;
    public $name = NULL;
    public $active = NULL;
    public $category_id = NULL;
    public $fuel_discount = NULL;
    public $airline_image = NULL;
    public $total_schedules = NULL;
    public $total_pireps = NULL;
    public $total_hours = NULL;
    public $regional = NULL;
    public $version = NULL;
    public $autocomplete = NULL;
    
    // Airline_category Object
    protected $_cat = NULL;
    
    // Array of Aircraft Object
    protected $_fleet = NULL;
    
    // Airline_aircraft Object
    protected $_aircraft = NULL;
    
    // Array of Airline Object
    protected $_airlines = NULL;
    
    // Array of Airport Object
    protected $_destinations = NULL;
    
    // Array of ALL Airline_category Object
    protected $_airline_categories = NULL;

    function __construct($id = NULL)
    {
        parent::__construct($id);
    }

    /**
     * Gets the Airline category with this airline object
     * 
     * Airline id must be populated separately. Normal usage would be:
     * $airline = new Airline($airline_id);
     * $airline_category = $airline->get_category();     
     * 
     * OR
     * 
     * The following is used to get the airline category when only
     * the value is known.  This is useful when retrieveing new
     * airlines.
     * $airline = new Airline();
     * $airline_category = $airline->get_category($catergoy); 
     * 
     * @return object Airline_category
     */
    function get_category($value = NULL)
    {
        if (!is_null($value))
        {
            $this->_cat = new Airline_category(array('value' => $value));
        }
        elseif (is_null($this->_cat))
        {
            $this->_cat = new Airline_category($this->category_id);
        }
        return $this->_cat;
    }

    /**
     * Gets an array of ALL airline_categories
     * 
     * Normal usage would be:
     * $airline = new Airline();
     * $categories = $airline->get_categories();
     * 
     * @return array of objects Airline_category
     */
    function get_categories()
    {
        if (is_null($this->_airline_categories))
        {
            $cat = new Airline_category();
            $this->_airline_categories = $cat->find_all();
        }
        return $this->_airline_categories;
    }

    /**
     * Gets ALL airlines and constructs an array
     * to be used in a dropdown form item
     * [airline_id]airline_name
     * 
     * @param Airline Status boolean $active
     * @return array 
     */
    function get_dropdown($active = TRUE)
    {
        $this->_limit = NULL;
        $this->_order_by = 'name ASC';
	$this->active = $active;

        $rows = $this->find_all();

        $data = array();
        $data[0] = '-- NONE --';
        foreach ($rows as $row)
        {
            $data[$row->id] = $row->name;
        }
        return $data;
    }

    /**
     * Gets ALL airline_categories and constructs an array
     * to be used in a dropdown form item
     * [category_value]category_description
     * 
     * @return array 
     */
    function get_category_dropdown()
    {
        $cat = new Airline_category();
        $rows = $cat->find_all();

        $data = array();
        foreach ($rows as $row)
        {
            $data[$row->id] = $row->description;
        }
        return $data;
    }

    /**
     * Gets an array of All Airline_aircraft for this Airline
     * 
     * Airline id must be populated separately. Normal usage would be:
     * $airline = new Airline($airline_id);
     * $fleet = $airline->get_fleet();
     * 
     * @return array of objects Airline_aircraft
     */
    function get_fleet()
    {
        if (is_null($this->_fleet))
        {
            $fleet = new Airline_aircraft();
            $fleet->airline_id = $this->id;
            $this->_fleet = $fleet->find_all();
        }
        return $this->_fleet;
    }

    /**
     * Gets an array of Airline objects based on Airframe_id
     * 
     * To be used to retrieve an array of airlines
     * using the same $airframe_id     
     * 
     * @param ID of Airframe Object $airframe_id 
     * @return array of objects Airline
     */
    function get_fleet_airlines($airframe_id = NULL)
    {
        if (is_null($this->_airlines))
        {
            $this->_airlines = array();
            $fleet = new Airline_aircraft();
            $fleet->airframe_id = $airframe_id;
            $aircraft = $fleet->find_all();

            if ($aircraft)
            {
                foreach ($aircraft as $obj)
                {
                    $this->_airlines[] = new Airline($obj->airline_id);
                }
            }
        }
        return $this->_airlines;
    }

    /**
     * Check Airline_aircraft table for aircraft
     * with $aircraft_sub_id and Airline_id
     * 
     * $airline = new Airline($airline_id);
     * $airline->check_aircraft($aircraft_sub_id);
     * 
     * @param ID of Aircraft_sub Object $aircraft_sub_id
     * @return none
     */
    function check_aircraft($aircraft_sub_id)
    {
        if (is_null($this->id) OR is_null($aircraft_sub_id))
            return;

        $aircraft = new Airline_aircraft(array('airline_id' => $this->id));
        $aircraft->check_aircraft($aircraft_sub_id);
    }

    /**
     * Gets the Airline_aircraft based on $aircraft_id
     * 
     * @param ID of Airline_aircraft Object $aircraft_id
     * @return object Airline_aircraft
     */
    function get_aircraft($aircraft_id)
    {
        if (is_null($this->_aircraft))
        {
            $this->_aircraft = new Airline_aircraft($aircraft_id);
        }
        return $this->_aircraft;
    }

    /**
     * Check Airline_airport table for a airport
     * based on Airline_id and Airport_id
     * 
     * $airline = new Airline($airline_id);
     * $airline->check_destination($airport_id);
     * 
     * @param ID of Airport Object $airport_id 
     * @return none
     */
    function check_destination($airport_id)
    {
        if (is_null($this->id) OR is_null($airport_id))
            return;

        new Airline_airport(array('airline_id' => $this->id, 'airport_id' => $airport_id), TRUE);
    }

    /**
     * Gets an array of Airports flown to/from by
     * $this Airline Object
     * 
     * Airline Object must be populated separately. Normal uasge would be:
     * $airline = new Airline($airline_id);
     * $airports = $airline->get_destinations();
     * 
     * @return array Array of Airport
     */
    function get_destinations()
    {
        if (is_null($this->_destinations))
        {
            $airline_airport = new Airline_airport();
	    $airline_airport->airline_id = $this->id;
            $this->_destinations = $airline_airport->get_destinations();
        }
        return $this->_destinations;
    }
    
    function get_destination_airlines($airport_id = NULL)
    {
	$airport = new Airline_airport();
	$airport->airport_id = $airport_id;
	return $airport->get_airlines();
    }
    
    /**
     * Find ALL.
     * Can be removed during final deployment
     * XXX
     * 
     * @return array of Airline Objects
     */
    function get_all_airlines()
    {
	$this->_limit = NULL;
	return $this->find_all();
    }
    
    /**
     * Search Airline tables autocomplete 
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
	
	$airlines = $this->find_all(TRUE);
	if ($airlines > 0)
	{
	    foreach ($airlines as $row)
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
	
	if(! is_null($this->icao) && ! is_null($this->iata))
	    $code = '( ' . $this->iata . ' | ' . $this->icao . ' )';
	elseif(is_null($this->icao))
	    $code = '( ' . $this->iata . ' )';
	else
	    $code = '( ' . $this->icao . ' )';
		
	$auto = "{$code} {$this->name}";
	
	$this->autocomplete = $auto;
	$this->save();	
    }

}

class Airline_category extends PVA_Model
{

    public $value = NULL;
    public $description = NULL;
    public $passenger = NULL;
    public $cargo = NULL;

    function __construct($id = NULL)
    {
        $this->_table_name = 'airlines_categories';
        parent::__construct($id);
    }

}

class Airline_aircraft extends PVA_Model
{

    public $airline_id = NULL;
    public $airframe_id = NULL;
    public $pax_first = NULL;
    public $pax_business = NULL;
    public $pax_economy = NULL;
    public $payload = NULL;
    public $total_schedules = NULL;
    public $total_flights = NULL;
    public $total_hours = NULL;
    public $total_fuel = NULL;
    public $total_landings = NULL;
    
    // Airframe Object
    protected $_airframe = NULL;

    function __construct($id = NULL, $create = FALSE)
    {
        $this->_timestamps = TRUE;
        parent::__construct($id);
        if (is_null($this->id) && $create)
        {
            $this->save();
        }
    }

    /**
     * Check Airline_aircraft table for all aircraft
     * that match the equips of the aircraft_sub row.
     * Equips is exploded and then looped to check for
     * Airline_aircraft.  If missing a new Airline_aircrfaft
     * is created using Airframe and Airline data     
     * 
     * Should Only called by Airline Object
     * 
     * @param ID of Aircfrat_sub $aircraft_sub_id
     * @return none
     */
    function check_aircraft($aircraft_sub_id = NULL)
    {
        if (is_null($aircraft_sub_id))
            return;

        $aircraft_sub = new Aircraft_sub($aircraft_sub_id);
        $equips = explode(' ', $aircraft_sub->equips);

        if ($equips)
        {
            foreach ($equips as $equip)
            {
                $airframe = new Airframe(array('iata' => $equip));
                $aircraft = new Airline_aircraft(array('airline_id' => $this->airline_id, 'airframe_id' => $airframe->id));

                if (!is_null($aircraft->id))
                    continue;

                $aircraft->pax_first = $airframe->pax_first;
                $aircraft->pax_business = $airframe->pax_business;
                $aircraft->pax_economy = $airframe->pax_economy;
                $aircraft->payload = $airframe->payload;
                $aircraft->save();
            }
        }
    }

    /**
     * Gets Airframe object of $this     * 
     * 
     * @return object Airframe
     */
    function get_airframe()
    {
        if (is_null($this->_airframe))
        {
            $this->_airframe = new Airframe($this->airframe_id);
        }
        return $this->_airframe;
    }

}

class Airline_airport extends PVA_Model
{

    public $airline_id = NULL;
    public $airport_id = NULL;
    
    // Array of Airport Object
    protected $_destinations = NULL;
    
    protected $_airlines = NULL;

    function __construct($id = NULL, $create = FALSE)
    {
        parent::__construct($id);
        if (is_null($this->id) && $create)
        {
            $this->save();
        }
    }

    /**
     * Gets array of Airport based on Airline_id
     * Should Only called by Airline.
     * 
     * @return array Airport Objects
     */
    function get_destinations()
    {	
        if (is_null($this->_destinations))
        {
            $this->_destinations = array();
            if ($destinations = $this->find_all())
            {
                foreach ($destinations as $airport)
                {
                    $this->_destinations[] = new Airport($airport->airport_id);
                }
            }
        }
        return $this->_destinations;
    }
    
    /**
     * Gets array of Airline based on Airport_id
     * Should Only called by Airline.
     * 
     * @return array Airline Objects
     */
    function get_airlines()
    {	
        if (is_null($this->_airlines))
        {
            $this->_airlines = array();
            if ($airlines = $this->find_all())
            {
                foreach ($airlines as $airline)
                {
                    $this->_airlines[] = new Airline($airline->airline_id);
                }
            }
        }
        return $this->_airlines;
    }

}
