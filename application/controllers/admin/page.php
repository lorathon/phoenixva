<?php

class Page extends PVA_Controller
{
    protected $_table_name = 'pages';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Page');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Set add/edit fields
        $crud->fields('title', 'slug', 'body');
        
        // Required fields
        $crud->required_fields('title', 'slug', 'body');
        
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
                 
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/page/index';
        $this->load->view('admin/_layout_main', $this->data);
    }     
    
}