<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends PVA_Model
{
    
    /* schedule properties */
    public $carrier_id		= NULL;
    public $operator_id		= NULL;
    public $flight_num		= NULL;
    public $dep_airport_id	= NULL;
    public $arr_airport_id	= NULL;
    public $airframe_id		= NULL;
    public $schedule_cat_id	= NULL;
    public $service_classes	= NULL;
    public $regional		= NULL;
    public $brand		= NULL;
    public $dep_time_local	= NULL;
    public $dep_time_utc	= NULL;
    public $dep_terminal	= NULL;
    public $block_time		= NULL;
    public $arr_time_local	= NULL;
    public $arr_time_utc	= NULL;
    public $arr_terminal	= NULL;
    public $version		= NULL;
    public $sun			= NULL;
    public $mon			= NULL;
    public $tue			= NULL;
    public $wed			= NULL;
    public $thu			= NULL;
    public $fri			= NULL;
    public $sat			= NULL;
    public $created		= NULL;
    public $modified		= NULL;

    function __construct()
    {
	$this->_timestamps = TRUE;
	parent::__construct();
    }

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
	    foreach ($scheds as $sch)
	    {
		// Create New Schedule;
		$schedule = new Schedule();

		// Retrieve Carrier Airline
		$carrier = new Airline(array('fs', $sch->carrier));
		if (! $carrier)
		{
		    // Carrier Airline NOT found
		    $carrier->save();
		}
		$schedule->carrier_id = $carrier->id;

		// Retrieve Operator Airline - if not NULL
		if (!is_null($sch->operator))
		{
		    $operator = new Airline(array('fs', $sch->operator));
		    if (! $operator)
		    {
			// Operator Airline NOT found
			$operator->save();
		    }
		    $schedule->operator_id = $operaor->id;
		}

		// Populate flight number
		$schedule->flight_num = $sch->flight_num;

		// Retrieve Departure Airport
		$dep_airport = new Airport(array('fa', $sch->dep_airport));
		if (! $dep_airport)
		{
		    // Departure Airport NOT found
		    $dep_airport->save();
		}
		$schedule->dep_airport_id = $dep_airport->id;

		// Retrieve Arrival Airport
		$arr_airport = new Airport(array('fs', $sch->arr_airport));
		if (! $arr_airport)
		{
		    // Arrival Airport NOT found
		    $arr_airport->save();
		}
		$schedule->arr_airport_id = $arr_airport->id;

		// Retrieve Airframe
		$airframe = new Airframe('iata', $sch->equip);
		if (! $airframe)
		{
		    // Airframe NOT found
		    $airframe->save();
		}
		$schedule->airframe_id = $airframe->id;

		// Set version
		$schedule->version = $sch->version;

		// Check for duplicate Schedule
		$schedule->find();

		// Populate remaining parameters
		// Retrieve Schedule Category
		$category = new Schedules_categories(array('value', $sch->service_type));
		if (! $category)
		{
		    // Category NOT found
		    $category->save();
		}
		$schedule->schedule_cat_id = $category->id;

		$schedule->service_classes = $sch->service_classes;
		$schedule->regional = $sch->regional;
		$schedule->brand = $sch->brand;
		$schedule->dep_time_local = $sch->dep_time_local;
		$schedule->dep_time_utc = $sch->dep_time_utc;
		$schedule->dep_terminal = $sch->dep_termianl;
		$schedule->block_time = $sch->block_time;
		$schedule->arr_time_local = $sch->arr_time_local;
		$schedule->arr_time_utc = $sch->arr_time_utc;
		$schedule->arr_terminal = $sch->arr_termianl;

		// Check for day of the week.  Only set those days that are TRUE(1)
		if ($sch->sun)
		    $schedule->sun = TRUE;
		if ($sch->mon)
		    $schedule->mon = TRUE;
		if ($sch->tue)
		    $schedule->tue = TRUE;
		if ($sch->wed)
		    $schedule->wed = TRUE;
		if ($sch->thu)
		    $schedule->thu = TRUE;
		if ($sch->fri)
		    $schedule->fri = TRUE;
		if ($sch->sat)
		    $schedule->sat = TRUE;

		// Save new schedule
		$schedule->save();

		// Set pending schedule to consumed
		$sch->consumed = TRUE;
		$sch->save();
		
		// Increase counter by 1
		$count++;
	    }
	}
	// Return total number of schedules activated
	return $count;
    }

}

class Schedules_categories extends PVA_Model
{

    public $value	= NULL;
    public $description	= NULL;

    function __construct()
    {
	$this->_table_name = 'schedules_categories';
	parent::__construct();
    }

}
