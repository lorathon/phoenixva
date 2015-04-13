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
class Migration_Install extends Migration_base {
	
	public function __construct()
	{
		parent::__construct();
		
		// List the tables included in this migration in the order they should be created
		$this->table_list = array(
				'users',
				'user_profiles',
				'user_stats',
				'user_aircraft',
				'user_airports',
				'user_airlines',
				'airports',
				'hub_stats',
				'airlines',
				'airlines_categories',
				'airframes',
				'airline_aircrafts',
				'airline_airports',
				'flightstats_logs',
				'schedules_pending',
				'schedules',
				'schedules_categories',
				'ranks',
				'notes',
				'ci_sessions',
				'login_attempts',
				'user_autologin',
				'session_logs',
				'articles',
				'award_types',
				'awards',
				'user_awards',
				'event_types',
				'events',
				'event_awards',
				'event_participants',
				'aircraft_subs',
		);
	}
	
	protected function _define_users()
	{
		$this->add_fields(array(
				'username' => $this->get_input_field(),
				'name' => $this->get_input_field(),
				'email' => $this->get_input_field(),
				'birthday' => $this->get_date_field(),
				'password' => $this->get_input_field(),
				'activated' => $this->get_status_field(),
				'status' => $this->get_status_field(),
				'banned' => $this->get_status_field(),
				'ban_reason' => $this->get_input_field(),
				'new_password_key' => $this->get_input_field(),
				'new_password_requested' => $this->get_datetime_field(),
				'new_email' => $this->get_input_field(),
				'new_email_key' => $this->get_input_field(),
				'last_ip' => $this->get_short_input_field(),
				'last_login' => $this->get_datetime_field(),
				'created' => $this->get_datetime_field(),
				'modified' => $this->get_timestamp_field(),
				'retire_date' => $this->get_date_field(),
				'admin_level' => $this->get_status_field(),
				'rank_id' => $this->get_fk_field(),
				'hub' => $this->get_fk_field(),
				'hub_transfer' => $this->get_fk_field(),
				'transfer_link' => $this->get_input_field(),
				'heard_about' => $this->get_input_field(),
				'ipbuser_id' => $this->get_fk_field()
		));
		$this->add_key('email', TRUE);
		$this->add_key(array (
				'hub',
				'rank_id'
		));
	}
	
