<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airline extends PVA_Model {
	
	/* Airline properties */
	public $fs		= NULL;
	public $iata		= NULL;
	public $icao		= NULL;
	public $name		= NULL;
	public $active		= NULL;
	public $category	= NULL;
	public $fuel_discount	= NULL;
	public $airline_image	= NULL;
	public $total_schedules	= NULL;
	public $total_pireps	= NULL;
	public $total_hours	= NULL;
	public $regional	= NULL;
        public $version         = NULL;
	
	// Airline Category
	protected $_cat		= NULL;
	
	// Array of Aircraft
	protected $_fleet	= NULL;
	
	// Airline Aircraft Object
	protected $_aircraft	= NULL;

	// Array of Airlines
	protected $_airlines	= NULL;
	
	// Array of Airports
	protected $_destinations    = NULL;
	
	// Array of all Airline Categories
	protected $_airline_categories	= NULL;	
	
	private $_schedule_table	= 'schedules_pending';
	
	function __construct($id = NULL)
	{
	    parent::__construct($id);
	}
	
	function get_category()
	{
	    if(is_null($this->_cat))
	    {
		$this->_cat = new Airline_category();
		$this->_cat->value = $this->category;
		$this->_cat->find();
	    }
	    return $this->_cat;
	}
	
	function get_categories()
	{
	    if(is_null($this->_airline_categories))
	    {
		$cat = new Airline_category();
		$this->_airline_categories = $cat->find_all();
	    }
	    return $this->_airline_categories;
	}
	
	function get_dropdown()
        {
	    $this->_limit = NULL;
	    $this->_order_by = 'name ASC';
	    
            $rows = $this->find_all();
            
            $data = array();
	    $data[0] = '-- NONE --';
            foreach($rows as $row)
            {
                $data[$row->id] = $row->name;
            }      
            return $data;
        }
	
	function get_category_dropdown()
        {
	    $cat = new Airline_category();
	    $rows = $cat->find_all();
            
            $data = array();
            foreach($rows as $row)
            {
                $data[$row->value] = $row->description;
            }      
            return $data;
        }
	
	function get_fleet()
	{
	    if(is_null($this->_fleet))
	    {
		$fleet = new Airline_aircraft();
		$fleet->airline_id = $this->id;
		$this->_fleet = $fleet->find_all();
	    }
	    return $this->_fleet;
	}
	
	function get_fleet_airlines($airframe_id = NULL)
	{
	    if(is_null($this->_airlines))
	    {
		$this->_airlines = array();
		$fleet = new Airline_aircraft();
		$fleet->airframe_id = $airframe_id;
		$aircraft = $fleet->find_all();		
		
		if($aircraft)
		{
		    foreach($aircraft as $obj)
		    {
			$this->_airlines[] = new Airline($obj->airline_id);
		    }
		}
	    }
	    return $this->_airlines;
	}	
	
	function get_aircraft($aircraft_id)
	{
	    if(is_null($this->_aircraft))
	    {
		$this->_aircraft = new Airline_aircraft($aircraft_id);		
	    }
	    return $this->_aircraft;
	}
	
	function get_destinations()
	{
	    if(is_null($this->_destinations))
	    {
		$airport = new Airline_airport();
		$airport->airline_id = $this->id;
		$this->_destinations = $airport->get_destinations();
	    }
	    return $this->_destinations;
	}
	
	function set_regional()
	{	    
	    $airline = new Airline();
	    $airlines = $airline->find_all();
	    
	    foreach($airlines as $airline)
	    {
		// Check for schedules with airline as operator
		$sched1 = new Schedules_pending();
		$sched1->operator = $airline->fs;		
		$count1 = $sched1->find_all(FALSE, TRUE);
		
		// if schedules as operator exist set regional flag
		if($count1 > 0)
		    $airline->regional = TRUE;		    
		
		// Check for schedules with airline as carrier
		$sched2 = new Schedules_pending();
		$sched2->carrier = $airline->fs;
		$count2 = $sched2->find_all(FALSE, TRUE);
		
		// If schedules as carrier exists reset regional flag
		if($count2 > 0)
		    $airline->regional = FALSE;
		
		// Set total schedules
		$airline->total_schedules = $count1 + $count2;
		
		// If total schedules = 0 then deactivate airline
		if($count1 + $count2 == 0)
		    $airline->active = FALSE;
		
		// Save airline
		$airline->save();		
	    }
	}
	
	function build_airline_destinations()
	{
	    $airport = new Airline_airport();
	    $airport->build_destinations($this);
	}
	
	function build_entire_destinations()
	{
	    $this->_limit = NULL;
	    $airlines = $this->find_all();
	    
	    if(! $airlines)
		return;
	    
	    foreach($airlines as $airline)
	    {
		$ac = new Airline_airport();
		$ac->build_destinations($airline);
	    }
	}
	
	// XXX temporary to create fleet
	function build_airline_fleet()
	{	    
	    $this->db->select('equip')
		    ->select('COUNT(`id`) as count')
		    ->from($this->_schedule_table)
		    ->where('operator', $this->fs)		    
		    ->group_by('equip');
	
	    $query = $this->db->get();
	    
	    foreach ($query->result() as $row)
	    {
		$aircraft = new Airline_aircraft();
		$aircraft->airline_id = $this->id;
		
		$airframe = new Airframe();
		$airframe->iata = $row->equip;
		$airframe->find();
		
		// Enable Airframe
		$airframe->enable_airframe();
		$aircraft->airframe_id = $airframe->id;
		
		// Check to see if the airframe exists
		$aircraft->find();
		
		if(is_null($aircraft->id))
		{
		    // Set standard data from airframe
		    $aircraft->pax_first = $airframe->pax_first;
		    $aircraft->pax_business = $airframe->pax_business;
		    $aircraft->pax_economy = $airframe->pax_economy;
		    $aircraft->payload = $airframe->payload;
		    
		}
		
		$aircraft->total_schedules = $row->count;		
		$aircraft->save();		
	    }
	}
}

