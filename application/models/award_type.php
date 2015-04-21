<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Award_type extends PVA_Model
{

    public $name = NULL;
    public $description = NULL;
    public $img_folder = NULL;
    public $img_width = NULL;
    public $img_height = NULL;
    public $created = NULL;
    public $modified = NULL;

    function __construct($id = NULL)
    {
	$this->_timestamps = TRUE;
	parent::__construct($id);
    }

    /**
     * Gets ALL Award_types and constructs an array
     * to be used in a dropdown form item
     * [award_type_id]award_type_name
     * 
     * @return array 
     */
    function get_dropdown()
    {
	$types = $this->find_all();

	$data = array();
	foreach ($types as $type)
	{
	    $data[$type->id] = $type->name;
	}
	return $data;
    }

}
