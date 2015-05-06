<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'migration_base.php';

/**
 * Creates initial database schema.
 *
 * Schema is based on the Stat/Data List document Jeff and Chuck worked on and
 * located on Google Drive.
 * 
 * @author Chuck (https://github.com/cjtop)
 * @author Jeffrey Kobus (https://github.com/lorathon)
 * @author Dustin Abell (https://github.com/gofly02)
 *        
 */
class Migration_Bids extends Migration_base {
	
	public function __construct()
	{
		parent::__construct();
		
		// List the tables included in this migration in the order they should be created
		$this->table_list = array(
				'bids',
		);
	}
	
	protected function _define_bids()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'carrier_id' => $this->get_fk_field(),
				'operator_id' => $this->get_fk_field(),
				'flight_num' => $this->get_short_input_field(),
				'dep_airport_id' => $this->get_fk_field(),
				'arr_airport_id' => $this->get_fk_field(),
				'airframe_id' => $this->get_fk_field(),
				'schedule_cat_id' => $this->get_fk_field(),
				'service_classes' => $this->get_short_input_field(),
				'regional' => $this->get_boolean_field(),
				'brand' => $this->get_input_field(),
				'dep_time_local' => $this->get_short_input_field(),
				'dep_time_utc' => $this->get_short_input_field(),
				'dep_terminal' => $this->get_short_input_field(),
				'block_time' => $this->get_short_input_field(),
				'arr_time_local' => $this->get_short_input_field(),
				'arr_time_utc' => $this->get_short_input_field(),
				'arr_terminal' => $this->get_short_input_field(),
				'version' => $this->get_short_input_field(),
				'sun' => $this->get_boolean_field(),
				'mon' => $this->get_boolean_field(),
				'tue' => $this->get_boolean_field(),
				'wed' => $this->get_boolean_field(),
				'thu' => $this->get_boolean_field(),
				'fri' => $this->get_boolean_field(),
				'sat' => $this->get_boolean_field(),
				'sort' => $this->get_status_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key(array (
				'user_id',
				'carrier_id',
				'operator_id',
				'dep_airport_id',
				'arr_airport_id',
				'airframe_id',
				'schedule_cat_id'
		));
	}	
	
	protected function _finish_up()
	{
	    
	}
}
