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
	public $descrip         = NULL;
        
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
        
}