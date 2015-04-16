<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends PVA_Model
{
    /* schedule properties */

    public $carrier_id	    = NULL;
    public $operator_id	    = NULL;
    public $flight_num	    = NULL;
    public $dep_airport_id  = NULL;
    public $arr_airport_id  = NULL;
    public $airframe_id	    = NULL;
    public $schedule_cat_id = NULL;
    public $service_classes = NULL;
    public $regional	    = NULL;
    public $brand	    = NULL;
    public $dep_time_local  = NULL;
    public $dep_time_utc    = NULL;
    public $dep_terminal    = NULL;
    public $block_time	    = NULL;
    public $arr_time_local  = NULL;
    public $arr_time_utc    = NULL;
    public $arr_terminal    = NULL;
    public $version	    = NULL;
    public $sun		    = NULL;
    public $mon		    = NULL;
    public $tue		    = NULL;
    public $wed		    = NULL;
    public $thu		    = NULL;
    public $fri		    = NULL;
    public $sat		    = NULL;
    public $created	    = NULL;
    public $modified	    = NULL;

    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
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
	    foreach ($scheds as $pending)
	    {
		// Create New Schedule;
		$schedule = new Schedule();

		// Retrieve Carrier Airline
		$carrier = new Airline(array('fs' => $pending->carrier));
		$schedule->carrier_id = $carrier->id;

		// Retrieve Operator Airline - if not NULL
		if (!is_null($pending->operator))
		{
		    $operator = new Airline(array('fs' => $pending->operator));
		    $schedule->operator_id = $operator->id;
		    $schedule->regional = TRUE;
		}
		else
		{
		    // If not a regional flight than set the carrier id as the operator id.
		    $schedule->operator_id = $carrier->id;
		    $schedule->regional = FALSE;
		}

		// Populate flight number
		$schedule->flight_num = $pending->flight_num;

		// Retrieve Departure Airport
		$dep_airport = new Airport(array('fs' => $pending->dep_airport));
		$schedule->dep_airport_id = $dep_airport->id;

		// Retrieve Arrival Airport
		$arr_airport = new Airport(array('fs' => $pending->arr_airport));
		$schedule->arr_airport_id = $arr_airport->id;

		// Retrieve Airframe
		$airframe = new Airframe(array('iata' => $pending->equip));
		$schedule->airframe_id = $airframe->id;

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
			OR is_null($schedule->airframe_id)
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

}

class Schedules_categories extends PVA_Model
{

    public $value	= NULL;
    public $description	= NULL;

    function __construct($id = NULL)
    {
	$this->_table_name = 'schedules_categories';
	parent::__construct($id);
    }

}