	protected function _define_user_profiles()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'location' => $this->get_input_field(),
				'avatar' => $this->get_input_field(),
				'background_sig' => $this->get_input_field(),
				'sig_color' => $this->get_short_input_field(),
				'bio' => $this->get_text_input_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('user_id', TRUE);
	}
	
	protected function _define_user_stats()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'total_pay' => $this->get_money_field(),
				'pay_adjustment' => $this->get_money_field(),
				'airlines_flown' => $this->get_counter_field(),
				'aircraft_flown' => $this->get_counter_field(),
				'airports_landed' => $this->get_counter_field(),
				'fuel_used' => $this->get_calculated_field(),
				'total_landings' => $this->get_calculated_field(),
				'landing_softest' => $this->get_calculated_field(),
				'landing_hardest' => $this->get_calculated_field(),
				'total_gross' => $this->get_money_field(),
				'total_expenses' => $this->get_money_field(),
				'flights_early' => $this->get_counter_field(),
				'flights_ontime' => $this->get_counter_field(),
				'flights_late' => $this->get_counter_field(),
				'flights_manual' => $this->get_counter_field(),
				'flights_rejected' => $this->get_counter_field(),
				'hours_flights' => $this->get_counter_field(),
				'hours_transfer' => $this->get_counter_field(),
				'hours_adjustment' => $this->get_counter_field_signed(),
				'hours_type_rating' => $this->get_counter_field(),
				'hours_hub' => $this->get_counter_field(),
				'current_location' => $this->get_icao_field(),
				'last_flight_date' => $this->get_date_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('user_id', TRUE);
	}
	
	protected function _define_user_aircraft()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'aircraft_id' => $this->get_fk_field(),
				'total_hours' => $this->get_counter_field(),
				'total_landings' => $this->get_calculated_field(),
				'total_gross' => $this->get_money_field(),
				'total_expenses' => $this->get_money_field(),
				'total_pay' => $this->get_money_field(),
				'flights_early' => $this->get_counter_field(),
				'flights_ontime' => $this->get_counter_field(),
				'flights_late' => $this->get_counter_field(),
				'flights_manual' => $this->get_counter_field(),
				'fuel_used' => $this->get_counter_field()
		));
		$this->add_key('user_id', TRUE);
		$this->add_key('aircraft_id', TRUE);
	}
	
	protected function _define_user_airports()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'airport_id' => $this->get_fk_field(),
				'total_hours' => $this->get_counter_field(),
				'total_landings' => $this->get_calculated_field(),
				'flights_early' => $this->get_counter_field(),
				'flights_ontime' => $this->get_counter_field(),
				'flights_late' => $this->get_counter_field(),
				'flights_manual' => $this->get_counter_field()
		));
		$this->add_key('user_id', TRUE);
		$this->add_key('airport_id', TRUE);
	}
	
	protected function _define_user_airlines()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'airline_id' => $this->get_fk_field(),
				'total_hours' => $this->get_counter_field(),
				'total_flights' => $this->get_counter_field(),
				'total_landings' => $this->get_calculated_field(),
				'total_gross' => $this->get_money_field(),
				'total_expenses' => $this->get_money_field(),
				'total_pay' => $this->get_money_field(),
				'flights_early' => $this->get_counter_field(),
				'flights_ontime' => $this->get_counter_field(),
				'flights_late' => $this->get_counter_field(),
				'flights_manual' => $this->get_counter_field()
		));
		$this->add_key('user_id', TRUE);
		$this->add_key('airline_id', TRUE);
	}
	
	protected function _define_airports()
	{
		$this->add_fields(array (
				'fs' => $this->get_icao_field(),
				'iata' => $this->get_icao_field(),
				'icao' => $this->get_icao_field(),
				'name' => $this->get_input_field(),
				'city' => $this->get_short_input_field(),
				'state_code' => $this->get_icao_field(),
				'country_code' => $this->get_icao_field(),
				'country_name' => $this->get_short_input_field(),
				'region_name' => $this->get_short_input_field(),
				'utc_offset' => $this->get_tinyint_field(),
				'lat' => $this->get_location_field(),
				'long' => $this->get_location_field(),
				'elevation' => $this->get_altitude_field(),
				'classification' => $this->get_tinyint_field(),
				'active' => $this->get_boolean_field(),
				'port_type' => $this->get_short_input_field(),
				'hub' => $this->get_boolean_field(),
				'delay_url' => $this->get_input_field(),
				'weather_url' => $this->get_input_field(),
				'version' => $this->get_short_input_field()
		));
		$this->add_key(array (
				'iata',
				'icao',
				'hub'
		));
	}
	
	protected function _define_hub_stats()
	{
		$this->add_fields(array (
				'airport_id' => $this->get_fk_field(),
				'total_pay' => $this->get_money_field(),
				'airlines_flown' => $this->get_counter_field(),
				'aircraft_flown' => $this->get_counter_field(),
				'airports_landed' => $this->get_counter_field(),
				'fuel_used' => $this->get_calculated_field(),
				'fuel_cost' => $this->get_money_field(),
				'total_landings' => $this->get_calculated_field(),
				'landing_softest' => $this->get_calculated_field(),
				'landing_hardest' => $this->get_calculated_field(),
				'total_gross' => $this->get_money_field(),
				'total_expenses' => $this->get_money_field(),
				'flights_early' => $this->get_counter_field(),
				'flights_ontime' => $this->get_counter_field(),
				'flights_late' => $this->get_counter_field(),
				'flights_manual' => $this->get_counter_field(),
				'flights_rejected' => $this->get_counter_field(),
				'hours_flights' => $this->get_counter_field(),
				'pilots_assigned' => $this->get_counter_field(),
				'pilots_flying' => $this->get_counter_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('airport_id');
	}
	
	protected function _define_airlines()
	{
		$this->add_fields(array (
				'fs' => $this->get_icao_field(),
				'iata' => $this->get_icao_field(),
				'icao' => $this->get_icao_field(),
				'name' => $this->get_input_field(),
				'active' => $this->get_boolean_field(),
				'category' => $this->get_icao_field(),
				'fuel_discount' => $this->get_status_field(),
				'airline_image' => $this->get_input_field(),
				'total_schedules' => $this->get_counter_field(),
				'total_pireps' => $this->get_counter_field(),
				'total_hours' => $this->get_counter_field(),
				'regional' => $this->get_boolean_field(),
				'version' => $this->get_short_input_field()
		));
		$this->add_key(array (
				'iata',
				'icao'
		));
	}
	
	protected function _define_airlines_categories()
	{
		$this->add_fields(array (
				'value' => $this->get_icao_field(),
				'description' => $this->get_input_field(),
				'passenger' => $this->get_status_field(),
				'cargo' => $this->get_status_field()
		));
	}
	
	protected function _define_airframes()
	{
		$this->add_fields(array (
				'iata' => $this->get_icao_field(),
				'icao' => $this->get_icao_field(),
				'name' => $this->get_input_field(),
				'aircraft_sub_id' => $this->get_fk_field(),
				'category' => $this->get_fk_field(),
				'regional' => $this->get_boolean_field(),
				'turboprop' => $this->get_boolean_field(),
				'jet' => $this->get_boolean_field(),
				'widebody' => $this->get_boolean_field(),
				'helicopter' => $this->get_boolean_field(),
				'pax_first' => $this->get_altitude_field(),
				'pax_business' => $this->get_altitude_field(),
				'pax_economy' => $this->get_altitude_field(),
				'max_cargo' => $this->get_counter_field(),
				'max_range' => $this->get_altitude_field(),
				'oew' => $this->get_counter_field(),
				'mzfw' => $this->get_counter_field(),
				'mlw' => $this->get_counter_field(),
				'mtow' => $this->get_counter_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('aircraft_sub_id');
	}
	
	protected function _define_airline_aircrafts()
	{
		$this->add_fields(array (
				'airline_id' => $this->get_fk_field(),
				'airframe_id' => $this->get_fk_field(),
				'pax_first' => $this->get_altitude_field(),
				'pax_business' => $this->get_altitude_field(),
				'pax_economy' => $this->get_altitude_field(),
				'max_cargo' => $this->get_counter_field(),
				'total_schedules' => $this->get_counter_field(),
				'total_flights' => $this->get_counter_field(),
				'total_hours' => $this->get_counter_field(),
				'total_fuel' => $this->get_counter_field(),
				'total_landings' => $this->get_calculated_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key(array (
				'airline_id',
				'airframe_id'
		));
	}
	
	protected function _define_airline_airports()
	{
		$this->add_fields(array (
				'airline_id' => $this->get_fk_field(),
				'airport_id' => $this->get_fk_field()
		));
		$this->add_key(array (
				'airline_id',
				'airport_id'
		));
	}
	
	protected function _define_flightstats_logs()
	{
		$this->add_fields(array (
				'type' => $this->get_short_input_field(),
				'version' => $this->get_short_input_field(),
				'fs' => $this->get_icao_field(),
				'note' => $this->get_input_field(),
				'created' => $this->get_timestamp_field()
		));
	}
	
	protected function _define_schedules_pending()
	{
		$this->add_fields(array (
				'carrier' => $this->get_short_input_field(),
				'operator' => $this->get_short_input_field(),
				'flight_num' => $this->get_short_input_field(),
				'dep_airport' => $this->get_icao_field(),
				'arr_airport' => $this->get_icao_field(),
				'equip' => $this->get_icao_field(),
				'service_type' => $this->get_icao_field(),
				'service_classes' => $this->get_short_input_field(),
				'regional' => $this->get_boolean_field(),
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
				'created' => $this->get_timestamp_field()
		));
	}
	
	protected function _define_schedules()
	{
		$this->add_fields(array (
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
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key(array (
				'carrier_id',
				'operator_id',
				'dep_airport_id',
				'arr_airport_id',
				'airframe_id',
				'schedule_cat_id'
		));
	}
	
	protected function _define_schedules_categories()
	{
		$this->add_fields(array (
				'value' => $this->get_icao_field(),
				'description' => $this->get_input_field()
		));
	}
	
	protected function _define_ranks()
	{
		$this->add_fields(array (
				'rank' => $this->get_short_input_field(),
				'rank_image' => $this->get_short_input_field(),
				'min_hours' => $this->get_counter_field(),
				'pay_rate' => $this->get_money_field(),
				'short' => $this->get_short_input_field(),
				'max_cat' => $this->get_tinyint_field()
		));
	}
	
	protected function _define_notes()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'table_name' => $this->get_input_field(),
				'table_entry' => $this->get_fk_field(),
				'note' => $this->get_text_input_field(),
				'modified' => $this->get_timestamp_field(),
				'staff' => $this->get_status_field()
		));
		$this->add_key('user_id');
		$this->add_key(array (
				'table_name',
				'table_entry'
		));
	}
	
	protected function _define_ci_sessions()
	{
		$this->add_fields(array (
				'session_id VARCHAR(40) DEFAULT \'0\' NOT NULL',
				'ip_address VARCHAR(45) DEFAULT \'0\' NOT NULL',
				'user_agent VARCHAR(120) NOT NULL',
				'last_activity INT(10) unsigned DEFAULT 0 NOT NULL',
				'user_data TEXT NOT NULL'
		));
		$this->add_key('session_id', TRUE);
	}
	
	protected function _define_login_attempts()
	{
		$this->add_fields(array (
				'ip_address' => $this->get_short_input_field(),
				'login' => $this->get_short_input_field(),
				'time' => $this->get_timestamp_field()
		));
		$this->add_key('ip_address');
	}
	
	protected function _define_user_autologin()
	{
		$this->add_fields(array (
				'key_id' => array (
						'type' => 'CHAR',
						'constraint' => 32
				),
				'user_id' => $this->get_fk_field(),
				'user_agent' => $this->get_input_field(),
				'last_ip' => $this->get_short_input_field(),
				'last_login' => $this->get_timestamp_field()
		));
		$this->add_key('key_id', TRUE);
		$this->add_key('user_id', TRUE);
	}
	
	protected function _define_session_logs()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'ip_address' => $this->get_short_input_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('user_id');
	}
	
	protected function _define_articles()
	{
		$this->add_fields(array (
				'title' => $this->get_input_field(),
				'slug' => $this->get_input_field(),
				'pubdate' => $this->get_date_field(),
				'body_html' => $this->get_text_input_field(),
				'body_bbcode' => $this->get_text_input_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key('slug');
	}
	
	protected function _define_award_types()
	{
		$this->add_fields(array (
				'name' => $this->get_input_field(),
				'description' => $this->get_input_field(),
				'img_folder' => $this->get_input_field(),
				'img_width' => $this->get_altitude_field(),
				'img_height' => $this->get_altitude_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
	}
	
	protected function _define_awards()
	{
		$this->add_fields(array (
				'award_type_id' => $this->get_fk_field(),
				'name' => $this->get_input_field(),
				'description' => $this->get_input_field(),
				'award_image' => $this->get_short_input_field()
		));
		$this->add_key('award_type_id');
	}
	
	protected function _define_user_awards()
	{
		$this->add_fields(array (
				'user_id' => $this->get_fk_field(),
				'award_id' => $this->get_fk_field(),
				'created' => $this->get_timestamp_field()
		));
		$this->add_key(array (
				'user_id',
				'award_id'
		));
	}
	
	protected function _define_event_types()
	{
		$this->add_fields(array (
				'name' => $this->get_input_field(),
				'description' => $this->get_input_field(),
				'color' => $this->get_fk_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
	}
	
	protected function _define_events()
	{
		$this->add_fields(array (
				'event_type_id' => $this->get_fk_field(),
				'name' => $this->get_input_field(),
				'description' => $this->get_input_field(),
				'time_start' => $this->get_timestamp_field(),
				'time_end' => $this->get_timestamp_field(),
				'waiver_js' => $this->get_boolean_field(),
				'waiver_cat' => $this->get_boolean_field(),
				'airline_id' => $this->get_fk_field(),
				'airport_id' => $this->get_fk_field(),
				'aircraft_cat_id' => $this->get_fk_field(),
				'landing_rate' => $this->get_landing_rate_field(),
				'flight_time' => $this->get_counter_field(),
				'total_flights' => $this->get_status_field(),
				'participant_award_id' => $this->get_fk_field(),
				'participant_bonus' => $this->get_counter_field(),
				'enabled' => $this->get_boolean_field(),
				'completed' => $this->get_boolean_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field()
		));
		$this->add_key(array (
				'event_type_id',
				'airline_id',
				'airport_id',
				'participant_award_id',
		));
	}
	
	protected function _define_event_awards()
	{
		$this->add_fields(array(
				'event_id' => $this->get_fk_field(),
				'bonus_amount' => $this->get_counter_field(),
				'award_id' => $this->get_fk_field(),
				'position' => $this->get_counter_field(),
		));
		$this->add_key('event_id', TRUE);
		$this->add_key('award_id', TRUE);
	}
	
	protected function _define_event_participants()
	{
		$this->add_fields(array(
				'event_id' => $this->get_fk_field(),
				'user_id' => $this->get_fk_field(),
				'event_result' => $this->get_calculated_field(),
				'position' => $this->get_counter_field(),
		));
		$this->add_key('event_id', TRUE);
		$this->add_key('user_id', TRUE);
	}
	
	protected function _define_aircraft_subs()
	{
		$this->add_fields(array (
				'designation' => $this->get_input_field(),
				'manufacturer' => $this->get_input_field(),
				'equips' => $this->get_input_field(),
				'hours_needed' => $this->get_status_field(),
				'category' => $this->get_status_field(),
				'rated' => $this->get_boolean_field()
		));
	}
	
	protected function _finish_up()
	{
		// Leave room for legacy users so they don't have to change their IDs
		$this->modify_table('users', 'AUTO_INCREMENT = 4000');
		
		// From the original Tank_auth implementation
		$this->modify_table('ci_sessions', 'ADD KEY `last_activity_idx` (`last_activity`)');
	}
}
