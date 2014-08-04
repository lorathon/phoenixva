<?php

class Aircraft extends Admin_Controller
{
    protected $_table_name = 'aircraft';
    protected $_order_by = 'icao';
    protected $_image_size = array('X' => 600, 'Y' => 400);
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Aircraft');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('icao', 'name', 'total_schedules', 'total_pireps', 'total_hours', 'enabled');
        
        // Set/Unset add fields
        $crud->unset_add_fields('total_schedules', 'total_pireps', 'total_hours', 'total_distance');
        
        // Unset view fields
        $crud->unset_view_fields('id');
        
        // Set edit fields
        //$crud->edit_fields('');
        
        // Required fields
        $crud->required_fields('icao', 'name', 'cat');
        
        // Set field callbacks
        $crud->callback_field('range',array($this->callback_model,'_add_miles_range'));
        $crud->callback_field('oew',array($this->callback_model,'_add_pounds_oew'));
        $crud->callback_field('mzfw',array($this->callback_model,'_add_pounds_mzfw'));
        $crud->callback_field('mlw',array($this->callback_model,'_add_pounds_mlw'));
        $crud->callback_field('mtow',array($this->callback_model,'_add_pounds_mtow'));
        $crud->callback_field('cargo',array($this->callback_model,'_add_pounds_cargo'));
        $crud->callback_field('pax_first',array($this->callback_model,'_count_pax_first'));
        $crud->callback_field('pax_business',array($this->callback_model,'_count_pax_business'));
        $crud->callback_field('pax_economy',array($this->callback_model,'_count_pax_economy'));
        $crud->callback_field('total_pireps',array($this->callback_model,'_readonly'));
        $crud->callback_field('total_schedules',array($this->callback_model,'_readonly'));
        $crud->callback_field('total_hours',array($this->callback_model,'_add_hours_flighttime'));
        $crud->callback_field('total_distance',array($this->callback_model,'_add_miles_distance'));        
        $crud->callback_column('enabled', array($this->callback_model, '_callback_enabled'));
        
        // Field Rules
        $crud->set_rules('icao', 'ICAO', 'required|trim|xss_clean|strtoupper|alpha-numeric');
        $crud->set_rules('name', 'Name', 'required|trim|xss_clean');
        $crud->set_rules('range', 'Range', 'trim|xss_clean|numeric');
        $crud->set_rules('pax_first', 'Pax First Class', 'trim|xss_clean|numeric');
        $crud->set_rules('pax_business', 'Pax Business Class', 'trim|xss_clean|numeric');
        $crud->set_rules('pax_economy', 'Pax Economy Class', 'trim|xss_clean|numeric');
        $crud->set_rules('oew', 'OEW', 'trim|xss_clean|numeric');
        $crud->set_rules('mzfw', 'MZFW', 'trim|xss_clean|numeric');
        $crud->set_rules('mlw', 'MLW', 'trim|xss_clean|numeric');
        $crud->set_rules('mtow', 'MTOW', 'trim|xss_clean|numeric');
        $crud->set_rules('cargo', 'Cargo', 'trim|xss_clean|numeric');
        $crud->set_rules('total_schedules', 'Schedules', 'trim|xss_clean|numeric');
        $crud->set_rules('total_hours', 'Hours', 'trim|xss_clean|numeric');
        $crud->set_rules('total_distance', 'Distance', 'trim|xss_clean|numeric');
        
        // Unique Fields
        $crud->unique_fields('icao');
        
        // Load model 
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Unset delete
        $crud->unset_delete();
        
        // Set field types
        $crud->field_type('name', 'string');
        $crud->field_type('enabled', 'true_false');
        $crud->field_type('cat','dropdown', config_item('aircraft_cat'));
        
        // Set variables for file upload (applies locally ONLY)
        $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png';
        $this->config->grocery_crud_file_upload_max_file_size = '1MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
        $this->config->img_size = $this->_image_size;
        
        // Set callback for the file upload
        $crud->callback_before_upload(array($this->callback_model, '_valid_file'));
        
        // Set file upload field and location
        $crud->set_field_upload('aircraft_image',config_item('img_folder_aircraft'));
        
        // Set callback to resize the image
        $crud->callback_after_upload(array($this->callback_model, '_image_resize')); 
                
        // Display As
        $crud->display_as('icao','ICAO');
        $crud->display_as('cat','CAT');
        $crud->display_as('cargo','Maximum Cargo');
        $crud->display_as('total_schedules','Total Schedules');
        $crud->display_as('total_pireps','Total PIREPs');
        $crud->display_as('total_hours','Total Hours');
        $crud->display_as('pax_first','Pax First Class');
        $crud->display_as('pax_business','Pax Business Class');
        $crud->display_as('pax_economy','Pax Economy Class');
        $crud->display_as('total_distance','Total Distance');
        $crud->display_as('oew','OEW');
        $crud->display_as('mzfw','MZFW');
        $crud->display_as('mlw','MLW');
        $crud->display_as('mtow','MTOW');
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/aircraft/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    
}