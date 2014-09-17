<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration base class for the PVA system.
 * 
 * Includes Definitions of common field types to ensure consistency. All
 * migrations should extend PVA_Migration instead of CI_Migration.
 * @author Chuck
 *
 */
class PVA_Migration extends CI_Migration {
	
	/* Definition for primary key (id) fields */
	protected $id_field = array(
			'type'           => 'INT',
			'constraint'     => 10,
			'unsigned'       => TRUE,
			'auto_increment' => TRUE,
			);
	
	/* Definition for foreign key fields */
	protected $fk_field = array(
			'type'           => 'INT',
			'constraint'     => 10,
			'unsigned'       => TRUE,
			);
	
	/* Definition for status fields */
	protected $status_field = array(
			'type'       => 'TINYINT',
			'constraint' => 3,
			'unsigned'   => TRUE,
			);
	
	/* Definition for date fields */
	protected $date_field = array(
			'type' => 'DATE',
			);
	
	/* Definition for timestamp fields */
	protected $timestamp_field = array(
			'type' => 'TIMESTAMP',
			);
	
	/* Definition for counter fields */
	protected $counter_field = array(
			'type'       => 'BIGINT',
			'constraint' => 11,
			'unsigned'   => TRUE,
			'default'    => 0,
			);
	
	/* Definition for calculated fields */
	protected $calculated_field = array(
			'type' => 'FLOAT',
			);
	
	/* Definition for money fields */
	protected $money_field = array(
			'type'       => 'DECIMAL',
			'constraint' => '10,2',
			);
	
	/* Definition for user input fields */
	protected $input_field = array(
			'type'       => 'VARCHAR',
			'constraint' => 255,
			);
	
	/* Definition for short input fields */
	protected $short_input_field = array(
			'type'       => 'VARCHAR',
			'constraint' => 25,
			);
	
	/* Definition for text input fields */
	protected $text_input_field = array(
			'type' => 'TEXT',
			);
	
	/* Definition for ICAO based fields */
	protected $icao_field = array(
			'type'       => 'VARCHAR',
			'constraint' => 4,
			);
	
	/* Definition for location fields (lat/long) */
	protected $location_field = array(
			'type'       => 'FLOAT',
			'constraint' => '10,6',
			);
	
	public function up() 
	{
		
	}
	
	public function down() 
	{
		
	}
}