<?php

class User extends Admin_Controller
{
	protected $_table_name = 'users';
    protected $_table_name_profile = 'user_profiles';
    protected $_table_name_comment = 'user_comments';
    protected $_order_by = 'users.id';
    protected $_avatar_image_size = array('X' => 100, 'Y' => 100);
	
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
		/* Load external dropdown data */
		$this->load->model('my_model/airport_m');        
        $hubs = $this->airport_m->get_hub_dropdown();
		
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('User');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove delete and add functions
        $crud->unset_add();
        $crud->unset_delete();        
        
        // Setup main table columns
        $crud->columns('id', 'rank', 'first_name','last_name', 'email', 'birth_date', 'admin', 'banned');
        
        // Setup editable fields
        $crud->edit_fields('first_name','last_name', 'status', 'email', 'birth_date', 'rank', 'hub', 'admin', 'banned', 'ban_reason');
        
        // Setup View fields
        $crud->unset_view_fields('username', 'password', 'new_password_key', 'new_email', 'new_email_key', 'new_password_requested');
                
        // Setup field types
        $crud->field_type('banned', 'true_false');
        $crud->field_type('admin','dropdown', config_item('admin_levels'));
		$crud->field_type('status','dropdown', config_item('user_status'));
		$crud->field_type('hub','dropdown', $hubs);
        
        // Required fields
        $crud->required_fields('first_name', 'last_name', 'email', 'birth_date', 'hub');
        
        // Field Rules
        $crud->set_rules('email', 'Email','required|trim|valid_email|xss_clean');
        
        // Unique Fields
        $crud->unique_fields('email');
        
        // Relationships
        $crud->set_relation('rank', 'user_ranks', 'short', null, 'id');		
		
		// Set initial Order
        $crud->order_by($this->_order_by);
        
        // Display as
        $crud->display_as('id','ID');
        
        // Custom Actions
        $crud->add_action('Edit Profile', base_url('images/icons/profile_16x16.png'), 'admin/user/profile/edit','');
        $crud->add_action('User Comments', base_url('images/icons/comment_16x16.png'), 'admin/user/comment','');
        $crud->add_action('Awards', base_url('images/icons/star_16x16.png'), 'admin/award/user_awards','');
		
		// Set join variables and load model to allow join
		$this->config->grocery_select = ', users.id as id, user_profiles.*';
		$this->config->grocery_join_table = 'user_profiles';
		$this->config->grocery_join_on = 'user_profiles.user_id = users.id';
        $crud->set_model('grocery_model/user_grocery_Model');
		
		// Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
         
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/user/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
	
	public function new_users()
	{
		/* Load external dropdown data */
		$this->load->model('my_model/airport_m');        
        $hubs = $this->airport_m->get_hub_dropdown();
		
		$crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('New User');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove functions
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->unset_export();
        $crud->unset_print();
        
        // Setup main table columns
        $crud->columns('id', 'first_name','last_name', 'email', 'birth_date', 'activated');
        
        // Setup editable fields
        $crud->edit_fields('first_name','last_name', 'status', 'email', 'birth_date', 'rank', 'hub', 'admin', 'banned', 'ban_reason');
                        
        // Setup field types
        $crud->field_type('banned', 'true_false');
        $crud->field_type('activated', 'true_false');
        $crud->field_type('admin','dropdown', config_item('admin_levels'));
		$crud->field_type('status','dropdown', config_item('user_status'));
		$crud->field_type('hub','dropdown', $hubs);
        
        // Required fields
        $crud->required_fields('first_name', 'last_name', 'email', 'birth_date', 'status', 'hub');
		
        
        // Field Rules
        $crud->set_rules('email', 'Email','required|trim|valid_email|xss_clean');
        
        // Unique Fields
        $crud->unique_fields('email');
        
        // Relationships
        $crud->set_relation('rank', 'user_ranks', 'short', null, 'id');
		
		// Set initial Order
        $crud->order_by($this->_order_by);
        
        // Set where
        $crud->where('users.status', array_search('New Registration', config_item('user_status')));
        
        // Display as
        $crud->display_as('id','ID');        	
				
		// Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
         
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/user/index';
        $this->load->view('admin/_layout_main', $this->data);}	
	
    public function profile()
    {
        $this->load->model('my_model/user_m');
        
        $user_id = $this->uri->segment(4);
        
        if($user_id == NULL)
            redirect('admin/user');
            
        if($user_id == 'success')
            redirect('admin/user');
        
        if(intval($user_id) == 0)
            $user_id = $this->uri->segment(5);
            
        $user = $this->user_m->get_with_profile($user_id, TRUE);
        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name_profile);
        $crud->set_subject('User Profile');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove delete and add functions
        $crud->unset_add();
        $crud->unset_delete();
        
        // Setup editable fields
        $crud->unset_edit_fields('user_id');
		
		// Setup field types
        $crud->field_type('country','dropdown', config_item('countries'));
        
        // Set variables for file upload (applies locally ONLY)
        $this->config->grocery_crud_file_upload_allow_file_types = 'jpeg|jpg|png';
        $this->config->grocery_crud_file_upload_max_file_size = '1MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)
        $this->config->img_size = $this->_avatar_image_size;
        
        // Set callback for the file upload
        $crud->callback_before_upload(array($this->callback_model, '_valid_file'));
        
        // Set file upload field and location
        $crud->set_field_upload('avatar_image', config_item('img_folder_avatar'));
        
        // Set callback to resize the image
        $crud->callback_after_upload(array($this->callback_model, '_image_resize'));
                
        // Where
        $crud->where('user_profiles.user_id', $user_id);
         
        // Create the table
        $output = $crud->render();
        
        // Set Title of subview
        $this->data['page_title'] = 'Profile: '.$user->first_name.' '.$user->last_name;

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/user/profile';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function comment()
    {
        $this->load->model('my_model/user_m');
        
        $user_id = $this->uri->segment(4);
        
        if($user_id == NULL)
            redirect('admin/user'); 
        
        if(intval($user_id) == 0)
            $user_id = $this->uri->segment(5);
        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name_comment);
        $crud->set_subject('User Comment');
        $crud->set_theme(config_item('grocery_theme'));
        
        $user = $this->user_m->get_with_profile($user_id, TRUE);
        $admin_id = $this->data['userdata']['user_id']; 
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove delete and add functions
        //$crud->unset_add();
        $crud->unset_delete();        
        
        // Setup main table columns
        $crud->columns('comment', 'created', 'modified', 'admin_id');
        
        // Setup editable fields
        $crud->unset_fields('created', 'modified');
        
        // Setup View fields
        $crud->unset_view_fields('id', 'user_id', 'admin_id');
                       
        // Hide Fields
        $crud->field_type('user_id', 'hidden', $user_id);
        $crud->field_type('admin_id', 'hidden', $admin_id);
        
        // Required fields
        $crud->required_fields('comment');
        
        // Field Rules
        $crud->set_rules('comment', 'Comment','xss_clean');
		$crud->field_type('comment','text');
        $crud->unset_texteditor('comment','full_text');
        
        // Relationships
        $crud->set_relation('admin_id', 'users', '{first_name} {last_name}', null, 'id');
        
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
        
        // Display as
        $crud->display_as('admin_id','Administrator');
        
        // Where
        $crud->where('user_comments.user_id', $user_id);
         
        // Create the table
        $output = $crud->render();
        
        // Set Title of subview
        $this->data['page_title'] = 'Comments: '.$user->first_name.' '.$user->last_name;

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/user/comment';
        $this->load->view('admin/_layout_main', $this->data);
    }    
}