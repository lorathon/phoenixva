<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft extends PVA_Model
{    
    public $equip		= NULL;
    public $aircraft_sub_id	= NULL;
    public $name		= NULL;
    public $category		= NULL;
    public $pax_first		= NULL;
    public $pax_business	= NULL;
    public $pax_economy		= NULL;
    public $max_cargo		= NULL;
    public $max_range		= NULL;
    public $oew			= NULL;
    public $mzfw		= NULL;
    public $mlw			= NULL;
    public $mtow		= NULL;    
    public $carrier_count	= NULL;
    public $operator_count	= NULL;
    public $flight_count	= NULL;
    public $total_pireps	= NULL;
    public $total_hours		= NULL;
    public $total_distance	= NULL;
    
    protected $_aircraft_sub	= NULL;
    
    private $_schedules_table = 'schedules';
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
    }
        
    /**
     * Retrieve aircraft data from schedules.
     * Build the aircraft table.  Ebnsure that
     * an aircraft does not already exist with
     * the same equip.  If it does not exist
     * be sure to set the default data.
     */
    public function create_equip()
    {	
	$this->db->select('equip')
		->select('COUNT(DISTINCT `carrier`) as carrier_count')
		->select('COUNT(DISTINCT `operator`) as operator_count')
		->select('COUNT(`id`) as flight_count')
		->from($this->_schedules_table)
		->group_by('equip');
	
	$query = $this->db->get();
	
	$rows = $this->_get_objects($query);
	
	if($rows)
	{
	    foreach($rows as $row)
	    {
		$aircraft = new Aircraft();
		$aircraft->equip = $row->equip;
		$aircraft->find();
		
		// If aircraft not found then set default data
		if(is_null($aircraft->id))
		{
		    $aircraft->name	    = '';
		    $aircraft->pax_first    = 0;
		    $aircraft->pax_business = 0;
		    $aircraft->pax_economy  = 0;
		    $aircraft->max_cargo    = 0;
		    $aircraft->max_range    = 0;
		    $aircraft->oew	    = 0;
		    $aircraft->mzfw	    = 0;
		    $aircraft->mlw	    = 0;
		    $aircraft->mtow	    = 0;
		    $aircraft->total_pireps = 0;
		    $aircraft->total_hours  = 0;
		    $aircraft->total_distance = 0;
		}
		
		// Find CAT based on equip
		// check for a LIKE %equip% in
		// sub table 
		$subs = new Aircraft_sub();
		$subs->equips = $aircraft->equip;
		$sub = $subs->find_all(TRUE);
		
		// If sub row is not found set defaults
		if(! $sub)
		{
		    $aircraft->aircraft_sub_id	= 0;
		    $aircraft->category		= 0;
		}
		// If sub is found set id and cat from sub
		else
		{
		    $aircraft->aircraft_sub_id  = $sub[0]->id;
		    $aircraft->category		= $sub[0]->category;
		}
		
		// Set data gathered from schedule scan.
		$aircraft->carrier_count = $row->carrier_count;
		$aircraft->flight_count = $row->flight_count;
		$aircraft->operator_count = $row->operator_count;
		
		$aircraft->save();
	    }
	}
    
    }
}

