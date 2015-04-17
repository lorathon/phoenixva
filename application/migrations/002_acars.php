<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'migration_base.php';

/**
 * Continues creation of initial database schema.
 *
 * Schema is based on the Stat/Data List document Jeff and Chuck worked on and
 * located on Google Drive.
 * 
 * @author Chuck (https://github.com/cjtop)
 * @author Jeffrey Kobus (https://github.com/lorathon)
 * @author Dustin Abell (https://github.com/gofly02)
 *        
 */
class Migration_Acars extends Migration_base {
	
	public function __construct()
	{
		parent::__construct();
	
		// List the tables included in this migration in the order they should be created
		$this->table_list = array(
				'acars_chat',
				'acars_settings',
				'positions',
				'pireps',
				'passengers',
				'passenger_logs',
				'cargo_types',
				'cargo',
				'cargo_logs',
		);
	}
	
	protected function _define_acars_chat()
	{
		$this->add_fields(array(
				'sender_id' => $this->get_fk_field(),
				'receiver_id' => $this->get_fk_field(),
				'message' => $this->get_text_input_field(),
				'created' => $this->get_timestamp_field(),
				'staff' => $this->get_boolean_field(),
				'chat' => $this->get_boolean_field(),
				'active' => $this->get_boolean_field(),				
		));
		$this->add_key('sender_id');
		$this->add_key('receiver_id');
		$this->add_key('created');
	}
	
	protected function _define_acars_settings()
	{
		$this->add_fields(array(
				'client' => $this->get_short_input_field(),
				'title' => $this->get_short_input_field(),
				'parameter' => $this->get_short_input_field(),
				'value' => $this->get_input_field(),
				'description' => $this->get_input_field(),
				'data_type' => $this->get_short_input_field(),
		));
		$this->add_key('client');
		$this->add_key('parameter');
	}
	
	protected function _define_positions()
	{
		$this->add_fields(array(
				'user_id' => $this->get_fk_field(),
				'ip_address' => $this->get_short_input_field(),
				'pirep_id' => $this->get_fk_field(),
				'load' => $this->get_calculated_field(),
				'lat' => $this->get_location_field(),
				'long' => $this->get_location_field(),
				'altitude' => $this->get_altitude_field(),
				'altitude_agl' => $this->get_altitude_field(),
				'altitude_msl' => $this->get_altitude_field(),
				'heading' => $this->get_calculated_field(),
				'ground_speed' => $this->get_calculated_field(),
				'true_airspeed' => $this->get_calculated_field(),
				'indicated_airspeed' => $this->get_calculated_field(),
				'vertical_speed' => $this->get_calculated_field(),
				'bank' => $this->get_calculated_field(),
				'pitch' => $this->get_calculated_field(),
				'fuel_onboard' => $this->get_calculated_field(),
				'phase' => $this->get_short_input_field(),
				'warning' => $this->get_boolean_field(),
				'warning_detail' => $this->get_input_field(),
				'remain_dist' => $this->get_calculated_field(),
				'remain_time' => $this->get_calculated_field(),
				'flown_time' => $this->get_calculated_field(),
				'landed' => $this->get_boolean_field(),
				'ontime' => $this->get_status_field(),
				'created' => $this->get_timestamp_field(),
		));
		$this->add_key('user_id');
		$this->add_key('pirep_id');
	}
	
	protected function _define_pireps()
	{
		$this->add_fields(array(
				'user_id'             => $this->get_fk_field(),
				'hub_id'              => $this->get_fk_field(),
				'airline_aircraft_id' => $this->get_fk_field(),
				'client'              => $this->get_short_input_field(),
				'flight_number'       => $this->get_short_input_field(),
				'flight_type'         => $this->get_status_field(),
				'dep_icao'            => $this->get_icao_field(),
				'dep_lat'             => $this->get_location_field(),
				'dep_long'            => $this->get_location_field(),
				'arr_icao'            => $this->get_icao_field(),
				'arr_lat'             => $this->get_location_field(),
				'arr_long'            => $this->get_location_field(),
				'flight_level'        => $this->get_altitude_field(),
				'route'               => $this->get_text_input_field(),
				'pax_first'           => $this->get_counter_field(),
				'pax_business'        => $this->get_counter_field(),
				'pax_economy'         => $this->get_counter_field(),
				'cargo'               => $this->get_counter_field(),
				'price_first'         => $this->get_money_field(),
				'price_business'      => $this->get_money_field(),
				'price_economy'       => $this->get_money_field(),
				'price_cargo'         => $this->get_money_field(),
				'gross_income'        => $this->get_money_field(),
				'time_out'            => $this->get_datetime_field(),
				'time_off'            => $this->get_datetime_field(),
				'time_on'             => $this->get_datetime_field(),
				'time_in'             => $this->get_datetime_field(),
				'hours_dawn'          => $this->get_counter_field(),
				'hours_day'           => $this->get_counter_field(),
				'hours_dusk'          => $this->get_counter_field(),
				'hours_night'         => $this->get_counter_field(),
				'distance'            => $this->get_calculated_field(),
				'status'              => $this->get_status_field(),
				'landing_rate'        => $this->get_landingrate_field(),
				'fuel_out'            => $this->get_calculated_field(),
				'fuel_off'            => $this->get_calculated_field(),
				'fuel_toc'            => $this->get_calculated_field(),
				'fuel_tod'            => $this->get_calculated_field(),
				'fuel_on'             => $this->get_calculated_field(),
				'fuel_in'             => $this->get_calculated_field(),
				'fuel_used'           => $this->get_calculated_field(),
				'fuel_price'          => $this->get_money_field(),
				'fuel_cost'           => $this->get_money_field(),
				'pilot_pay_rate'      => $this->get_money_field(),
				'pilot_pay_total'     => $this->get_money_field(),
				'expenses'            => $this->get_money_field(),
				'afk_elapsed'         => $this->get_counter_field(),
				'afk_attempts'        => $this->get_tiny_int_field(),
				'online'              => $this->get_boolean_field(),
				'event'               => $this->get_boolean_field(),
				'checkride'           => $this->get_boolean_field(),
				'ac_model'            => $this->get_input_field(),
				'ac_title'            => $this->get_input_field(),
				'created'             => $this->get_timestamp_field(),
				'modified'            => $this->get_timestamp_field(),
		));
		$this->add_key('user_id');
		$this->add_key('hub_id');
		$this->add_key('airline_aircraft_id');
		$this->add_key('dep_icao');
		$this->add_key('arr_icao');
	}
	
