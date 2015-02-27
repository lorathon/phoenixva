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
        public $_award_type     = NULL;
        
        // User Awards
        public $_user_award     = NULL; 
        	
	
	function __construct($id = NULL)
	{
            parent::__construct($id);
            $this->_award_type  = new Award_type();
            $this->_user_award = new User_award();
	} 
        
        function get_award_type($type_id = NULL)
        {
            $this->_award_type = new Award_type($type_id);
            return $this->_award_type;
        }
        
        function get_user_award($user_award_id = NULL)
        {
            $this->_user_award = new User_award($user_award_id);
            return $this->_user_award;
        }
        
        function get_user_count($award_id = NULL)
        {           
            $user_award = new User_award();
            return $user_award->get_user_count($award_id);
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

/**
 * User_awards object is essentially a sub of the User model.
 * 
 * The user awards contains all awards granted to the user.
 * @date 02/15/2015
 * @author Jeff
 *
 */
class User_award extends PVA_Model {
	
	/* Default Properties */
	public $user_id     = NULL;
        
	public $award_id    = NULL;
        
	public $created     = NULL;
        
        protected $_order_by    = 'created desc';
        
        protected $_awards_table    = 'awards';
        protected $_join            = 'awards.id = user_awards.award_id';
        protected $_awards_key      = 'awards.award_type_id';
	
	function __construct($id = NULL)
	{
            $this->_timestamps = TRUE;
            parent::__construct($id);
	} 
        
        /*Override save to ensure no double rows (WIP)
        * See if row exists with user_id and award_id
        * if it exists return FALSE
        * if not create new user award row
        */
        function save()
        {            
            $user_award = new User_award();
            $user_award->user_id = $this->user_id;
            $user_award->award_id = $this->award_id;
            
            if(! $user_award->find())
            {
                parent::save();
            } 
        }
        
        /*
         * Retrieve user awrds for user based
         * on award_type.  
         */
        
        function get_by_type($user_id, $type_id)
        {
            /* What a mess */
            $this->db->select('*, awards.award_type_id')
                    ->from($this->_table_name)
                    ->join($this->_awards_table, $this->_join)
                    ->where($this->_awards_key, $type_id)
                    ->where('user_id', $user_id)
                ;
            
            $query = $this->db->get();  
            return $this->_get_objects($query);
        }
        
        /*
         * Retrieve all awards that a user has
         * NOT been granted.  This can be used to 
         * grant an award to the user.  Awards
         * can only be granted once
         * 
         * return Award Objects
         */
        function get_not_granted($user_id)
        {
            //SELECT * FROM da05_awards WHERE da05_awards.id NOT IN (SELECT award_id FROM da05_user_awards WHERE user_id = 2)
            $this->db->select('*')
                    ->from($this->_awards_table)
                    ->where($this->_awards_table . '.id NOT IN (SELECT award_id FROM ' . 
                            $this->db->dbprefix($this->_table_name) . ' WHERE user_id = ' . $user_id.')')
                ;
            
            $query = $this->db->get();
            return $this->_get_objects($query);            
        }
        
        function get_user_count($award_id = NULL)
        {           
            $this->db->where('award_id', $award_id)
                       ->from($this->_table_name);
            return $this->db->count_all_results();
        }
        
}

