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
        private $_award_type     = NULL;
        
        //User award table
        private $_user_award_table = 'user_awards';
        
        //Count of users with award
        private $_user_count    = NULL;
        	
	
	function __construct($id = NULL)
	{
            parent::__construct($id);
	} 
        
        function get_award_type() 
        {                
            if (is_null($this->_award_type)) {
                $this->_award_type = new Award_type($this->award_type_id);
            }            
            return $this->_award_type;
        }        
        
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
