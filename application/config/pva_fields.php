<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Definition for primary key (id) fields */
$config['id_field'] = array(
		'type'           => 'INT',
		'constraint'     => 10,
		'unsigned'       => TRUE,
		'auto_increment' => TRUE,
);

/* Definition for foreign key fields */
$config['fk_field'] = array(
		'type'           => 'INT',
		'constraint'     => 10,
		'unsigned'       => TRUE,
);

/* Definition for status fields */
$config['status_field'] = array(
		'type'       => 'TINYINT',
		'constraint' => 3,
		'unsigned'   => TRUE,
);

/* Definition for date fields */
$config['date_field'] = array(
		'type' => 'DATE',
);

/* Definition for datetime fields */
$config['datetime_field'] = array(
		'type' => 'DATETIME',
);

/* Definition for timestamp fields */
$config['timestamp_field'] = array(
		'type' => 'TIMESTAMP',
);

/* Definition for counter fields */
$config['counter_field'] = array(
		'type'       => 'BIGINT',
		'constraint' => 11,
		'unsigned'   => TRUE,
		'default'    => 0,
);

/* Definition for counter fields */
$config['counter_field_signed'] = array(
		'type'       => 'BIGINT',
		'constraint' => 11,
		'default'    => 0,
);

/* Definition for small number fields (up to +/- 128) */
$config['tiny_int'] = array(
		'type'       => 'TINYINT',
		'constraint' => 3,
		'default'    => 0,
);

/* Definition for calculated fields */
$config['calculated_field'] = array(
		'type' => 'FLOAT',
);

/* Definition for money fields */
$config['money_field'] = array(
		'type'       => 'DECIMAL',
		'constraint' => '10,2',
);

/* Definition for user input fields */
$config['input_field'] = array(
		'type'       => 'VARCHAR',
		'constraint' => 255,
);

/* Definition for short input fields */
$config['short_input_field'] = array(
		'type'       => 'VARCHAR',
		'constraint' => 50,
);

/* Definition for text input fields */
$config['text_input_field'] = array(
		'type' => 'TEXT',
);

/* Definition for ICAO based fields */
$config['icao_field'] = array(
		'type'       => 'VARCHAR',
		'constraint' => 4,
);

/* Definition for location fields (lat/long) */
$config['location_field'] = array(
		'type'       => 'FLOAT',
		'constraint' => '10,6',
);

/* Definition for altitude fields (0-65535) */
$config['altitude_field'] = array(
		'type'       => 'SMALLINT',
		'constraint' => 5,
		'unsigned'   => TRUE,
		'default'    => 0,
);