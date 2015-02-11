<?php

class Upload extends PVA_Controller {  
    
        /**
         * The path to the folder where the upload should be placed. 
         * The folder must be writable and the path can be absolute 
         * or relative.
         * 
         * @var string
         */
        public $upload_path     = './uploads/';
        
        /**
         * The mime types corresponding to the types of files you allow
         * to be uploaded. Usually the file extension can be used as the 
         * mime type. Separate multiple types with a pipe.
         * 
         * @var string
         */
        public $allowed_types   = 'gif|jpg|png';
        
        /**
         * If set CodeIgniter will rename the uploaded file to this name. 
         * The extension provided in the file name must also be an allowed 
         * file type.
         * 
         * @var string
         */
        public $file_name       = NULL;
        
        /**
         * If set to true, if a file with the same name as the one you are 
         * uploading exists, it will be overwritten. If set to false, a 
         * number will be appended to the filename if another with the same 
         * name exists.
         * 
         * @var boolean
         */
        public $overwrite       = FALSE;
        
        /**
         * The maximum size (in kilobytes) that the file can be. Set to zero 
         * for no limit. Note: Most PHP installations have their own limit, 
         * as specified in the php.ini file. Usually 2 MB (or 2048 KB) by default.
         * 
         * @var number
         */
        public $max_size        = 0;
        
        /**
         * The maximum width (in pixels) that the file can be. Set to zero for 
         * no limit.
         * 
         * @var number
         */
        public $max_width       = 0;
        
        /**
         * The maximum height (in pixels) that the file can be. Set to zero for 
         * no limit.
         * 
         * @var number
         */
        public $max_height      = 0;
        
        /**
         * The maximum length that a file name can be. Set to zero for no limit.
         * 
         * @var number
         */
        public $max_filename    = 0;
        
        /**
         * If set to TRUE the file name will be converted to a random encrypted 
         * string. This can be useful if you would like the file saved with a 
         * name that can not be discerned by the person uploading it.
         * 
         * @var boolean
         */
        public $encrypt_name    = FALSE;
        
        /**
         * If set to TRUE, any spaces in the file name will be converted to 
         * underscores. This is recommended.
         * 
         * @var boolean
         */
        public $remove_spaces   = TRUE;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{             
                $this->data['errors'] = NULL;
                $this->_render('admin/upload_form');
	}

	function do_upload()
	{
		$config['upload_path']      = $this->upload_path;
		$config['allowed_types']    = $this->allowed_types;
                $config['file_name']        = $this->file_name;
                $config['overwrite']        = $this->overwrite;
		$config['max_size']         = $this->max_size;
		$config['max_width']        = $this->max_width;
		$config['max_height']       = $this->max_height;
                $config['max_filename']     = $this->max_filename;
                $config['encrypt_name']     = $this->encrypt_name;
                $config['remove_spaces']    = $this->remove_spaces;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{			
                        $this->data['errors'] = $this->upload->display_errors();
			$this->_render('admin/upload_form');
		}
		else
		{
			$this->data['upload_data'] = $this->upload->data();
                        $this->data['errors'] = NULL;
                        $this->_render('admin/upload_success');
		}
	}
}