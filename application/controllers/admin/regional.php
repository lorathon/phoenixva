<?php

class Regional extends Admin_Controller
{
    protected $_table_name = 'airlines_regional';
    protected $_order_by = 'icao';
    protected $_image_size = array('X' => 130, 'Y' => 35);
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('callback_model');
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Regional Airline');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('regional_image', 'icao', 'iata', 'name', 'total_schedules', 'total_pireps', 'total_hours', 'enabled');
        
        // Set/Unset add fields
        $crud->add_fields('regional_image', 'icao', 'iata', 'name', 'fuel_discount', 'enabled');
        
        // Set/Unset edit fields
        $crud->edit_fields('regional_image', 'icao', 'iata', 'name', 'fuel_discount', 'total_schedules', 'total_pireps', 'total_hours', 'enabled');
        
        // Unset view fields
        $crud->unset_view_fields('id');
        
        // Set variables for file upload (applies locally ONLY)
        $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png|gif';
        $this->config->grocery_crud_file_upload_max_file_size = '1MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
        $this->config->img_size = $this->_image_size;
        
        // Set callback for the file upload
        $crud->callback_before_upload(array($this->callback_model, '_valid_file'));
        
        // Set file upload field and location
        $crud->set_field_upload('regional_image', config_item('img_folder_regional'));
        
        // Set callback to resize the image
        $crud->callback_after_upload(array($this->callback_model, '_image_resize'));
        
        // Required fields
        $crud->required_fields('icao', 'name');
        
        // Set field callbacks
        $crud->callback_field('total_pireps',array($this->callback_model,'_readonly'));
        $crud->callback_field('total_schedules',array($this->callback_model,'_readonly'));
        $crud->callback_field('total_hours',array($this->callback_model,'_add_hours_flighttime'));
        
        // Field Rules
        $crud->set_rules('icao', 'ICAO', 'required|trim|xss_clean|alpha|strtoupper');
        $crud->set_rules('iata', 'IATA', 'trim|xss_clean|alpha-numeric|strtoupper');
        $crud->set_rules('name', 'Name', 'required|trim|xss_clean');
        
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Unset delete
        $crud->unset_delete();
        
        // Set field types
        $crud->field_type('name', 'string');
        $crud->field_type('fuel_discount','dropdown', config_item('fuel_discount'));
        $crud->field_type('enabled', 'true_false');
        
        // Display As
        $crud->display_as('regional_image','Logo');
        $crud->display_as('icao','ICAO');
        $crud->display_as('iata','IATA');
        $crud->display_as('fuel_discount', 'Fuel Discount');
                
        // Unique Fields
        $crud->unique_fields('icao');
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        $crud->callback_column('enabled', array($this->callback_model, '_callback_enabled'));
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/airline/regional_index';
        $this->load->view('admin/_layout_main', $this->data);
    }     
}