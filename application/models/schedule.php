<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends PVA_Model
{
    /* Schedule properties */

    public $carrier_id = NULL;
    public $operator_id = NULL;
    public $flight_num = NULL;
    public $dep_airport_id = NULL;
    public $arr_airport_id = NULL;
    public $aircraft_sub_id = NULL;
    public $schedule_cat_id = NULL;
    public $service_classes = NULL;
    public $regional = NULL;
    public $brand = NULL;
    public $dep_time_local = NULL;
    public $dep_time_utc = NULL;
    public $dep_terminal = NULL;
    public $block_time = NULL;
    public $arr_time_local = NULL;
    public $arr_time_utc = NULL;
    public $arr_terminal = NULL;
    public $version = NULL;
    public $sun = NULL;
    public $mon = NULL;
    public $tue = NULL;
    public $wed = NULL;
    public $thu = NULL;
    public $fri = NULL;
    public $sat = NULL;
    public $created = NULL;
    public $modified = NULL;
    
    protected $_flightnumber = NULL;
    
    /* Airline Object*/
    protected $_airline = NULL;    
    
    /* Airport Object */
    protected $_departure = NULL;
    protected $_arrival = NULL;
    
    /* Airframe Object */
    protected $_airframe = NULL;
    
    /* Bids Table */
    protected $_bids_table = 'bids';
    protected $_bids_sort = 'sort asc';
	    

    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
    }
    
    function get_flightnumber()
    {
	if(is_null($this->_flightnumber))
	{
	    $airline = new Airline($this->operator_id);
	    $this->_flightnumber = $airline->fs.$this->flight_num;
	}
	return $this->_flightnumber;
    }
    
    function get_airline()
    {
	if(is_null($this->_airline))
	{
	    $this->_airline = new Airline($this->carrier_id);
	}
	return $this->_airline;
    }
    
    function get_airport($arrival = FALSE)
    {
	if($arrival)
	{
	    $this->_arrival = new Airport($this->arr_airport_id);
	    return $this->_arrival;		
	}
	else
	{
	    $this->_departure = new Airport($this->dep_airport_id);
	    return $this->_departure;	
	}
    }
    
    function get_airframe()
    {
	$this->_airframe = new Airframe($this->aircraft_sub_id);
	return $this->_airframe;
    }
    

    /**
     * Activates $limit number of Schedules_pending 
     * 
     * Order of Operation
     * 1. Gets $limit number of NOT consumed Schedules_pending
     * 2. Loops though found Schedules_pending
     * 3. Checks linked tables for matching rows 
     * (Airline, Airport, Airline_aiport, Airline_aircraft, Airline_category)
     * 4. Creates new Schedule based on Schedules_pending and found Objects
     * 5. Sets Schedules_pending Object consumed to TRUE 
     * 
     * @param int $limit Number of Schedules_pending to activate
     * @return int $count Number of Schedules_pending successfully activated
     * @throws Exception Data from a linked tables was NOT found
     */
    function activate($limit = 25)
    {
	// Counter to return for total activated
	$count = 0;

	// Retrieve limit # of pending schedules that have NOT been consumed
	$sched = new Schedules_pending();
	$sched->consumed = FALSE;
	$sched->_limit = $limit;
	$scheds = $sched->find_all();

	// If pending schedules have been found
	if ($scheds)
	{
	    foreach ($scheds as $pending)
	    {
		// Create New Schedule;
		$schedule = new Schedule();

		// Retrieve Carrier Airline
		$carrier = new Airline(array('fs' => $pending->carrier));
		$schedule->carrier_id = $carrier->id;

		// Retrieve Aircraft Sub		
		$sub = new Aircraft_sub();
		$aircraft_sub = $sub->find_sub($pending->equip);
		$schedule->aircraft_sub_id = $aircraft_sub->id;

		// Check for Carrier Airline aircrafts
		$carrier->check_aircraft($aircraft_sub->id);

		// Retrieve Departure Airport
		$dep_airport = new Airport(array('fs' => $pending->dep_airport));
		$schedule->dep_airport_id = $dep_airport->id;

		// Retrieve Arrival Airport
		$arr_airport = new Airport(array('fs' => $pending->arr_airport));
		$schedule->arr_airport_id = $arr_airport->id;

		// Check for Carrier Airline airports
		$carrier->check_destination($dep_airport->id);
		$carrier->check_destination($arr_airport->id);

		// Retrieve Operator Airline - if not NULL
		if (!is_null($pending->operator))
		{
		    $operator = new Airline(array('fs' => $pending->operator));
		    $schedule->operator_id = $operator->id;
		    $schedule->regional = TRUE;

		    // Check for Operator Airline aiports
		    $operator->check_destination($dep_airport->id);
		    $operator->check_destination($dep_airport->id);

		    // Check for Carrier Airline airframe
		    $operator->check_aircraft($airframe->id);
		}
		else
		{
		    // If not a regional flight than set the carrier id as the operator id.
		    $schedule->operator_id = $carrier->id;
		    $schedule->regional = FALSE;
		}

		// Populate flight number
		$schedule->flight_num = $pending->flight_num;

		// Set version
		$schedule->version = $pending->version;

		// Retrieve Schedule Category
		$category = new Schedules_categories(array('value' => $pending->service_type));
		$schedule->schedule_cat_id = $category->id;

		// Check for NULL ids.  If exist then throw execption
		if (is_null($schedule->carrier_id)
			OR is_null($schedule->dep_airport_id)
			OR is_null($schedule->dep_airport_id)
			OR is_null($schedule->arr_airport_id)
			OR is_null($schedule->aircraft_sub_id)
			OR is_null($schedule->schedule_cat_id)
		)
		{
		    throw new Exception('Required linked table(s) not populated');
		}

		// Check for duplicate Schedule
		$schedule->find();

		$schedule->service_classes = $pending->service_classes;
		$schedule->brand = $pending->brand;
		$schedule->dep_time_local = $pending->dep_time_local;
		$schedule->dep_time_utc = $pending->dep_time_utc;
		$schedule->dep_terminal = $pending->dep_terminal;
		$schedule->block_time = $pending->block_time;
		$schedule->arr_time_local = $pending->arr_time_local;
		$schedule->arr_time_utc = $pending->arr_time_utc;
		$schedule->arr_terminal = $pending->arr_terminal;

		// Check for day of the week.  Only set those days that are TRUE(1)
		if ($pending->sun)
		    $schedule->sun = TRUE;
		if ($pending->mon)
		    $schedule->mon = TRUE;
		if ($pending->tue)
		    $schedule->tue = TRUE;
		if ($pending->wed)
		    $schedule->wed = TRUE;
		if ($pending->thu)
		    $schedule->thu = TRUE;
		if ($pending->fri)
		    $schedule->fri = TRUE;
		if ($pending->sat)
		    $schedule->sat = TRUE;

		// Save new schedule
		$schedule->save();

		// Set pending schedule to consumed
		$pending->consumed = TRUE;
		$pending->save();

		// Increase counter by 1
		$count++;
	    }
	}
	// Return total number of schedules activated
	return $count;
    }    
    
    function get_bids($user_id = NULL, $all = FALSE)
    {
	if(is_null($user_id))
	    return FALSE;
	
	$schedule = new Schedule();
	$schedule->_table_name = $this->_bids_table;
	$schedule->_order_by = $this->_bids_sort;	
	
	if($all)
	    $schedule->_limit = $schedule->find_all(FALSE, TRUE);
	else
	    $schedule->_limit = 1;
	
	return $schedule->find_all();
    }
    
    /**
     * Create a new bid.  
     * Retrieves schedule based on $schedule_id
     * Adds new bid using $user_id
     * 
     * Normal Usage
     * $bid = new Schedule($schedule_id);
     * $schedule->create_bid($user_id);
     * 
     * @param int $user_id
     * @return boolean
     */
    function create_bid($user_id = NULL)
    {
	$this->_table_name = $this->_bids_table;
	
	if(is_null($user_id))
	    return FALSE;
	
	$this->id = NULL;
	
	$this->user_id = $user_id;
	$this->sort = self::get_bid_sort($user_id);
	
	$this->save();
    }
    
    /**
     * Retrieve the next sort number 
     * used for adding a new bid
     * 
     * @param int $user_id
     * @return int
     */
    function get_bid_sort($user_id = NULL)
    {	
	if(is_null($user_id))
	    return FALSE;	
	
	$bid = new Schedule();
	$bid->_table_name = $this->_bids_table;
	$bid->user_id = $user_id;
	
	$bid->sort_bids($user_id);
	
	$bid->id = NULL;
	
	return $bid->find_all(FALSE, TRUE);	
    }
    
    /**
     * Sorts all current bids to
     * remove any numerical spaces
     * in sort order.
     * 
     * @param int $user_id
     */
    function sort_bids($user_id = NULL)
    {
	
	if(is_null($user_id))
	    return;
	
	$schedule = new Schedule();	
	$schedule->_table_name = $this->_bids_table;
	$schedule->_order_by = $this->_bids_sort;	
	$schedule->user_id = $user_id;
	$schedule->_limit = $schedule->find_all(FALSE, TRUE);
	
	$bids = $schedule->find_all();
	
	$i = 0;
	
	if($bids)
	{
	    foreach($bids as $bid)
	    {
		$bid->_table_name = $this->_bids_table;
		$bid->sort = $i;
		$bid->save();
		$i++;
	    }
	}
    }
    
    /**
     * Reorder a users bids
     * Expects an array of bids
     * Bids should be in order of new sort
     * 
     * @param array $bids Array of Bids
     */
    function reorder_bids($bids = NULL)
    {
	if(is_null($bids))
	    return;
	
	$i = 0;
	foreach($bids as $bid)
	{
	    $bid->_table_name = $this->_bids_table;
	    $bid->sort = $i;
	    $bid->save();
	    $i++;
	}
    }
    
    function delete_bid($bid_id = NULL)
    {
	if(is_null($bid_id))
	    return FALSE;
	    
	$schedule = new Schedule();
	$schedule->_table_name = $this->_bids_table;
	$schedule->id = $bid_id;
	$schedule->delete();	
    }

}

class Schedules_categories extends PVA_Model
{

    public $value = NULL;
    public $description = NULL;

    function __construct($id = NULL)
    {
	$this->_table_name = 'schedules_categories';
	parent::__construct($id);
    }

}
