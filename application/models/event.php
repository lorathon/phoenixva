<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Event model
 * 
 * Provides all business logic for adding events to items in the system. 
 * 
 * @author Jeff
 *
 */
class Event extends PVA_Model
{

    public $event_type_id	= NULL;
    public $name		= NULL;
    public $description		= NULL;
    public $time_start		= NULL;
    public $time_end		= NULL;
    public $waiver_js		= 0;
    public $waiver_cat		= 0;
    public $airline_id		= 0;
    public $airport_id		= 0;
    public $aircraft_cat_id	= 0;
    public $landing_rate	= 0;
    public $flight_time		= 0;
    public $total_flights	= 0;
    public $bonus_1		= 0;
    public $bonus_2		= 0;
    public $bonus_3		= 0;
    public $award_id_winner	= 0;
    public $award_id_participant	= 0;
    public $enabled		= 0;
    public $completed		= 0;
    public $user_id_1		= 0;
    public $user_id_2		= 0;
    public $user_id_3		= 0;
    public $created		= NULL;
    public $modified		= NULL;
    
    protected $_event_type	= NULL;
    
    protected $_airline		= NULL;
    protected $_airport		= NULL;
    
    protected $_award_winner	    = NULL;
    protected $_award_participant   = NULL;
    
    protected $_user_1	= NULL;
    protected $_user_2	= NULL;
    protected $_user_3	= NULL;
	    
    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
	
	$this->_event_type  = new Event_type();
	
	$this->_airline	    = new Airline();
	$this->_airport	    = new Airport();
	
	$this->_award_winner	    = new Award();
	$this->_award_participant   = new Award();
	
	$this->_user_1	    = new User();
	$this->_user_2	    = new User();
	$this->_user_3	    = new User();
    }
    
    function get_event_type()
    {
	if ( is_null($this->_event_type) )
	{
	    $this->_event_type = new Event_type($this->event_type_id);
	}
	return $this->_event_type;
    }
    
    function get_airline()
    {
	if ( is_null($this->_airline) )
	{
	    $this->airline = new Airline($this->airline_id);
	}
	return $this->_airline;
    }
    
    function get_airport()
    {
	if ( is_null($this->_airport) )
	{
	    $this->_airport = new Airport($this->airport_id);
	}
	return $this->_airport;
    }
    
    function get_aircraft_list()
    {
	//Retrieve aircraft list based on aircraft_cat_id
    }
    
    function get_award_winner()
    {
	if ( is_null($this->_award_winner) )
	{
	    $this->_award_winner = new Award($this->award_id_winner);
	}
	return $this->_award_winner;
    }
    
    function get_award_participant()
    {
	if ( is_null($this->_award_participant) )
	{
	    $this->_award_winner = new Award($this->award_id_participant);
	}
	return $this->_award_participant;
    }
    
    function get_first_place()
    {
	if ( is_null($this->_user_1) )
	{
	    $this->_user_1 = new User($this->user_id_1);	    
	}
	return $this->_user_1;
    }
    
    function get_first_second()
    {
	if ( is_null($this->_user_2) )
	{
	    $this->_user_2 = new User($this->user_id_2);	    
	}
	return $this->_user_2;
    }
    
    function get_first_third()
    {
	if ( is_null($this->_user_3) )
	{
	    $this->_user_3 = new User($this->user_id_3);	    
	}
	return $this->_user_3;
    }
    
    function enable_event()
    {
	if ( is_null($this->id) )
	    return FALSE;
	
	$this->enabled = 1;
	$this->save();
    }
    
    function disable_event()
    {
	if ( is_null($this->id) )
	    return FALSE;
	
	$this->enabled = 0;
	$this->save();
    }
    
    function complete_event()
    {
	if ( is_null($this->id) )
	    return FALSE;
	
	$this->completed = 1;
	$this->save();
    }
    
    function reset_event()
    {
	if ( is_null($this->id) )
	    return FALSE;
	
	$this->comleted = 0;
	$this->user_id_1 = NULL;
	$this->user_id_2 = NULL;
	$this->user_id_3 = NULL;
	$this->save();
    }

}
