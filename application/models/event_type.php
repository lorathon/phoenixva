<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Event Type model
 * 
 * Provides all business logic for adding event types to items in the system. 
 * 
 * @author Jeff
 *
 */


class Event_type extends PVA_Model {
    
        public $name            = NULL;
        public $description     = NULL;
        public $color_id	= NULL;
        public $created         = NULL;
        public $modified        = NULL;
        
        function __construct($id = NULL)
	{
            $this->_timestamps = TRUE;
            parent::__construct($id);
	} 
        
        function get_dropdown()
        {	    
            $types = $this->find_all();
            
            $data = array();
            foreach($types as $type)
            {
                $data[$type->id] = $type->name;
            }            
            return $data;
        }
}

