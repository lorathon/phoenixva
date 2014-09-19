<?php

class Airport extends PVA_Controller
{
    protected $_table_name = 'airports';
    protected $_order_by = 'icao';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Airport');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Set add/edit fields
        $crud->fields('icao', 'iata', 'name', 'country', 'lat', 'lng', 'hub');
        
        // Unset view fields
        $crud->unset_view_fields('id');
        
        // Required fields
        $crud->required_fields('icao', 'name', 'lat', 'lng');
        
        // Field Rules
        $crud->set_rules('icao', 'ICAO', 'required|trim|xss_clean|alpha-numeric|strtoupper');
        $crud->set_rules('iata', 'IATA', 'trim|xss_clean|alpha-numeric|strtoupper');
        $crud->set_rules('name', 'Name', 'required|trim|xss_clean');
        $crud->set_rules('lat', 'Latitude', 'required|trim|xss_clean|numeric');
        $crud->set_rules('lng', 'Logitude', 'required|trim|xss_clean|numeric');
                
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Unset delete
        $crud->unset_delete();
        
        // Set field types
        $crud->field_type('name', 'string');
        $crud->field_type('hub', 'true_false');
        $crud->field_type('country','dropdown', config_item('countries'));
        
        // Display as
        $crud->display_as('icao','ICAO');
        $crud->display_as('iata','IATA');
        $crud->display_as('lat','Latitude');
        $crud->display_as('lng','Logitude');
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/airport/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
}