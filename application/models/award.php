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
        
        // Award Types
        private $_award_type     = NULL;
        
        // User award table
        private $_user_award_table = 'user_awards';
        
        // Count of users with award
        private $_user_count    = NULL;
	
	// array of users with award
	private $_users		= NULL;
	
	// Count of awards in type
	private $_awards_count	= NULL;
        	
	
	function __construct($id = NULL)
	{
            parent::__construct($id);
	} 
        
	/**
	 * Returns the award type of this award
	 * 
	 * @return object Award_type object
	 */
        function get_award_type() 
        {                
            if (is_null($this->_award_type)) {
                $this->_award_type = new Award_type($this->award_type_id);
            }            
            return $this->_award_type;
        }   
	
	/**
	 * Return the number of Awards with
	 * Award_type_id
	 * 
	 * @return int Award count
	 */
	function get_awards_count()
	{
	    if ( is_null($this->_awards_count))
	    {		
		$award = new Award();
		$award->award_type_id = $this->award_type_id;
		$this->_awards_count = $award->find_all(FALSE, TRUE);
	    }
	    return $this->_awards_count;
	}
	
	/**
	 * Return array of Users that have been
	 * awarded the award with Award_id
	 * 
	 * @return array of User Objects
	 */
	function get_users()
	{
	    if(is_null($this->_users))
            {
		$this->db->select('user_id, created, id as user_award_id');
                $this->db->where('award_id', $this->id)
                           ->from($this->_user_award_table);
                $query = $this->db->get();
		
		$awards = $this->_get_objects($query);	
		
		if($awards)
		{
		    foreach($awards as $award)
		    {
			$user = new User($award->user_id);
			$user->granted = $award->created;
			$user->user_award_id = $award->user_award_id;
			$this->_users[] = $user;
		    }
		}
            }
            return $this->_users;
	}
        
	/**
	 * Return the number of users that have
	 * been awarded the awrd with Award_id 
	 * 
	 * @return int count of users with Award
	 */
        function get_user_count()
        {       
            if(is_null($this->_user_count))
            {
                $this->db->where('award_id', $this->id)
                           ->from($this->_user_award_table);
                $this->_user_count = $this->db->count_all_results();
            }
            return $this->_user_count;
        }	
	
	/**
	 * Gets ALL awards and constructs an array
	 * to be used in a dropdown form item
	 * [award_id]award_name
	 * 
	 * @return array 
	 */
	function get_dropdown()
        {
	    $this->_limit = NULL;
	    $this->_order_by = 'name ASC';
	    
            $rows = $this->find_all();
            
            $data = array();
	    $data[0] = '-- NONE --';
            foreach($rows as $row)
            {
                $data[$row->id] = $row->name;
            }      
            return $data;
        }        
        
}
