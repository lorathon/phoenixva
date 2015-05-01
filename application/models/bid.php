<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bid extends PVA_Model
{
    /* Bid properties */
    public $user_id = NULL;
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
    public $sort = NULL;   
    public $created = NULL;
    public $modified = NULL;    
    
    
    function __construct($id = NULL)
    {
	$this->_order_by = 'sort asc';	
	$this->_timestamps = TRUE;
	parent::__construct($id);	
    }
    
    
    /**
     * Create a new bid.  
     * Retrieves schedule based on $schedule_id
     * Adds new bid using $this->user_id
     * 
     * Normal Usage
     * $bid = new Bid();
     * $bid->user_id = $id
     * $bid->create_bid($schedule_id);
     * 
     * @param int $schedule_id
     * @return boolean
     */
    function create_bid($schedule_id = NULL)
    {
	if(is_null($schedule_id))
	    return;
	
	$this->user_id = 2;
	
	if(is_null($this->user_id))
	    return;
	
	$schedule = new Schedule($schedule_id);
	
	if(! $schedule->flight_num)
	    return FALSE;
	
	$this->carrier_id = $schedule->carrier_id;
	$this->operator_id = $schedule->operator_id;
	$this->flight_num = $schedule->flight_num;
	$this->dep_airport_id = $schedule->dep_airport_id;
	$this->arr_airport_id = $schedule->arr_airport_id;
	$this->aircraft_sub_id = $schedule->aircraft_sub_id;
	$this->schedule_cat_id = $schedule->schedule_cat_id;
	$this->service_classes = $schedule->service_classes;
	$this->regional = $schedule->regional;
	$this->brand = $schedule->brand;
	$this->dep_time_local = $schedule->dep_time_local;
	$this->dep_time_utc = $schedule->dep_time_utc;
	$this->dep_terminal = $schedule->dep_terminal;
	$this->block_time = $schedule->block_time;
	$this->arr_time_local = $schedule->arr_time_local;
	$this->arr_time_utc = $schedule->arr_time_utc;
	$this->arr_terminal = $schedule->arr_terminal;
	$this->version = $schedule->version;
	$this->sun = $schedule->sun;
	$this->mon = $schedule->mon;
	$this->tue = $schedule->tue;
	$this->wed = $schedule->wed;
	$this->thu = $schedule->thu;
	$this->fri = $schedule->fri;
	$this->sat = $schedule->sat;
	$this->sort = self::find_next_sort($this->user_id);
	
	$this->save();
	return TRUE;
    }
    
    /**
     * Retrieve the next sort number 
     * used for adding a new bid
     * 
     * @param int $user_id
     * @return int
     */
    function find_next_sort($user_id = NULL)
    {
	if(is_null($user_id))
	    return;
	
	$bid = new Bid();
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
	
	$bid = new Bid();
	$bid->user_id = $user_id;
	
	$bids = $bid->find_all();
	
	$i = 0;
	
	if($bids)
	{
	    foreach($bids as $bid)
	    {
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
	    $bid->sort = $i;
	    $bid->save();
	    $i++;
	}
    }
}
