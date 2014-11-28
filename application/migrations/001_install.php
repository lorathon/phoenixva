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
				'id'                     => $field_config['id_field'],
				'username'               => $field_config['input_field'],
				'name'                   => $field_config['input_field'],
				'email'                  => $field_config['input_field'],
				'birthday'               => $field_config['date_field'],
				'password'               => $field_config['input_field'],
				'activated'              => $field_config['status_field'],
				'status'                 => $field_config['status_field'],
				'banned'                 => $field_config['status_field'],
				'ban_reason'             => $field_config['input_field'],
				'new_password_key'       => $field_config['input_field'],
				'new_password_requested' => $field_config['datetime_field'],
				'new_email'              => $field_config['input_field'],
				'new_email_key'          => $field_config['input_field'],
				'last_ip'                => $field_config['short_input_field'],
				'last_login'             => $field_config['datetime_field'],
				'created'                => $field_config['datetime_field'],
				'modified'               => $field_config['timestamp_field'],
				'retire_date'            => $field_config['date_field'],
				'admin_level'            => $field_config['status_field'],
				'rank_id'                => $field_config['fk_field'],
				'hub'                    => $field_config['fk_field'],
				'hub_transfer'           => $field_config['fk_field'],
				'transfer_link'          => $field_config['input_field'],
				'heard_about'            => $field_config['input_field'],
				'ipbuser_id'             => $field_config['fk_field'],
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('email', TRUE);
		$this->dbforge->add_key(array('hub','rank_id'));
		$this->dbforge->create_table('users');
		// Leave room for legacy users so they don't have to change their IDs
		$this->db->query('ALTER TABLE '.$this->db->dbprefix('users').' AUTO_INCREMENT = 4000');
		
		// User_profiles table
		$this->dbforge->add_field(array(
				'id'             => $field_config['id_field'],
				'user_id'        => $field_config['fk_field'],
				'location'       => $field_config['input_field'],
				'avatar'         => $field_config['input_field'],
				'background_sig' => $field_config['input_field'],
				'sig_color'      => $field_config['short_input_field'],
				'bio'            => $field_config['text_input_field'],
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
				'total_landings'    => $field_config['calculated_field'],
				'landing_softest'   => $field_config['calculated_field'],
				'landing_hardest'   => $field_config['calculated_field'],
				'total_gross'       => $field_config['money_field'],
				'total_expenses'    => $field_config['money_field'],
				'flights_early'     => $field_config['counter_field'],
				'flights_ontime'    => $field_config['counter_field'],
				'flights_late'      => $field_config['counter_field'],
				'flights_manual'    => $field_config['counter_field'],
				'flights_rejected'  => $field_config['counter_field'],
				'hours_flights'     => $field_config['counter_field'],
				'hours_transfer'    => $field_config['counter_field'],
				'hours_adjustment'  => $field_config['counter_field_signed'],
				'hours_type_rating' => $field_config['counter_field'],
				'hours_hub'         => $field_config['counter_field'],
				'current_location'  => $field_config['icao_field'],
				'last_flight_date'  => $field_config['date_field'],
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
		
		// Airports table
		$this->dbforge->add_field(array(
				'id'              => $field_config['id_field'],
				'icao'            => $field_config['icao_field'],
				'iata'            => $field_config['icao_field'],
				'name'            => $field_config['short_input_field'],
				'city'            => $field_config['short_input_field'],
				'state_code'      => $field_config['short_input_field'],
				'country'         => $field_config['short_input_field'],
				'utc_offset'      => $field_config['tiny_int'],
				'lat'             => $field_config['location_field'],
				'long'            => $field_config['location_field'],
				'elevation'       => $field_config['altitude_field'],
				'classification'  => $field_config['status_field'],
				'type'            => $field_config['short_input_field'],
				'active'          => $field_config['status_field'],
				'delay_index_url' => $field_config['short_input_field'],
				'weather_url'     => $field_config['short_input_field'],
				'hub'             => $field_config['status_field'],
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array('icao','iata','hub'));
		$this->dbforge->create_table('airports');
		
		// Airlines table
		$this->dbforge->add_field(array(
				'id'              => $field_config['id_field'],
				'iata'            => $field_config['icao_field'],
				'icao'            => $field_config['icao_field'],
				'name'            => $field_config['short_input_field'],
				'active'          => $field_config['status_field'],
				'fuel_discount'   => $field_config['status_field'],
				'airline_image'   => $field_config['short_input_field'],
				'total_schedules' => $field_config['counter_field'],
				'total_pireps'    => $field_config['counter_field'],
				'total_hours'     => $field_config['counter_field'],
				'regional'        => $field_config['status_field'],
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array('iata','icao'));
		$this->dbforge->create_table('airlines');
		
		// Ranks table
		$this->dbforge->add_field(array(
				'id'         => $field_config['id_field'],
				'rank'       => $field_config['short_input_field'],
				'rank_image' => $field_config['short_input_field'],
				'min_hours'  => $field_config['counter_field'],
				'pay_rate'   => $field_config['money_field'],
				'short'      => $field_config['short_input_field'],
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ranks');
		
		// Notes table
		$this->dbforge->add_field(array(
				'id'          => $field_config['id_field'],
				'user_id'     => $field_config['fk_field'],
				'table_name'  => $field_config['input_field'],
				'table_entry' => $field_config['fk_field'],
				'note'        => $field_config['text_input_field'],
				'modified'    => $field_config['timestamp_field'],
				));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key(array('table_name','table_entry'));
		$this->dbforge->create_table('notes');
		
		// Tank Auth Sessions table		
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
		
		// Tank Auth Login_attempts table
		$this->dbforge->add_field(array(
				'id'         => $field_config['id_field'],
				'ip_address' => $field_config['short_input_field'],
				'login'      => $field_config['short_input_field'],
				'time'       => $field_config['timestamp_field'],
				));
		$this->dbforge->add_key('id',TRUE);
		$this->dbforge->add_key('ip_address');
		$this->dbforge->create_table('login_attempts');
		
		// Tank Auth User_autologin table
		$this->dbforge->add_field(array(
				'key_id'     => array('type' => 'CHAR', 'constraint' => 32),
				'user_id'    => $field_config['fk_field'],
				'user_agent' => $field_config['input_field'],
				'last_ip'    => $field_config['short_input_field'],
				'last_login' => $field_config['timestamp_field'],
		));
		$this->dbforge->add_key('key_id',TRUE);
		$this->dbforge->add_key('user_id',TRUE);
		$this->dbforge->create_table('user_autologin');
		
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
		$this->dbforge->drop_table('login_attempts');
		$this->dbforge->drop_table('user_autologin');
		$this->dbforge->drop_table('ci_sessions');
		$this->dbforge->drop_table('notes');
		$this->dbforge->drop_table('user_stats');
		$this->dbforge->drop_table('user_aircraft');
		$this->dbforge->drop_table('user_airports');
		$this->dbforge->drop_table('user_airlines');
		$this->dbforge->drop_table('user_profiles');
		$this->dbforge->drop_table('users');
	}
}