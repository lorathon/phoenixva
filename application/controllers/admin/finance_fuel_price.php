<?php

class Finance_Fuel_Price extends PVA_Controller
{
    protected $_table_name = 'finance_fuel_prices';
    protected $_order_by = 'id';
    protected $_order_dir = 'desc';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Fuel Price');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('crude_price', 'fuel_price', 'notes', 'created', 'modified');
        
        // Set/Unset add fields
        $crud->add_fields('crude_price', 'notes', 'fuel_price');
        
        // Set/Unset edit fields
        $crud->edit_fields('crude_price', 'notes', 'fuel_price');
        
        // Unset view fields
        $crud->unset_view_fields('id'); 
        
        // Required fields
        $crud->required_fields('crude_price', 'notes');
        
        // Set field callbacks
        $crud->callback_field('crude_price',array($this->callback_model,'_dollar_crude_price'));
        $crud->callback_field('fuel_price',array($this->callback_model,'_dollar_fuel_price'));
        
        // Field Rules
        $crud->set_rules('crude_price', 'Crude Price', 'required|trim|xss_clean|numeric');
        $crud->set_rules('notes', 'Notes', 'trim|xss_clean|alpha-numeric');
                
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by, $this->_order_dir);
        
        // Unset delete
        $crud->unset_delete();
        
        // Set field types
        //
        
        // Display As
        $crud->display_as('crude_price','Crude Oil Price');
        $crud->display_as('fuel_price','Fuel Price');
                
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
        $this->data['subview'] = 'admin/finance_fuel_prices/index';
        $this->load->view('admin/_layout_main', $this->data);
    }     
}