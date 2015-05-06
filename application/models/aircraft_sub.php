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
        $this->equips = $equip;
        $this->_limit = 1;
        $sub = $this->find_all(TRUE);

        if (!is_null($sub[0]->id))
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

        /*         * *
         * If this is an update then check airframes
         * and update them.  Just incase the category has changed
         */
        if (!is_null($this->id))
        {
            $airframe = new Airframe();
            $airframe->aircraft_sub_id = $this->id;
            $airframe->category = $this->category;
            $airframe->update_categories();
        }
    }
    
    /**
     * Search Aircraft_sub tables autocomplete 
     * column using %LIKE%
     * 
     * @param string $search
     * @return json JSON search results
     */
    function get_autocomplete($search = NULL)
    {
	if(is_null($search))
	    echo json_encode($row_set);
	
	$this->autocomplete = $search;
	
	$aircraft = $this->find_all(TRUE);
	if ($aircraft > 0)
	{
	    foreach ($aircraft as $row)
	    {		
		$new_row['label'] = htmlentities(stripslashes($row->autocomplete));
		$new_row['value'] = htmlentities(stripslashes($row->autocomplete));		
		$new_row['id'] = $row->id;
		$row_set[] = $new_row; //build an array
	    }
	    $this->output->enable_profiler(FALSE);
	    return json_encode($row_set); //format the array into json data
	}
    }
    
    public function datatable()
    {
        $this->datatables->select('id,designation,manufacturer,equips,hours_needed,category,rated')->from('aircraft_subs');
        echo $this->datatables->generate();
    }

}
