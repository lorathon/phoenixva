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
        	
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	} 
        /*
         * Override find_all() to allow
         * for the joining of tables to reduce
         * number of queries
         */
        function get_all()
        {
            $this->_limit = 300;
            $this->_join = array(
                'award_types'   => 'award_types.id = awards.award_type_id',
            );
            $this->_select = 'awards.*, award_types.img_folder, award_types.img_width, award_types.img_height, award_types.name as type';
            return parent::find_all_join();
        }
        
}

