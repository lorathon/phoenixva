<?php

class Finance_Pireps_Fees extends Admin_Controller
{
    protected $_table_name = 'finance_pireps_fees';
    protected $_order_by = 'id';
    protected $_order_dir = 'asc';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('PIREP Fees');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('name', 'price', 'type', 'created', 'modified', 'enabled');
        
        // Set/Unset add fields
        $crud->add_fields('name', 'notes', 'price', 'type', 'enabled');
        
        // Set/Unset edit fields
        $crud->edit_fields('name', 'notes', 'price', 'type', 'enabled');
        
        // Unset view fields
        $crud->unset_view_fields('id'); 
        
        // Required fields
        $crud->required_fields('name', 'price', 'type', 'enabled');
        
        // Set field callbacks
        $crud->callback_field('price',array($this->callback_model,'_dollar_price'));
        $crud->callback_column('enabled', array($this->callback_model, '_callback_enabled'));
        
        // Field Rules
        $crud->set_rules('name', 'Name', 'required|trim|xss_clean|alpha-numeric');
        $crud->set_rules('notes', 'Notes', 'trim|xss_clean|alpha-numeric');
                
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by, $this->_order_dir);
        
        // Unset delete
        //$crud->unset_delete();
        
        // Set field types
        $crud->field_type('type','dropdown', config_item('pireps_fees_types'));
        $crud->field_type('notes','text');
        $crud->unset_texteditor('notes','full_text');
        
        // Display As
        //
                
        // Unique Fields
        //
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/finance_pireps_fees/index';
        $this->load->view('admin/_layout_main', $this->data);
    }     
}