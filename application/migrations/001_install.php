<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
class Migration_Install extends CI_Migration {

	public function up()
	{
		log_message('debug', 'Running installer.');
		
		// Load the database field configurations
		$this->config->load('pva_fields', TRUE);
		$field_config = $this->config->item('pva_fields');
		
		// Users table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'username' => $field_config['input_field'],
				'name' => $field_config['input_field'],
				'email' => $field_config['input_field'],
				'birthday' => $field_config['date_field'],
				'password' => $field_config['input_field'],
				'activated' => $field_config['status_field'],
				'status' => $field_config['status_field'],
				'banned' => $field_config['status_field'],
				'ban_reason' => $field_config['input_field'],
				'new_password_key' => $field_config['input_field'],
				'new_password_requested' => $field_config['datetime_field'],
				'new_email' => $field_config['input_field'],
				'new_email_key' => $field_config['input_field'],
				'last_ip' => $field_config['short_input_field'],
				'last_login' => $field_config['datetime_field'],
				'created' => $field_config['datetime_field'],
				'modified' => $field_config['timestamp_field'],
				'retire_date' => $field_config['date_field'],
				'admin_level' => $field_config['status_field'],
				'rank_id' => $field_config['fk_field'],
				'hub' => $field_config['fk_field'],
				'hub_transfer' => $field_config['fk_field'],
				'transfer_link' => $field_config['input_field'],
				'heard_about' => $field_config['input_field'],
				'ipbuser_id' => $field_config['fk_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('email', TRUE);
		$this->dbforge->add_key(array (
				'hub',
				'rank_id' 
		));
		$this->dbforge->create_table('users');
		// Leave room for legacy users so they don't have to change their IDs
		$this->db->query('ALTER TABLE ' . $this->db->dbprefix('users') . ' AUTO_INCREMENT = 4000');
		
		// User_profiles table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'location' => $field_config['input_field'],
				'avatar' => $field_config['input_field'],
				'background_sig' => $field_config['input_field'],
				'sig_color' => $field_config['short_input_field'],
				'bio' => $field_config['text_input_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('user_profiles');
		
		// User_stats table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'total_pay' => $field_config['money_field'],
				'pay_adjustment' => $field_config['money_field'],
				'airlines_flown' => $field_config['counter_field'],
				'aircraft_flown' => $field_config['counter_field'],
				'airports_landed' => $field_config['counter_field'],
				'fuel_used' => $field_config['calculated_field'],
				'total_landings' => $field_config['calculated_field'],
				'landing_softest' => $field_config['calculated_field'],
				'landing_hardest' => $field_config['calculated_field'],
				'total_gross' => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'flights_early' => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late' => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
				'flights_rejected' => $field_config['counter_field'],
				'hours_flights' => $field_config['counter_field'],
				'hours_transfer' => $field_config['counter_field'],
				'hours_adjustment' => $field_config['counter_field_signed'],
				'hours_type_rating' => $field_config['counter_field'],
				'hours_hub' => $field_config['counter_field'],
				'current_location' => $field_config['icao_field'],
				'last_flight_date' => $field_config['date_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('user_stats');
		
		// User_aircraft table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'aircraft_id' => $field_config['fk_field'],
				'total_hours' => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'total_gross' => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'total_pay' => $field_config['money_field'],
				'flights_early' => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late' => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
				'fuel_used' => $field_config['counter_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('aircraft_id', TRUE);
		$this->dbforge->create_table('user_aircraft');
		
		// User_airports table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'airport_id' => $field_config['fk_field'],
				'total_hours' => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'flights_early' => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late' => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airport_id', TRUE);
		$this->dbforge->create_table('user_airports');
		
		// User_airlines table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'airline_id' => $field_config['fk_field'],
				'total_hours' => $field_config['counter_field'],
				'total_flights' => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'total_gross' => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'total_pay' => $field_config['money_field'],
				'flights_early' => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late' => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airline_id', TRUE);
		$this->dbforge->create_table('user_airlines');
		
		// Airports table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'fs' => $field_config['icao_field'],
				'iata' => $field_config['icao_field'],
				'icao' => $field_config['icao_field'],
				'name' => $field_config['input_field'],
				'city' => $field_config['short_input_field'],
				'state_code' => $field_config['short_input_field'],
				'country_code' => $field_config['icao_field'],
				'country_name' => $field_config['short_input_field'],
				'region_name' => $field_config['short_input_field'],
				'utc_offset' => $field_config['tiny_int'],
				'lat' => $field_config['location_field'],
				'long' => $field_config['location_field'],
				'elevation' => $field_config['altitude_field'],
				'classification' => $field_config['tiny_int'],
				'active' => $field_config['status_field'],
				'port_type' => $field_config['short_input_field'],
				'hub' => $field_config['status_field'],
				'delay_url' => $field_config['input_field'],
				'weather_url' => $field_config['input_field'],
				'version' => $field_config['short_input_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'iata',
				'icao',
				'hub' 
		));
		$this->dbforge->create_table('airports');
		
		// Hub Stats
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'airport_id' => $field_config['fk_field'],
				'total_pay' => $field_config['money_field'],
				'airlines_flown' => $field_config['counter_field'],
				'aircraft_flown' => $field_config['counter_field'],
				'airports_landed' => $field_config['counter_field'],
				'fuel_used' => $field_config['calculated_field'],
				'fuel_cost' => $field_config['money_field'],
				'total_landings' => $field_config['calculated_field'],
				'landing_softest' => $field_config['calculated_field'],
				'landing_hardest' => $field_config['calculated_field'],
				'total_gross' => $field_config['money_field'],
				'total_expenses' => $field_config['money_field'],
				'flights_early' => $field_config['counter_field'],
				'flights_ontime' => $field_config['counter_field'],
				'flights_late' => $field_config['counter_field'],
				'flights_manual' => $field_config['counter_field'],
				'flights_rejected' => $field_config['counter_field'],
				'hours_flights' => $field_config['counter_field'],
				'pilots_assigned' => $field_config['counter_field'],
				'pilots_flying' => $field_config['counter_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('airport_id');
		$this->dbforge->create_table('hub_stats', TRUE);
		
		// Airlines table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'fs' => $field_config['icao_field'],
				'iata' => $field_config['icao_field'],
				'icao' => $field_config['icao_field'],
				'name' => $field_config['input_field'],
				'active' => $field_config['status_field'],
				'category' => $field_config['icao_field'],
				'fuel_discount' => $field_config['status_field'],
				'airline_image' => $field_config['input_field'],
				'total_schedules' => $field_config['counter_field'],
				'total_pireps' => $field_config['counter_field'],
				'total_hours' => $field_config['counter_field'],
				'regional' => $field_config['status_field'],
				'version' => $field_config['short_input_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'iata',
				'icao' 
		));
		$this->dbforge->create_table('airlines');
		
		// Airlines categories table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'value' => $field_config['icao_field'],
				'description' => $field_config['input_field'],
				'passenger' => $field_config['status_field'],
				'cargo' => $field_config['status_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('airlines_categories');
		
		// Airframe table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'iata' => $field_config['icao_field'],
				'icao' => $field_config['icao_field'],
				'name' => $field_config['input_field'],
				'aircraft_sub_id' => $field_config['fk_field'],
				'category' => $field_config['fk_field'],
				'regional' => $field_config['boolean_field'],
				'turboprop' => $field_config['boolean_field'],
				'jet' => $field_config['boolean_field'],
				'widebody' => $field_config['boolean_field'],
				'helicopter' => $field_config['boolean_field'],
				'pax_first' => $field_config['altitude_field'],
				'pax_business' => $field_config['altitude_field'],
				'pax_economy' => $field_config['altitude_field'],
				'max_cargo' => $field_config['counter_field'],
				'max_range' => $field_config['altitude_field'],
				'oew' => $field_config['counter_field'],
				'mzfw' => $field_config['counter_field'],
				'mlw' => $field_config['counter_field'],
				'mtow' => $field_config['counter_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('aircraft_sub_id');
		$this->dbforge->create_table('airframes', TRUE);
		
		// Airline Aircraft table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'airline_id' => $field_config['fk_field'],
				'airframe_id' => $field_config['fk_field'],
				'pax_first' => $field_config['altitude_field'],
				'pax_business' => $field_config['altitude_field'],
				'pax_economy' => $field_config['altitude_field'],
				'max_cargo' => $field_config['counter_field'],
				'total_schedules' => $field_config['counter_field'],
				'total_flights' => $field_config['counter_field'],
				'total_hours' => $field_config['counter_field'],
				'total_fuel' => $field_config['counter_field'],
				'total_landings' => $field_config['calculated_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'airline_id',
				'airframe_id' 
		));
		$this->dbforge->create_table('airline_aircrafts', TRUE);
		
		// Airline Airports table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'airline_id' => $field_config['fk_field'],
				'airport_id' => $field_config['fk_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'airline_id',
				'airport_id' 
		));
		$this->dbforge->create_table('airline_airports', TRUE);
		
		// Flightstats Logs
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'type' => $field_config['short_input_field'],
				'version' => $field_config['short_input_field'],
				'fs' => $field_config['icao_field'],
				'note' => $field_config['input_field'],
				'created' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('flightstats_logs', TRUE);
		
		// Pending Schedules table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'carrier' => $field_config['short_input_field'],
				'operator' => $field_config['short_input_field'],
				'flight_num' => $field_config['short_input_field'],
				'dep_airport' => $field_config['icao_field'],
				'arr_airport' => $field_config['icao_field'],
				'equip' => $field_config['icao_field'],
				'service_type' => $field_config['icao_field'],
				'service_classes' => $field_config['short_input_field'],
				'regional' => $field_config['boolean_field'],
				'dep_time_local' => $field_config['short_input_field'],
				'dep_time_utc' => $field_config['short_input_field'],
				'dep_terminal' => $field_config['short_input_field'],
				'block_time' => $field_config['short_input_field'],
				'arr_time_local' => $field_config['short_input_field'],
				'arr_time_utc' => $field_config['short_input_field'],
				'arr_terminal' => $field_config['short_input_field'],
				'version' => $field_config['short_input_field'],
				'sun' => $field_config['boolean_field'],
				'mon' => $field_config['boolean_field'],
				'tue' => $field_config['boolean_field'],
				'wed' => $field_config['boolean_field'],
				'thu' => $field_config['boolean_field'],
				'fri' => $field_config['boolean_field'],
				'sat' => $field_config['boolean_field'],
				'created' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('schedules_pending');
		
		// Schedules table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'carrier_id' => $field_config['fk_field'],
				'operator_id' => $field_config['fk_field'],
				'flight_num' => $field_config['short_input_field'],
				'dep_airport_id' => $field_config['fk_field'],
				'arr_airport_id' => $field_config['fk_field'],
				'airframe_id' => $field_config['fk_field'],
				'schedule_cat_id' => $field_config['fk_field'],
				'service_classes' => $field_config['short_input_field'],
				'regional' => $field_config['boolean_field'],
				'brand' => $field_config['input_field'],
				'dep_time_local' => $field_config['short_input_field'],
				'dep_time_utc' => $field_config['short_input_field'],
				'dep_terminal' => $field_config['short_input_field'],
				'block_time' => $field_config['short_input_field'],
				'arr_time_local' => $field_config['short_input_field'],
				'arr_time_utc' => $field_config['short_input_field'],
				'arr_terminal' => $field_config['short_input_field'],
				'version' => $field_config['short_input_field'],
				'sun' => $field_config['boolean_field'],
				'mon' => $field_config['boolean_field'],
				'tue' => $field_config['boolean_field'],
				'wed' => $field_config['boolean_field'],
				'thu' => $field_config['boolean_field'],
				'fri' => $field_config['boolean_field'],
				'sat' => $field_config['boolean_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'carrier_id',
				'operator_id',
				'dep_airport_id',
				'arr_airport_id',
				'airframe_id',
				'schedule_cat_id' 
		));
		$this->dbforge->create_table('schedules');
		
		// Schedules Categories table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'value' => $field_config['icao_field'],
				'description' => $field_config['input_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('schedules_categories');
		
		// Ranks table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'rank' => $field_config['short_input_field'],
				'rank_image' => $field_config['short_input_field'],
				'min_hours' => $field_config['counter_field'],
				'pay_rate' => $field_config['money_field'],
				'short' => $field_config['short_input_field'],
				'max_cat' => $field_config['tiny_int'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ranks');
		
		// Notes table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'table_name' => $field_config['input_field'],
				'table_entry' => $field_config['fk_field'],
				'note' => $field_config['text_input_field'],
				'modified' => $field_config['timestamp_field'],
				'staff' => $field_config['status_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key(array (
				'table_name',
				'table_entry' 
		));
		$this->dbforge->create_table('notes');
		
		// Tank Auth Sessions table
		$this->dbforge->add_field(array (
				'session_id VARCHAR(40) DEFAULT \'0\' NOT NULL',
				'ip_address VARCHAR(45) DEFAULT \'0\' NOT NULL',
				'user_agent VARCHAR(120) NOT NULL',
				'last_activity INT(10) unsigned DEFAULT 0 NOT NULL',
				'user_data TEXT NOT NULL' 
		));
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table('ci_sessions');
		$this->db->query('ALTER TABLE ' . $this->db->dbprefix('ci_sessions') . ' ADD KEY `last_activity_idx` (`last_activity`)');
		
		// Tank Auth Login_attempts table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'ip_address' => $field_config['short_input_field'],
				'login' => $field_config['short_input_field'],
				'time' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('ip_address');
		$this->dbforge->create_table('login_attempts');
		
		// Tank Auth User_autologin table
		$this->dbforge->add_field(array (
				'key_id' => array (
						'type' => 'CHAR',
						'constraint' => 32 
				),
				'user_id' => $field_config['fk_field'],
				'user_agent' => $field_config['input_field'],
				'last_ip' => $field_config['short_input_field'],
				'last_login' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('key_id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('user_autologin');
		
		// Session logging table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'ip_address' => $field_config['short_input_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('session_logs', TRUE);
		
		// Articles table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'title' => $field_config['input_field'],
				'slug' => $field_config['input_field'],
				'pubdate' => $field_config['date_field'],
				'body_html' => $field_config['text_input_field'],
				'body_bbcode' => $field_config['text_input_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('slug');
		$this->dbforge->create_table('articles');
		
		// Award_types table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'name' => $field_config['input_field'],
				'description' => $field_config['input_field'],
				'img_folder' => $field_config['input_field'],
				'img_width' => $field_config['altitude_field'],
				'img_height' => $field_config['altitude_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('award_types');
		
		// Awards table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'award_type_id' => $field_config['fk_field'],
				'name' => $field_config['input_field'],
				'description' => $field_config['input_field'],
				'award_image' => $field_config['short_input_field'] 
		));
		
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('award_type_id');
		$this->dbforge->create_table('awards');
		
		// User Awards table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'user_id' => $field_config['fk_field'],
				'award_id' => $field_config['fk_field'],
				'created' => $field_config['timestamp_field'] 
		));
		
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'user_id',
				'award_id' 
		));
		$this->dbforge->create_table('user_awards');
		
		// Event_types table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'name' => $field_config['input_field'],
				'description' => $field_config['input_field'],
				'color' => $field_config['fk_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('event_types');
		
		// Events table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'event_type_id' => $field_config['fk_field'],
				'name' => $field_config['input_field'],
				'description' => $field_config['input_field'],
				'time_start' => $field_config['timestamp_field'],
				'time_end' => $field_config['timestamp_field'],
				'waiver_js' => $field_config['boolean_field'],
				'waiver_cat' => $field_config['boolean_field'],
				'airline_id' => $field_config['fk_field'],
				'airport_id' => $field_config['fk_field'],
				'aircraft_cat_id' => $field_config['fk_field'],
				'landing_rate' => $field_config['landingrate_field'],
				'flight_time' => $field_config['counter_field'],
				'total_flights' => $field_config['status_field'],
				'bonus_1' => $field_config['status_field'],
				'bonus_2' => $field_config['status_field'],
				'bonus_3' => $field_config['status_field'],
				'award_id_winner' => $field_config['fk_field'],
				'award_id_particapant' => $field_config['fk_field'],
				'enabled' => $field_config['boolean_field'],
				'completed' => $field_config['boolean_field'],
				'user_id_1' => $field_config['fk_field'],
				'user_id_2' => $field_config['fk_field'],
				'user_id_3' => $field_config['fk_field'],
				'created' => $field_config['timestamp_field'],
				'modified' => $field_config['timestamp_field'] 
		));
		
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(array (
				'event_type_id',
				'airline_id',
				'airport_id',
				'award_id_winner',
				'award_id_particapant',
				'user_id_1',
				'user_id_2',
				'user_id_3' 
		));
		$this->dbforge->create_table('events');
		
		// Aircraft Substitution table
		$this->dbforge->add_field(array (
				'id' => $field_config['id_field'],
				'designation' => $field_config['input_field'],
				'manufacturer' => $field_config['input_field'],
				'equips' => $field_config['input_field'],
				'hours_needed' => $field_config['status_field'],
				'category' => $field_config['status_field'],
				'rated' => $field_config['boolean_field'] 
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('aircraft_subs');
	}

	public function down()
	{
		$this->dbforge->drop_table('hub_stats');
		$this->dbforge->drop_table('session_logs');
		$this->dbforge->drop_table('airline_aircrafts');
		$this->dbforge->drop_table('airline_airports');
		$this->dbforge->drop_table('schedules_pending');
		$this->dbforge->drop_table('flightstats_log');
		$this->dbforge->drop_table('airframes');
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
		$this->dbforge->drop_table('user_awards');
		$this->dbforge->drop_table('awards');
		$this->dbforge->drop_table('award_types');
		$this->dbforge->drop_table('events');
		$this->dbforge->drop_table('event_types');
		$this->dbforge->drop_table('aircraft_subs');
		$this->dbforge->drop_table('users');
	}
}
