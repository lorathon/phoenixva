<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airframe extends PVA_Model
{    
    public $iata		= NULL;
    public $icao		= NULL;    
    public $name		= NULL;
    public $count               = NULL;
    public $aircraft_sub_id	= NULL;
    public $category		= NULL;
    public $regional		= NULL;
    public $turboprop		= NULL;
    public $jet			= NULL;
    public $widebody		= NULL;
    public $pax_first		= NULL;
    public $pax_business	= NULL;
    public $pax_economy		= NULL;
    public $max_cargo		= NULL;
    public $max_range		= NULL;
    public $oew			= NULL;
    public $mzfw		= NULL;    
    public $mtow		= NULL; 
    public $mlw			= NULL;
    
    public function __construct($id = NULL)
    {
	$this->_order_by = 'name ASC';
	$this->_timestamps = TRUE;
        parent::__construct($id);	
    }    
    
    public function find_all()
    {
	$this->_limit = NULL;
	return parent::find_all();
    }
    
    public function find_good()
    {
	$this->db->select('*')
		->where('count > 0')
		->from($this->_table_name)
		;
	
	$query = $this->db->get();
        return $this->_get_objects($query);
    }
    
    function check_sub()
    {
	$subs = new Aircraft_sub();
	$subs->equips = $this->iata;
	$sub = $subs->find_all(TRUE);
	
	if($sub)
	{
	    $this->aircraft_sub_id	= $sub[0]->id;
	    $this->category		= $sub[0]->category;	    
	}	    
    }
    
    function set_count()
    {
	$schedule = new Schedules_pending();
	$schedule->equip = $this->iata;
	$count = $schedule->find_all(FALSE, TRUE);
	
	$this->count = $count;
	$this->save();
    }
}

