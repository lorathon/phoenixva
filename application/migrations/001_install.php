<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Creates initial database schema.
 * 
 * Schema is based on the Stat/Data List document Jeff and Chuck worked on and
 * located on Google Drive.
 * @author Chuck
 *
 */
class Migration_Install extends CI_Migration {	
	
	public function up()
	{
		log_message('debug', 'Running installer.');
		
		// Load the database field configurations
		$this->config->load('pva_fields', TRUE);
		$field_config = $this->config->item('pva_fields');
		
		// Users table
		$this->dbforge->add_field(array(
				'id'          => $field_config['id_field'],
				'name'        => $field_config['input_field'],
				'email'       => $field_config['input_field'],
				'birthday'    => $field_config['date_field'],
				'password'    => $field_config['input_field'],
				'status'      => $field_config['status_field'],
				'admin_level' => $field_config['status_field'],
				'rank_id'     => $field_config['fk_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('users');
		
		// User_profiles table
		$this->dbforge->add_field(array(
				'id'             => $field_config['id_field'],
				'user_id'        => $field_config['fk_field'],
				'location'       => $field_config['input_field'],
				'avatar'         => $field_config['input_field'],
				'background_sig' => $field_config['input_field'],
				'modified'       => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('user_profiles');
		
		// User_stats table
		$this->dbforge->add_field(array(
				'id'                => $field_config['id_field'],
				'user_id'           => $field_config['fk_field'],
				'total_pay'         => $field_config['money_field'],
				'pay_adjustment'    => $field_config['money_field'],
				'airlines_flown'    => $field_config['counter_field'],
				'aircraft_flown'    => $field_config['counter_field'],
				'airports_landed'   => $field_config['counter_field'],
				'fuel_used'         => $field_config['calculated_field'],
				'total_landings'    => $field_config['counter_field'],
				'total_gross'       => $field_config['money_field'],
				'total_expenses'    => $field_config['money_field'],
				'flights_early'     => $field_config['counter_field'],
				'flights_ontime'    => $field_config['counter_field'],
				'flights_late'      => $field_config['counter_field'],
				'flights_manual'    => $field_config['counter_field'],
				'flights_rejected'  => $field_config['counter_field'],
				'hours_flights'     => $field_config['counter_field'],
				'hours_transfer'    => $field_config['counter_field'],
				'hours_adjustment'  => $field_config['counter_field'],
				'hours_type_rating' => $field_config['counter_field'],
				'current_location'  => $field_config['icao_field'],
				'modified'          => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('user_stats');
		
		// User_aircraft table
		$this->dbforge->add_field(array(
				'id'             => $field_config['id_field'],
				'user_id'        => $field_config['fk_field'],
				'aircraft_id'    => $field_config['fk_field'],
				'total_hours'    => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'total_gross'    => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'total_pay'      => $field_config['money_field'],
				'flights_early'  => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late'   => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
				'fuel_used'      => $field_config['counter_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('aircraft_id', TRUE);
		$this->dbforge->create_table('user_aircraft');
		
		// User_airports table
		$this->dbforge->add_field(array(
				'id'             => $field_config['id_field'],
				'user_id'        => $field_config['fk_field'],
				'airport_id'     => $field_config['fk_field'],
				'total_hours'    => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'flights_early'  => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late'   => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airport_id', TRUE);
		$this->dbforge->create_table('user_airports');
		
		// User_airlines table
		$this->dbforge->add_field(array(
				'id'             => $field_config['id_field'],
				'user_id'        => $field_config['fk_field'],
				'airline_id'     => $field_config['fk_field'],
				'total_hours'    => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'total_gross'    => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'total_pay'      => $field_config['money_field'],
				'flights_early'  => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late'   => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airline_id', TRUE);
		$this->dbforge->create_table('user_airlines');
		
		// Notes table
		$this->dbforge->add_field(array(
				'id'          => $field_config['id_field'],
				'user_id'     => $field_config['fk_field'],
				'table'       => $field_config['input_field'],
				'table_entry' => $field_config['fk_field'],
				'note'        => $field_config['text_input_field'],
				'modified'    => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key(array('table','table_entry'));
		$this->dbforge->create_table('notes');
		
		// Sessions table		
		$this->dbforge->add_field(array(
				'session_id VARCHAR(40) DEFAULT \'0\' NOT NULL',
				'ip_address VARCHAR(45) DEFAULT \'0\' NOT NULL',
				'user_agent VARCHAR(120) NOT NULL',
				'last_activity INT(10) unsigned DEFAULT 0 NOT NULL',
				'user_data TEXT NOT NULL',
		));
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table('ci_sessions');
		$this->db->query('ALTER TABLE '.$this->db->dbprefix('ci_sessions').' ADD KEY `last_activity_idx` (`last_activity`)');
		
		// Articles table
		$this->dbforge->add_field(array(
				'id'       => $field_config['id_field'],
				'title'    => $field_config['input_field'],
				'slug'     => $field_config['input_field'],
				'pubdate'  => $field_config['date_field'],
				'body'     => $field_config['text_input_field'],
				'created'  => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('articles');
	}
	
	public function down()
	{
		$this->dbforge->drop_table('articles');
		$this->dbforge->drop_table('ci_sessions');
		$this->dbforge->drop_table('notes');
		$this->dbforge->drop_table('user_stats');
		$this->dbforge->drop_table('user_aircraft');
		$this->dbforge->drop_table('user_airports');
		$this->dbforge->drop_table('user_airlines');
		$this->dbforge->drop_table('users');
	}
}