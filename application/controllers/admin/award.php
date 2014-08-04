<?php

class Award extends Admin_Controller
{
    protected $_table_name = 'awards';
    protected $_table_name_users = 'user_awards';
    protected $_order_by = 'awards.id';
    protected $_image_size = array('X' => 55, 'Y' => 15);
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Award');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove delete and add functions
        $crud->unset_delete();   
         
        // Setup main table columns
        $crud->columns('name', 'type', 'award_image');
        
        // Unset view fields
        $crud->unset_view_fields('id');
        
        // Required fields
        $crud->required_fields('name', 'type', 'award_image');
        
        // Field Rules
        $crud->set_rules('name', 'Name', 'trim|xss_clean');
        
        // Unique Fields
        $crud->unique_fields('name');
        
        // Load model 
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Set field types
        $crud->field_type('name', 'string');
        $crud->field_type('type','dropdown', config_item('award_types'));
        
        // Set variables for file upload (applies locally ONLY)
        $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png';
        $this->config->grocery_crud_file_upload_max_file_size = '1MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
        $this->config->img_size = $this->_image_size;
        
        // Set callback for the file upload
        $crud->callback_before_upload(array($this->callback_model, '_valid_file'));
        
        // Set file upload field and location
        $crud->set_field_upload('award_image',config_item('img_folder_award'));
        
        // Set callback to resize the image
        $crud->callback_after_upload(array($this->callback_model, '_image_resize'));
        
        // Unique Fields
        $crud->unique_fields('icao');
        
        // Display As
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/award/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function user_awards()
    {
        $this->load->model('my_model/user_m');
        
        $user_id = $this->uri->segment(4);
        
        if($user_id == NULL)
            redirect('admin/user');
        
        if(intval($user_id) == 0)
            $user_id = $this->uri->segment(5);
            
        $user = $this->user_m->get_with_profile($user_id, TRUE);
        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Award');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove delete and add functions
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_read();
         
        // Setup main table columns
        $crud->columns('name', 'type', 'award_image', 'granted');
        
        // Unset view fields
        $crud->unset_view_fields('id');
        
        // Required fields
        $crud->required_fields('name', 'type', 'award_image');
        
        // Field Rules
        $crud->set_rules('name', 'Name', 'required|trim|xss_clean');
        
        // Unique Fields
        $crud->unique_fields('name');
        
        // Load model 
        $crud->set_model('MY_grocery_Model');
        
        // Set initial Order
        $crud->order_by($this->_order_by);
        
        // Set field types
        $crud->field_type('type','dropdown', config_item('award_types'));
        
        // Cloumn Callback
        $crud->callback_column('granted', array($this->callback_model, '_callback_enabled'));         
        
        // Set file upload field and location
        $crud->set_field_upload('award_image',config_item('img_folder_award'));
        
        // Use special select to get data
        $_select = ', (SELECT 1 FROM user_awards as ua WHERE ua.award_id = awards.id AND ua.user_id = '.$user_id.') as granted';
        $this->config->grocery_select = $_select;
        $crud->set_model('grocery_model/user_grocery_Model');
        
        $crud->add_action('Grant Award', base_url('images/icons/success_16x16.png'), 'admin/award/grant_award/'.$user_id, '');
        $crud->add_action('Remove Award', base_url('images/icons/fail_16x16.png'), 'admin/award/remove_award/'.$user_id, '');
        
        // Create the table
        $output = $crud->render();
        
        // Set Title of subview
        $this->data['page_title'] = 'Awards: '.$user->first_name.' '.$user->last_name;

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/award/user_awards';
        $this->load->view('admin/_layout_main', $this->data);
    }
        
    public function grant_award()
    {
        $this->load->model('my_model/user_award_m');
        $user_id = $this->uri->segment(4);
        $award_id = $this->uri->segment(5);
        
        //$this->user_award_m->grant_award($user_id, $award_id);
        //redirect('admin/award/user_awards/'.$user_id);        
    }
    
    public function remove_award()
    {
        $this->load->model('my_model/user_award_m');
        $user_id = $this->uri->segment(4);
        $award_id = $this->uri->segment(5);
        
        $this->user_award_m->remove_award($user_id, $award_id);
        redirect('admin/award/user_awards/'.$user_id);
    }
}