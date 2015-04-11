<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base migration class.
 *
 * Migrations should extend this class for general setup and utility functions.
 * Wraps many of the dbforge methods to ensure consistency.
 * Provides a standard way of defining various field types.
 * 
 * Tables will automatically be given an 'id' field as the primary key. The
 * primary key can be extended with additional fields if needed.
 *
 * @author Chuck (https://github.com/cjtop)
 *        
 */
class Migration_base extends CI_Migration {
	
	/**
	 * The list of table names associated with this migration.
	 * 
	 * @var array
	 */
	protected $table_list = array();
	
	/**
	 * The table definitions associated with this migration.
	 * 
	 * @var array
	 */
	private $tables = array();
	
	/**
	 * The current table being manipulated.
	 * 
	 * @var string
	 */
	private $current_table = null;

	public function __construct()
	{
		log_message('debug', 'Migration base loaded');
		parent::__construct();
	}
	
	/**
	 * Upgrade the database to this migration version
	 */
	public function up()
	{
		log_message('debug', 'Running migration upgrade.');
	
		foreach ($this->table_list as $table)
		{
			$this->add_table($table);
			$method = '_define_'.$table;
			$this->{$method}();
			$this->tables[$table] = $this->current_table;				
		}
	
		$this->create_tables();
		$this->_finish_up();
	}
	
	/**
	 * Downgrade the database from this migration version
	 */
	public function down()
	{
		log_message('debug', 'Running migration downgrade.');
		$this->drop_tables();
	}
	
	/**
	 * Any items that should run after the database has been updated.
	 */
	protected function _finish_up()
	{
		// This function should be implemented in the child
	}
	
	/**
	 * Adds the fields for the current table
	 * 
	 * @param array $columns The fields for the table with the keys equal to the
	 * field names and the values equal to the field structure.
	 * 
	 * @throws Exception if there is no current table being worked on.
	 */
	protected function add_fields($columns)
	{
		if (is_null($this->current_table)) throw new Exception('Add table before adding fields.');
		log_message('debug', 'Adding fields to table '.$this->current_table->name);
		$this->current_table->fields = array_merge($this->current_table->fields, $columns);
	}
	
	/**
	 * Adds keys in addition to the id
	 * 
	 * The id field is automatically added and set as the primary key.
	 * 
	 * @param array|string $column Array of field names for a compound key or 
	 * name of the field for the key.
	 * @param boolean $primary TRUE if this key should be part of the primary key.
	 */
	protected function add_key($column, $primary = FALSE)
	{
		if (is_null($this->current_table)) throw new Exception('Add table before adding keys.');
		if ($column == 'id') throw new Exception('The ID is automatically defined as a primary key.');
		if ($primary)
		{
			$this->current_table->primary_key[] = $column;
		}
		else 
		{
			$this->current_table->keys[] = $column;
		}
	}
	
	/**
	 * Executs a DROP TABLE sql
	 * 
	 * @param string $table_name 
	 */
	protected function drop_table($table_name)
	{
		$this->dbforge->drop_table($table_name);
	}
	
	/**
	 * Executes a TABLE rename
	 * 
	 * @param unknown $old name of the table to rename
	 * @param unknown $new name for the table
	 */
	protected function rename_table($old, $new)
	{
		$this->dbforge->rename_table($old, $new);
	}
	
	/**
	 * ALTERs a table
	 * @param string $table_name
	 * @param string $alteration
	 */
	protected function modify_table($table_name, $alteration)
	{
		$this->db->query('ALTER TABLE '.$this->db->dbprefix($table_name).' '.$alteration);
	}
	
	/**
	 * Removes fields from a table
	 * 
	 * @param string $table_name
	 * @param array $fields
	 */
	protected function drop_fields($table_name, $fields)
	{
		foreach ($fields as $field)
		{
			$this->dbforge->drop_column($table_name, $field);
		}
	}
	
	/**
	 * Alters existing columns in the database
	 * 
	 * @param string $table_name
	 * @param array $fields Field definitions to alter to
	 */
	protected function modify_fields($table_name, $fields)
	{
		$this->dbforge->modify_column($table_name, $fields);
	}
	
	/**
	 * Adds a table
	 * 
	 * This will add a table to the table array and prepare the object to accept
	 * parameters for the new table. This will also automatically create the 
	 * id field so every table will have it.
	 * 
	 * @param string $table Name for the table.
	 */
	private function add_table($table)
	{
		log_message('debug', 'Adding table '.$table);
		$this->current_table = new Migration_table();
		$this->current_table->name = $table;
		$this->current_table->fields = array('id' => $this->get_id_field());
		$this->current_table->primary_key = array('id');
		$this->current_table->keys = array();
	}
	
	/**
	 * Creates all of the tables set up since the last create_table() call.
	 */
	private function create_tables()
	{
		log_message('debug', 'Creating tables.');
		log_message('debug', print_r($this->tables, TRUE));
		log_message('debug', '------------------------');
		foreach ($this->tables as $name => $def)
		{
			// Short circuit if the table was not defined.
			if (is_null($def))
			{
				log_message('debug', "Skipping {$name} table: not defined.");
				continue;
			} 
			log_message('debug', 'Table definition: ');
			log_message('debug', print_r($def, TRUE));
			log_message('debug', '--------------------------');
			$this->dbforge->add_field($def->fields);
			
			foreach ($def->primary_key as $key)
			{
				$this->dbforge->add_key($key, TRUE);
			}
			
			foreach ($def->keys as $key)
			{
				$this->dbforge->add_key($key);
			}
			
			$this->dbforge->create_table($name, TRUE);
			log_message('debug', "{$name} table created.");
		}
		// Reset the tables array
		$this->tables = array();
	}
	
	/**
	 * Drops all of the tables in the current set in reverse order.
	 */
	private function drop_tables()
	{
		array_reverse($this->table_list);
		foreach ($this->table_list as $name)
		{
			$this->dbforge->drop_table($name);
		}
	}

	/**
	 * Primary key ID fields
	 * 
	 * @return array field definition
	 */
	protected function get_id_field()
	{
		return array (
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE 
		);
	}
	
	/**
	 * Foreign key fields
	 * 
	 * @return array field definition
	 */
	protected function get_fk_field()
	{
		return array(
				'type'           => 'INT',
				'constraint'     => 10,
				'unsigned'       => TRUE,
		);
	}
	
	/**
	 * Status field
	 * 
	 * Integer with maximum value of 255
	 * 
	 * @return array field definition
	 */
	protected function get_status_field()
	{
		return array(
				'type'       => 'TINYINT',
				'constraint' => 3,
				'unsigned'   => TRUE,
				'default'    => 0,
		);
	}
	
	/**
	 * Date field
	 * 
	 * @return array field definition
	 */
	protected function get_date_field()
	{
		return array(
				'type' => 'DATE',
				'null' => TRUE,
		);
	}
	
	/**
	 * Datetime field
	 * 
	 * @return array field definition
	 */
	protected function get_datetime_field()
	{
		return array(
				'type' => 'DATETIME',
				'null' => TRUE,
		);
	}
	
	/**
	 * Timestamp field
	 * 
	 * @return array field definition
	 */
	protected function get_timestamp_field()
	{
		return array(
				'type' => 'TIMESTAMP',
		);
	}
	
	/**
	 * Counter field
	 * 
	 * Unsigned so it can handle very large numbers.
	 * 
	 * @return array field definition
	 */
	protected function get_counter_field()
	{
		return array(
				'type'       => 'BIGINT',
				'constraint' => 11,
				'unsigned'   => TRUE,
				'default'    => 0,
		);
	}
	
	/**
	 * Signed counter field
	 * 
	 * Can still handle very large numbers, but both positive and negative.
	 * 
	 * @return array field definition
	 */
	protected function get_counter_field_signed()
	{
		return array(
				'type'       => 'BIGINT',
				'constraint' => 11,
				'default'    => 0,
		);
	}
	
	/**
	 * Tiny integer
	 * 
	 * Definition for small number fields (up to +/- 128)
	 * 
	 * @return array field definition
	 */
	protected function get_tinyint_field()
	{
		return array(
				'type'       => 'TINYINT',
				'constraint' => 3,
				'default'    => 0,
		);
	}
	
	/**
	 * Boolean
	 * 
	 * Defaults to FALSE/0
	 * 
	 * @return array field definition
	 */
	protected function get_boolean_field()
	{
		return array(
				'type'       => 'TINYINT',
				'constraint' => 1,
				'default'    => 0,
		);
	}
	
	/**
	 * Calculated field
	 * 
	 * Holds a float to preserve decimals
	 * 
	 * @return array field definition
	 */
	protected function get_calculated_field()
	{
		return array(
				'type'    => 'FLOAT',
				'default' => 0,
		);
	}
	
	/**
	 * Money field
	 * 
	 * Stores to two decimal places to support currency
	 * 
	 * @return array field definition
	 */
	protected function get_money_field()
	{
		return array(
				'type'       => 'DECIMAL',
				'constraint' => '10,2',
				'default'    => 0,
		);
	}
	
	/**
	 * Standard input field
	 * 
	 * @return array field definition
	 */
	protected function get_input_field()
	{
		return array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'null'       => TRUE,
		);
	}
	
	/**
	 * Short input field
	 * 
	 * Maximum length is 50 characters (25 with double byte)
	 * 
	 * @return array field definition
	 */
	protected function get_short_input_field()
	{
		return array(
				'type'       => 'VARCHAR',
				'constraint' => 50,
				'null'       => TRUE,
		);
	}
	
	/**
	 * Long text input field
	 * 
	 * Used for storing very large inputs. Usually you will want to use the
	 * get_input_field() method instead.
	 * 
	 * @see Migration_base::get_input_field()
	 * @return array field definition
	 */
	protected function get_text_input_field()
	{
		return array(
				'type' => 'TEXT',
				'null' => TRUE,
		);
	}
	
	/**
	 * ICAO field
	 * 
	 * Definition for ICAO based fields
	 * 
	 * @return array field definition
	 */
	protected function get_icao_field()
	{
		return array(
				'type'       => 'VARCHAR',
				'constraint' => 4,
				'null'       => TRUE,
		);
	}
	
	/**
	 * Latitude and Longitude
	 * 
	 * @return array field definition
	 */
	protected function get_location_field()
	{
		return array(
				'type'       => 'FLOAT',
				'constraint' => '10,6',
				'null'       => TRUE,
		);
	}
	
	/**
	 * Landing rates
	 * 
	 * Definition for landing rate (-100.00)
	 * 
	 * @return array field definition
	 */
	protected function get_landing_rate_field()
	{
		return array(
				'type'       => 'FLOAT',
				'constraint' => '10,2',
				'null'       => TRUE,
		);
	}
	
	/**
	 * Altitude
	 * 
	 * Definition for altitude fields (0-65535). Note that the altitude cannot
	 * hold a negative value or a positive value above 65,535 feet.
	 * 
	 * @return array field definition
	 */
	protected function get_altitude_field()
	{
		return array(
				'type'       => 'SMALLINT',
				'constraint' => 5,
				'unsigned'   => TRUE,
				'default'    => 0,
		);
	}
}

/**
 * Basically a standard object with predefined properties (for convenience).
 * 
 * @author Chuck
 *
 */
class Migration_table {
	
	/**
	 * Name of the table
	 * @var string
	 */
	public $name = NULL;
	
	/**
	 * Field definitions
	 * @var array
	 */
	public $fields = NULL;
	
	/**
	 * Primary keys
	 * @var array
	 */
	public $primary_key = NULL;
	
	/**
	 * Other keys
	 * @var array
	 */
	public $keys = NULL;
}
