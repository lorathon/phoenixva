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
        
        //Award Types
        public $_award_types    = array();
        
        // User Awards
        private $_user_awards_key     = 'award_id';        
        private $_user_awards_table  = 'user_awards';        
        protected $_user_count  = NULL;
        	
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
                $this->_award_types = new Award_type();
	} 
        
        function get_user_count()
        {
            if (is_null($this->_user_count))
            {
              $this->db->where($this->_user_awards_key, $this->id)
                       ->from($this->_user_awards_table);
              $this->_user_count = $this->db->count_all_results();
            }
            return $this->_user_count;
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
        
        public $_award_type_id  = NULL;
        
        function __construct($id = NULL)
	{
		parent::__construct($id);
                $this->_timestamps = TRUE;
                $this->_table_name = "award_types";
	} 
        
        function get_award_type($id)
        {
            $this->id = $id;
            return parent::find();
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

