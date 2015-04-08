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
	
	protected $_cat		= NULL;
	
	// Array of Aircraft
	protected $_fleet	= NULL;
	
	// Array of Airports
	protected $_destinations    = NULL;
	
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
    public $max_cargo	    = NULL;
    public $total_schedules = NULL;
    public $total_flights   = NULL;
    public $total_hours	    = NULL;
    public $total_fuel	    = NULL;
    public $total_landings  = NULL;
    
    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
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