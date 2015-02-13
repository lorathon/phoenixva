<?php

class Migration_Create_awards extends CI_Migration {

    public function up()
    {
        log_message('debug', 'Running Create Awards.');
		
	// Load the database field configurations
        $this->config->load('pva_fields', TRUE);
	$field_config = $this->config->item('pva_fields');
                
        // Awards table
        $this->dbforge->add_field(array(
            'id'            => $field_config['id_field'],
            'type'          => $field_config['status_field'],
            'name'          => $field_config['input_field'],
            'descrip'       => $field_config['input_field'],
            'award_image'   => $field_config['short_input_field'],
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_awards');
        
         // User Awards table
        $this->dbforge->add_field(array(
            'id'            => $field_config['id_field'],
            'user_id'       => $field_config['id_field'],
            'award_id'      => $field_config['id_field'],
            'created'       => $field_config['timestamp_field'],
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_awards');
    }

    public function down()
    {
        $this->dbforge->drop_table('awards');
        $this->dbforge->drop_table('user_awards');
    }
}