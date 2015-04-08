<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft_sub extends PVA_Model
{    
    public $designation		= NULL;
    public $manufacturer	= NULL;
    public $equips		= NULL;
    public $hours_needed	= NULL;
    public $category		= NULL;
    public $rated		= NULL;
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
    }    
    
    public function save()
    {
	parent::save();
	
	/***
	 * If this is an update then check airframes
	 * and update them.  Just incase the category has changed
	 */
	if(!is_null($this->id))
	{
	    $airframe = new Airframe();
	    $airframe->aircraft_sub_id = $this->id;
	    $airframe->_limit = $airframe->find_all(FALSE, TRUE);
	    $airframes = $airframe->find_all();
	    
	    if(!$airframes)
		return;
	    
	    foreach($airframes as $obj)
	    {
		$obj->category = $this->category;
		$obj->save();
	    }
	    
	}
    }
}

