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
	
	protected $_cat		= NULL;
	
	// Array of Aircraft
	protected $_fleet	= NULL;
	
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
		$this->_fleet = $fleet->get_fleet();
	    }
	    return $this->_fleet;
	}
	
	function build_airline_fleet()
	{
	    $fleet = new Airline_aircraft();
	    $fleet->build_fleet($this);
	}
	
	function build_entire_fleet()
	{
	    $this->_limit = NULL;
	    $airlines = $this->find_all();
	    
	    if(! $airlines)
		return;
	    
	    foreach($airlines as $airline)
	    {
		$ac = new Airline_aircraft();
		$ac->build_fleet($airline);
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
    public $aircraft_id	    = NULL;
    public $total_schedules = NULL;
    public $total_flights   = NULL;
    public $total_hours	    = NULL;
    
    protected $_fleet	    = NULL;
    
    protected $_schedules_table	= 'schedules';
    protected $_aircrafts_table	= 'aircrafts';
    
    function __construct($id = NULL)
    {
	parent::__construct($id);
    }
    
    function build_fleet($airline)
    {			
	if(is_null($airline->id))
	    return;
	
	$this->db->select("aircrafts.id as id, COUNT({$this->db->dbprefix($this->_aircrafts_table)}.id) as total_schedules")
		->from($this->_aircrafts_table)
		->join($this->_schedules_table, 'schedules.equip = aircrafts.equip')
		->where('schedules.carrier', $airline->fs)
		->group_by('aircrafts.id');

	$query = $this->db->get();

	foreach ($query->result() as $row)
	{
	    $ac = new Airline_aircraft();
	    $ac->aircraft_id = $row->id;
	    $ac->airline_id = $airline->id;
	    
	    if(! $ac->find())
	    {		
		$ac->total_flights = 0;
		$ac->total_hours = 0;
	    }	    
	    $ac->total_schedules = $row->total_schedules;
	    $ac->save();
	}
    }
    
    function get_fleet()
    {
	if(is_null($this->_fleet))
	{
	    $fleet = $this->find_all();
	    
	    if(count($fleet) > 0)
	    {
		foreach($fleet as $ac)
		{
		    $aircraft = new Aircraft($ac->aircraft_id);
		    $this->_fleet[] = $aircraft;
		}
	    }	    
	}	
	return $this->_fleet;
    }
    
}