class Airline_category extends PVA_Model {
    
    public $value	    = NULL;
    public $description = NULL;
    public $passenger   = NULL;
    public $cargo	    = NULL;

    function __construct($id = NULL)
    {	    
	parent::__construct($id);
	$this->_table_name = 'airlines_categories';
    }
	
}

class Airline_aircraft extends PVA_Model {
    
    public $airline_id	    = NULL;
    public $airframe_id	    = NULL;
    public $pax_first	    = NULL;
    public $pax_business    = NULL;
    public $pax_economy	    = NULL;
    public $payload	    = NULL;
    public $total_schedules = NULL;
    public $total_flights   = NULL;
    public $total_hours	    = NULL;
    public $total_fuel	    = NULL;
    public $total_landings  = NULL;
    
    protected $_airframe    = NULL;
    
    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
    } 
    
    function get_airframe()
    {
	if(is_null($this->_airframe))
	{
	    $this->_airframe = new Airframe($this->airframe_id);	    
	}
	return $this->_airframe;
    }    
}

class Airline_airport extends PVA_Model {
    
    public $airline_id	    = NULL;
    public $airport_id	    = NULL;
    
    protected $_schedules_table	= 'schedules';
    protected $_airports_table	= 'airports';
    
    protected $_destinations   = NULL;
    
    function __construct($id = NULL)
    {
	parent::__construct($id);
    }
    
    // XXX should be a better way to do this.  
    function build_destinations($airline)
    {
	if(is_null($airline->id))
	    return;
	
	$this->db->select('airports.id as id')
		->from($this->_airports_table)
		->join($this->_schedules_table, 'schedules.dep_airport = airports.fs')
		->where('schedules.carrier', $airline->fs)
		->or_where('schedules.operator', $airline->fs)
		->group_by('airports.id');
	
	$query = $this->db->get();

	foreach ($query->result() as $row)
	{
	    $ac = new Airline_airport();
	    $ac->airport_id = $row->id;
	    $ac->airline_id = $airline->id;
	    $ac->find();
	    $ac->save();
	}
	
	$this->db->select('airports.id as id')
		->from($this->_airports_table)
		->join($this->_schedules_table, 'schedules.arr_airport = airports.fs')
		->where('schedules.carrier', $airline->fs)
		->or_where('schedules.operator', $airline->fs)
		->group_by('airports.id');
	
	$query = $this->db->get();

	foreach ($query->result() as $row)
	{
	    $ac = new Airline_airport();
	    $ac->airport_id = $row->id;
	    $ac->airline_id = $airline->id;
	    $ac->find();
	    $ac->save();
	}
    }
    
    function get_destinations()
    {	
	if(is_null($this->_destinations))
	{	
	    $this->_destinations = array();
	    if($destinations = $this->find_all())
	    {
		foreach($destinations as $airport)
		{
		    $this->_destinations[] = new Airport($airport->airport_id);
		}
	    }	    
	}	
	return $this->_destinations;
    }
}