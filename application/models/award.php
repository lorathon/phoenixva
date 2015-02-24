<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Award model
 * 
 * Provides all business logic for adding awards to items in the system. 
 * 
 * @author Jeff
 *
 */
class Award extends PVA_Model {	
	       
        /**
	 * The type of the award. (from admin_config)
         * '1'  => 'Manually Granted',
         * '2'  => 'Flight # Award',
         * '3'  => 'Flight Hours Award',
         * '4'  => 'Time in Service Award'
	 * 
	 * @var number
	 */
	public $type            = NULL;
        
        /**
	 * The type of the award. (from award_types table)
	 * 
	 * @var fk int
	 */
	public $award_type_id   = NULL;
        
        /**
	 * The name of the award.
	 * 
	 * @var string
	 */
	public $name            = NULL;
        
        /**
	 * The description of the award.
	 * 
	 * @var string
	 */
	public $description     = NULL;
        
        /**
	 * The image link.
	 * 
	 * @var url
	 */
	public $award_image     = NULL;	
        
        
        protected $_award_types = NULL;
	
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
                $this->_award_types = new Award_type();
	}
        
        function get_award_types()
        {            
            $this->_award_types->find_all();
            return $this->_award_types;
        }
        
}

class Award_type extends PVA_Model {
    
        public $name            = NULL;
        public $description     = NULL;
        public $img_folder      = NULL;
        public $img_width       = NULL;
        public $img_height      = NULL;
        public $created         = NULL;
        public $modified        = NULL;
        
        function __construct($id = NULL)
	{
		parent::__construct($id);
                $this->_table_name = 'award_types';
	} 
}