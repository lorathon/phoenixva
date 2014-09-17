<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Creates initial database schema.
 * 
 * Schema is based on the Stat/Data List document Jeff and Chuck worked on and
 * located on Google Drive.
 * @author Chuck
 *
 */
class Migration_Install extends PVA_Migration {	
	
	public function up()
	{		
		// Users table
		$this->dbforge->add_field(array(
				'id'          => $this->id_field,
				'name'        => $this->input_field,
				'email'       => $this->input_field,
				'birthday'    => $this->date_field,
				'password'    => $this->input_field,
				'status'      => $this->status_field,
				'admin_level' => $this->status_field,
				'rank_id'     => $this->fk_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('users'));
		
		// User_profiles table
		$this->dbforge->add_field(array(
				'id'             => $this->id_field,
				'user_id'        => $this->fk_field,
				'location'       => $this->input_field,
				'avatar'         => $this->input_field,
				'background_sig' => $this->input_field,
				'modified'       => $this->timestamp_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('user_profiles'));
		
		// User_stats table
		$this->dbforge->add_field(array(
				'id'                => $this->id_field,
				'user_id'           => $this->fk_field,
				'total_pay'         => $this->money_field,
				'pay_adjustment'    => $this->money_field,
				'airlines_flown'    => $this->counter_field,
				'aircraft_flown'    => $this->counter_field,
				'airports_landed'   => $this->counter_field,
				'fuel_used'         => $this->calculated_field,
				'total_landings'    => $this->counter_field,
				'total_gross'       => $this->money_field,
				'total_expenses'    => $this->money_field,
				'flights_early'     => $this->counter_field,
				'flights_ontime'    => $this->counter_field,
				'flights_late'      => $this->counter_field,
				'flights_manual'    => $this->counter_field,
				'flights_rejected'  => $this->counter_field,
				'hours_flights'     => $this->counter_field,
				'hours_transfer'    => $this->counter_field,
				'hours_adjustment'  => $this->counter_field,
				'hours_type_rating' => $this->counter_field,
				'current_location'  => $this->icao_field,
				'modified'          => $this->timestamp_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('user_stats'));
		
		// User_aircraft table
		$this->dbforge->add_field(array(
				'id'             => $this->id_field,
				'user_id'        => $this->fk_field,
				'aircraft_id'    => $this->fk_field,
				'total_hours'    => $this->counter_field,
				'total_landings' => $this->calculated_field,
				'total_gross'    => $this->money_field,
				'total_expenses' => $this->money_field,
				'total_pay'      => $this->money_field,
				'flights_early'  => $this->counter_field,
				'flights_ontime' => $this->counter_field,
				'flights_late'   => $this->counter_field,
				'flights_manual' => $this->counter_field,
				'fuel_used'      => $this->counter_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('aircraft_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('user_aircraft'));
		
		// User_airports table
		$this->dbforge->add_field(array(
				'id'             => $this->id_field,
				'user_id'        => $this->fk_field,
				'airport_id'     => $this->fk_field,
				'total_hours'    => $this->counter_field,
				'total_landings' => $this->calculated_field,
				'flights_early'  => $this->counter_field,
				'flights_ontime' => $this->counter_field,
				'flights_late'   => $this->counter_field,
				'flights_manual' => $this->counter_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airport_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('user_airports'));
		
		// User_airlines table
		$this->dbforge->add_field(array(
				'id'             => $this->id_field,
				'user_id'        => $this->fk_field,
				'airline_id'     => $this->fk_field,
				'total_hours'    => $this->counter_field,
				'total_landings' => $this->calculated_field,
				'total_gross'    => $this->money_field,
				'total_expenses' => $this->money_field,
				'total_pay'      => $this->money_field,
				'flights_early'  => $this->counter_field,
				'flights_ontime' => $this->counter_field,
				'flights_late'   => $this->counter_field,
				'flights_manual' => $this->counter_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_key('airline_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('user_airlines'));
		
		// Notes table
		$this->dbforge->add_field(array(
				'id'          => $this->id_field,
				'user_id'     => $this->fk_field,
				'table'       => $this->input_field,
				'table_entry' => $this->fk_field,
				'note'        => $this->text_input_field,
				'modified'    => $this->timestamp_field,
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key(array('table','table_entry'));
		$this->dbforge->create_table($this->db->dbprefix('notes'));
		
		// Sessions table		
		$this->dbforge->add_field(array(
				'session_id VARCHAR(40) DEFAULT \'0\' NOT NULL',
				'ip_address VARCHAR(45) DEFAULT \'0\' NOT NULL',
				'user_agent VARCHAR(120) NOT NULL',
				'last_activity INT(10) unsigned DEFAULT 0 NOT NULL',
				'user_data TEXT NOT NULL',
		));
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table($this->db->dbprefix('ci_sessions'));
		$this->db->query('ALTER TABLE '.$this->db->dbprefix('ci_sessions').' ADD KEY `last_activity_idx` (`last_activity`)');
		
	}
	
	public function down()
	{
		$this->dbforge->drop_table($this->db->dbprefix('ci_sessions'));
		$this->dbforge->drop_table($this->db->dbprefix('notes'));
		$this->dbforge->drop_table($this->db->dbprefix('user_stats'));
		$this->dbforge->drop_table($this->db->dbprefix('user_aircraft'));
		$this->dbforge->drop_table($this->db->dbprefix('user_airports'));
		$this->dbforge->drop_table($this->db->dbprefix('user_airlines'));
		$this->dbforge->drop_table($this->db->dbprefix('users'));
	}
}