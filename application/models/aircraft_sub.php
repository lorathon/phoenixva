<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft_sub extends PVA_Model
{
    /* Aircraft_sub properties */

    public $designation = NULL;
    public $manufacturer = NULL;
    public $equips = NULL;
    public $hours_needed = NULL;
    public $category = NULL;
    public $rated = NULL;

    public function __construct($id = NULL)
    {
	parent::__construct($id);
    }

    /**
     * Gets Aircraft_sub based on equip.
     * Checks $equips for LIKE $equip.
     * If found return 1st object in array
     * 
     * @param string $equip
     * @return boolean FALSE if Aircraft_sub not found
     * @return object Aircraft_sub     
     */
    public function find_sub($equip = NULL)
    {
	$subs->equips = $equip;
	$sub = $subs->find_all(TRUE);

	if (!is_null($sub->id))
	    return $sub[0];
	else
	    return FALSE;
    }

    /**
     * Save Override
     * When Aircraft_sub is saved all Airframes
     * linked to $this are checked for
     * matching data and updated as necessary
     * 
     * @return none
     */
    public function save()
    {
	parent::save();

	/*	 * *
	 * If this is an update then check airframes
	 * and update them.  Just incase the category has changed
	 */
	if (!is_null($this->id))
	{
	    $airframe = new Airframe();
	    $airframe->aircraft_sub_id = $this->id;
	    $airframe->_limit = $airframe->find_all(FALSE, TRUE);
	    $airframes = $airframe->find_all();

	    if (!$airframes)
		return;

	    foreach ($airframes as $obj)
	    {
		$obj->category = $this->category;
		$obj->save();
	    }
	}
    }

}
