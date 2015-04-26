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

    public $event_type_id = NULL;
    public $name = NULL;
    public $description = NULL;
    public $time_start = NULL;
    public $time_end = NULL;
    public $waiver_js = NULL;
    public $waiver_cat = NULL;
    public $airline_id = NULL;
    public $airport_id = NULL;
    public $aircraft_cat_id = NULL;
    public $landing_rate = NULL;
    public $flight_time = NULL;
    public $total_flights = NULL;
    public $enabled = NULL;
    public $completed = NULL;
    public $created = NULL;
    public $modified = NULL;
    
    protected $_event_type = NULL;
    
    /* Airline Object */
    protected $_airline = NULL;
    
    /* Airport Object */
    protected $_airport = NULL;
    
    /* Count of Events */
    protected $_events_count = NULL;
    
    /* Array of Event_award Objects */
    protected $_event_awards = NULL;
    
    /* Array of Award Objects */
    protected $_awards = NULL;
    
    /* Array of Event_participant Objects */
    protected $_participants = NULL;
    
    /* Array of User Objects */
    protected $_users = NULL;

    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
    }

    function get_event_type()
    {
	if (is_null($this->_event_type))
	{
	    $this->_event_type = new Event_type($this->event_type_id);
	}
	return $this->_event_type;
    }

    function get_type_name()
    {
	if (is_null($this->_event_type))
	{
	    $this->get_event_type();
	}
	return $this->_event_type->name;
    }

    function get_color_id()
    {
	if (is_null($this->_event_type))
	{
	    $this->get_event_type();
	}
	return $this->_event_type->color_id;
    }

    function get_airline()
    {
	if (is_null($this->_airline))
	{
	    $this->_airline = new Airline($this->airline_id);
	}
	return $this->_airline;
    }

    function get_airline_name()
    {
	if (is_null($this->_airline))
	{
	    $this->get_airline();
	}
	return $this->_airline->name;
    }

    function get_airport()
    {
	if (is_null($this->_airport))
	{
	    $this->_airport = new Airport($this->airport_id);
	}
	return $this->_airport;
    }

    function get_airport_name()
    {
	if (is_null($this->_airport))
	{
	    $this->get_airport();
	}
	return $this->_airport->iata . ' - ' . $this->_airport->name;
    }

    function get_aircraft_list()
    {
	//Retrieve aircraft list based on aircraft_cat_id
    }

    function enable_event()
    {
	if (is_null($this->id))
	    return FALSE;

	$this->enabled = TRUE;
	$this->save();
    }

    function disable_event()
    {
	if (is_null($this->id))
	    return FALSE;

	$this->enabled = FALSE;
	$this->save();
    }

    function complete_event()
    {
	if (is_null($this->id))
	    return FALSE;

	$this->completed = TRUE;
	$this->save();
    }

    function reset_event()
    {
	if (is_null($this->id))
	    return FALSE;

	$this->comleted = FALSE;
	$this->user_id_1 = NULL;
	$this->user_id_2 = NULL;
	$this->user_id_3 = NULL;
	$this->save();
    }
    
    function get_event_awards()
    {
	if (is_null($this->id))
	    return FALSE;
	
	if (is_null($this->_event_awards))
	{
	    $event_award = new Event_award();
	    $event_award->event_id = $this->id();
	    $this->_event_awards = $event_award->find_all();	    
	}
	return $this->_event_awards;
    }
    
    function get_awards()
    {
	if (is_null($this->id))
	    return FALSE;
	
	if (is_null($this->_awards))
	{
	    $this->_awards = array();
	    
	    if(is_null($this->_event_awards))
		$this->get_event_awards ();
	    
	    if($this->_event_awards)
	    {
		foreach($this->_event_awards as $event_award)
		{
		    $this->_awards[] = new Award($event_award->award->id);
		}
	    }
	}
	return $this->_awards;
    }
    
    function add_award($award_id = NULL)
    {
	if(is_null($award_id) OR is_null($this->id))
	    return FALSE;
	
	$event_award = new Event_award(array('event_id' => $this->id, 'award_id' => $award_id));
	$event_award->save();
    }
    
    function remove_award($award_id = NULL)
    {
	if(is_null($award_id) OR is_null($this->id))
	    return FALSE;
	
	$event_award = new Event_award(array('event_id' => $this->id, 'award_id' => $award_id));
	$event_award->delete();
    }
    
    function get_participants()
    {
	if (is_null($this->id))
	    return FALSE;
	
	if (is_null($this->_participants))
	{
	    $event_participant = new Event_participant();
	    $event_participant->event_id = $this->id();
	    $this->_participants = $event_participants->find_all();	    
	}
	return $this->_participants;
    }
    
    function get_users()
    {
	if (is_null($this->id))
	    return FALSE;
	
	if (is_null($this->_users))
	{
	    $this->_users = array();
	    
	    if(is_null($this->_participants))
		$this->get_participants ();
	    
	    if($this->_participants)
	    {
		foreach($this->_participants as $event_participant)
		{
		    $this->_users[] = new User($event_participant->user_id);
		}
	    }
	}
	return $this->_users;
    }
    
    function add_participant($user_id = NULL)
    {
	if(is_null($user_id) OR is_null($this->id))
	    return FALSE;
	
	$participant = new Event_participant(array('event_id' => $this->id, 'user_id' => $user_id));
	$participant->save();
    }
    
    function remove_participant($user_id = NULL)
    {
	if(is_null($user_id) OR is_null($this->id))
	    return FALSE;
	
	$participant = new Event_participant(array('event_id' => $this->id, 'user_id' => $user_id));
	$participant->delete();
    }

    function get_events_count()
    {
	if (is_null($this->_events_count))
	{
	    $event = new Event();
	    $event->event_type_id = $this->event_type_id;
	    $events = $event->find_all();
	    $this->_events_count = ($events) ? count($events) : 0;
	}
	return $this->_events_count;
    }

}

class Event_award extends PVA_Model
{
    
    public $event_id = NULL;
    public $bonus_amount = NULL;
    public $award_id = NULL;
    public $position = NULL;
    
    function __construct($id = NULL)
    {
	parent::__construct($id);
    }
    
}

class Event_participant extends PVA_Model
{
    
    public $event_id = NULL;
    public $user_id = NULL;
    public $event_result = NULL;
    public $position = NULL;
    
    function __construct($id = NULL)
    {
	parent::__construct($id);
    }
    
}
