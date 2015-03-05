<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aircraft_sub extends PVA_model
{    
    public $designation		= NULL;
    public $manufacturer	= NULL;
    public $equips		= NULL;
    public $hours_needed	= NULL;
    public $category		= NULL;
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
    }    
}

