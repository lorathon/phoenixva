<?php

class Rank extends Admin_Controller
{
    protected $_table_name = 'user_ranks';
    protected $_order_by = 'id';
    protected $_image_size = array('X' => 120, 'Y' => 40);
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Rank');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
         
        // Setup main table columns
        $crud->columns('short', 'rank', 'rank_image', 'min_hours', 'pay_rate');
        
        // Set add fields
        //$crud->add_fields('');
        
        // Set edit fields
        //$crud->edit_fields('');
        
        // Required fields
        $crud->required_fields('short', 'rank', 'min_hours', 'pay_rate');
        
        // Field Rules
        $crud->set_rules('short', 'ABBR', 'required|trim|xss_clean|alpha');
        $crud->set_rules('rank', 'Rank', 'required|trim|xss_clean');
        $crud->set_rules('min_hours', 'Min Hours', 'required|trim|xss_clean|numeric');
        $crud->set_rules('pay_rate', 'Pay Rate', 'required|trim|xss_clean|numeric');
        
        // Unique Fields
        $crud->unique_fields('short', 'rank');
        
        // Load model 
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Unset delete
        $crud->unset_delete();
        
        // Set field types         
        
        // Set variables for file upload (applies locally ONLY)
        $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png';
        $this->config->grocery_crud_file_upload_max_file_size = '1MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
        $this->config->img_size = $this->_image_size;
        
        // Set callback for the file upload
        $crud->callback_before_upload(array($this->callback_model, '_valid_file'));
        
        // Set file upload field and location
        $crud->set_field_upload('rank_image', config_item('img_folder_rank'));
        
        // Set callback to resize the image
        $crud->callback_after_upload(array($this->callback_model, '_image_resize'));
        
        // Unique Fields
        $crud->unique_fields('icao');
        
        // Display As
        $crud->display_as('short','ABBR');
        $crud->display_as('rank_image','Rank Image');
        $crud->display_as('min_hours','Min Hours');
        $crud->display_as('pay_rate','Pay Rate');
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/rank/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    
}