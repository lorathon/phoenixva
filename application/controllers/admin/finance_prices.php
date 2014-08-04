<?php

class Finance_Prices extends Admin_Controller
{
    protected $_table_name = 'finance_prices';
    protected $_order_by = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Prices');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('name', 'title', 'value');
        
        // Required fields
        $crud->required_fields('name', 'title', 'value');
        
        // Set field callbacks
        $crud->callback_field('name',array($this->callback_model,'_readonly'));
        $crud->callback_field('title',array($this->callback_model,'_readonly'));
        $crud->callback_field('description',array($this->callback_model,'_readonly_textarea'));
        
        // Field Rules
        $crud->set_rules('value', 'Value', 'required|trim|xss_clean');
                
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Unset delete
        $crud->unset_delete();
        $crud->unset_add();
        
        // Set field types
        $crud->field_type('description','text');
        $crud->unset_texteditor('description','full_text');
                        
        // Unique Fields
        $crud->unique_fields('name');
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        $crud->callback_column('enabled', array($this->callback_model, '_callback_enabled'));
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/finance_prices/index';
        $this->load->view('admin/_layout_main', $this->data);
    }     
}