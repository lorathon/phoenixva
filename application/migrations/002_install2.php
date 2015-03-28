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
                
                // Pending Schedules table
		$this->dbforge->add_field(array(
				'id'              => $field_config['id_field'],
				'flight_id'       => $field_config['short_input_field'],
				'carrier'         => $field_config['short_input_field'],
				'operator'        => $field_config['short_input_field'],
				'flight_num'      => $field_config['short_input_field'],
				'dep_airport'     => $field_config['icao_field'],
				'arr_airport'     => $field_config['icao_field'],
				'equip'           => $field_config['icao_field'],
				'tail_number'     => $field_config['short_input_field'],
				'flight_type'     => $field_config['icao_field'],
				'regional'        => $field_config['status_field'],
				'dep_time_local'  => $field_config['short_input_field'],
				'dep_time_utc'    => $field_config['short_input_field'],
				'dep_terminal'    => $field_config['short_input_field'],
				'dep_gate'        => $field_config['short_input_field'],
				'block_time'      => $field_config['short_input_field'],
				'taxi_out_time'   => $field_config['short_input_field'],
				'air_time'        => $field_config['short_input_field'],
				'taxi_in_time'    => $field_config['short_input_field'],
				'arr_time_local'  => $field_config['short_input_field'],
				'arr_time_utc'    => $field_config['short_input_field'],
				'arr_terminal'    => $field_config['short_input_field'],
				'arr_gate'        => $field_config['short_input_field'],
				'downline_apt'	  => $field_config['icao_field'],
				'downline_fltId'  => $field_config['short_input_field'],
                                'version'         => $field_config['icao_field'],
				'source_date'	  => $field_config['timestamp_field'],	
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('schedules_pending');
		
		// Hub Stats
		$this->dbforge->add_field(array(
				'id'               => $field_config['id_field'],
				'airport_id'       => $field_config['fk_field'],
				'total_pay'        => $field_config['money_field'],
				'airlines_flown'   => $field_config['counter_field'],
				'aircraft_flown'   => $field_config['counter_field'],
				'airports_landed'  => $field_config['counter_field'],
				'fuel_used'        => $field_config['calculated_field'],
				'fuel_cost'        => $field_config['money_field'],
				'total_landings'   => $field_config['calculated_field'],
				'landing_softest'  => $field_config['calculated_field'],
				'landing_hardest'  => $field_config['calculated_field'],
				'total_gross'      => $field_config['money_field'],
				'total_expenses'   => $field_config['money_field'],
				'flights_early'    => $field_config['counter_field'],
				'flights_ontime'   => $field_config['counter_field'],
				'flights_late'     => $field_config['counter_field'],
				'flights_manual'   => $field_config['counter_field'],
				'flights_rejected' => $field_config['counter_field'],
				'hours_flights'    => $field_config['counter_field'],
				'pilots_assigned'  => $field_config['counter_field'],
				'pilots_flying'    => $field_config['counter_field'],
				'created'          => $field_config['timestamp_field'],
				'modified'         => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('airport_id');
		$this->dbforge->create_table('hub_stats', TRUE);
	}
	
	public function down()
	{
		$this->dbforge->drop_table('hub_stats');
		$this->dbforge->drop_table('session_logs');
		$this->dbforge->drop_table('airline_aircrafts');
		$this->dbforge->drop_table('airline_airports');
	}
}