	protected function _define_passengers()
	{
		$this->add_fields(array(
				'name' => $this->get_input_field(),
				'weight' => $this->get_counter_field(),
				'home' => $this->get_icao_field(),
				'location' => $this->get_icao_field(),
				'last_pilot' => $this->get_fk_field(),
				'cur_pilot' => $this->get_fk_field(),
				'bag_max' => $this->get_status_field(),
				'bag_short' => $this->get_status_field(),
				'bag_long' => $this->get_status_field(),
				'drink_max' => $this->get_status_field(),
				'drink_short' => $this->get_status_field(),
				'drink_long' => $this->get_status_field(),
				'food_short' => $this->get_status_field(),
				'food_long' => $this->get_status_field(),
				'total_flights' => $this->get_counter_field(),
				'total_miles' => $this->get_counter_field(),
				'total_spent' => $this->get_money_field(),
				'approval' => $this->get_status_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field(),
		));
		$this->add_key('home');
		$this->add_key('location');
		$this->add_key('last_pilot');
		$this->add_key('cur_pilot');
		$this->add_key('total_miles');
	}
	
	protected function _define_passenger_logs()
	{
		$this->add_fields(array(
				'passenger_id' => $this->get_fk_field(),
				'pirep_id' => $this->get_fk_field(),
				'count_bags' => $this->get_counter_field(),
				'count_drinks' => $this->get_counter_field(),
				'cost_bags' => $this->get_money_field(),
				'cost_drinks' => $this->get_money_field(),
				'cost_food' => $this->get_money_field(),
				'cost_ticket' => $this->get_money_field(),
				'approval_rating' => $this->get_status_field(),
		));
		$this->add_key('passenger_id');
		$this->add_key('pirep_id');
	}
	
	protected function _define_cargo_types()
	{
		$this->add_fields(array(
				'description' => $this->get_short_input_field(),
				'weight_min' => $this->get_counter_field(),
				'weight_max' => $this->get_counter_field(),
		));
	}
	
	protected function _define_cargo()
	{
		$this->add_fields(array(
				'description' => $this->get_text_input_field(),
				'cargo_type_id' => $this->get_fk_field(),
				'weight' => $this->get_counter_field(),
				'start_icao' => $this->get_icao_field(),
				'end_icao' => $this->get_icao_field(),
				'location' => $this->get_icao_field(),
				'last_pilot' => $this->get_fk_field(),
				'cur_pilot' => $this->get_fk_field(),
				'total_flights' => $this->get_counter_field(),
				'shipping_pilot' => $this->get_fk_field(),
				'shipping_code' => $this->get_short_input_field(),
				'created' => $this->get_timestamp_field(),
				'modified' => $this->get_timestamp_field(),
				'delivered' => $this->get_boolean_field(),
		));
		$this->add_key('cargo_type_id');
		$this->add_key('start_icao');
		$this->add_key('end_icao');
		$this->add_key('location');
		$this->add_key('last_pilot');
		$this->add_key('cur_pilot');
		$this->add_key('shipping_pilot');
	}
	
	protected function _define_cargo_logs()
	{
		$this->add_fields(array(
				'cargo_id' => $this->get_fk_field(),
				'pirep_id' => $this->get_fk_field(),
				'cost' => $this->get_money_field(),
		));
		$this->add_key('cargo_id');
		$this->add_key('pirep_id');
	}
	
	protected function _finish_up()
	{
		$this->modify_fields('airline_aircraft', array(
				'max_cargo' => array(
						'name' => 'payload'
				),
		));
		$this->dbforge->add_column('airframes', array(
				'enabled' => $this->get_boolean_field(),
		));
		$this->dbforge->add_column('schedules_pending', array(
				'modified' => $this->get_timestamp_field(),
				'consumed' => $this->get_boolean_field(),
		));
		$this->modify_fields('schedules', array(
				'airframe_id' => array(
						'name' => 'aircraft_sub_id'
				),
		));
		$this->dbforge->add_column('users', array(
				'waivers_js' => $this->get_status_field(),
				'waivers_cat' => $this->get_status_field(),
		));
	}
}
