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
    public $waiver_js		= NULL;
    public $waiver_cat		= NULL;
    public $airline_id		= NULL;
    public $airport_id		= NULL;
    public $aircraft_cat_id	= NULL;
    public $landing_rate	= NULL;
    public $flight_time		= NULL;
    public $total_flights	= NULL;
    public $bonus_1		= NULL;
    public $bonus_2		= NULL;
    public $bonus_3		= NULL;
    public $award_id_winner	= NULL;
    public $award_id_participant	= NULL;
    public $enabled		= NULL;
    public $completed		= NULL;
    public $user_id_1		= NULL;
    public $user_id_2		= NULL;
    public $user_id_3		= NULL;
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
    }
    
    function get_event_type()
    {
	if ( is_null($this->_event_type) )
	{
	    $this->_event_type = new Event_type($this->event_type_id);
	}
	return $this->_event_type;
    }
    
    function get_type_name()
    {
	if( is_null($this->_event_type) )
	{
	    $this->get_event_type();
	}
	return $this->_event_type->name;
    }
    
    function get_color_id()
    {
	if( is_null($this->_event_type) )
	{
	    $this->get_event_type();
	}
	return $this->_event_type->color_id;
    }
    
    function get_airline()
    {
	if ( is_null($this->_airline) )
	{
	    $this->_airline = new Airline($this->airline_id);
	}		
	return $this->_airline;	
    }
    
    function get_airline_name()
    {
	if ( is_null($this->_airline) )
	{
	    $this->get_airline();
	}
	return $this->_airline->name;
    }
    
    function get_airport()
    {
	if ( is_null($this->_airport) )
	{
	    $this->_airport = new Airport($this->airport_id);
	}
	return $this->_airport;
    }
    
    function get_airport_name()
    {
	if ( is_null($this->_airport) )
	{
	    $this->get_airport();
	}
	return $this->_airport->iata . ' - ' . $this->_airport->name;
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
