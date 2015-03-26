<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Continues Creation of initial database schema.
 *
 * Schema is based on the Stat/Data List document Jeff and Chuck worked on and
 * located on Google Drive.
 * @author Chuck
 *
 */
class Migration_Install2 extends CI_Migration {

	public function up()
	{
		log_message('debug', 'Updating to version 002 database.');
		
		// Load the database field configurations
		$this->config->load('pva_fields', TRUE);
		$field_config = $this->config->item('pva_fields');
		
		// Session logging table
		$this->dbforge->add_field(array(
				'id'         => $field_config['id_field'],
				'user_id'    => $field_config['fk_field'],
				'ip_address' => $field_config['short_input_field'],
				'created'    => $field_config['timestamp_field'],
				'modified'   => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('session_logs', TRUE);
		
		
		// Airline Aircraft table
		$this->dbforge->add_field(array(
				'id'		    => $field_config['id_field'],
				'airline_id'	    => $field_config['fk_field'],
				'aircraft_id'	    => $field_config['fk_field'],
				'total_schedules'   => $field_config['counter_field'],
				'total_flights'	    => $field_config['counter_field'],
				'total_hours'	    => $field_config['counter_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('airline_id');
		$this->dbforge->add_key('aircraft_id');
		$this->dbforge->create_table('airline_aircrafts', TRUE);
		
		
		// Airline Airports table
		$this->dbforge->add_field(array(
				'id'		    => $field_config['id_field'],
				'airline_id'	    => $field_config['fk_field'],
				'airport_id'	    => $field_config['fk_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('airline_id');
		$this->dbforge->add_key('airport_id');
		$this->dbforge->create_table('airline_airports', TRUE);
	}
	
	public function down()
	{
		$this->dbforge->drop_table('session_logs');
		$this->dbforge->drop_table('airline_aircrafts');
		$this->dbforge->drop_table('airline_airports');
	}
}