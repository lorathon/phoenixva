<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PVA Application base controller.
 *
 * Ensures the database is current with the application and automatically
 * handles login redirection and admin checks. Private areas of the application
 * should have a URL like domain.com/private/ and admin areas should have a URL
 * like domain.com/admin/. All other URLs will be treated as public.
 *
 * General controller usage should be to instantiate and populate models, set the
 * necessary values in the $data array, and then call $this->_render('view_name');
 * where 'view_name' = the view to render. This will automatically render the
 * view within the appropriate template.
 *
 * @author Chuck Topinka
 *
*/
class PVA_Controller extends CI_Controller {

	// Holds the data for display by the view
	public $data     = array();
	
	// Used to enable/disable the profiler (can be overriden by child controllers)
	protected $_profile_this = FALSE;
	
	// Controls which template to render
	private $_access = 'public';

	function __construct()
	{
		parent::__construct();

		// Styling and profiling
		if (ENVIRONMENT == 'development')
		{
			// Profiler is always on in development
			$this->_profile_this = TRUE;
		}
		$this->output->enable_profiler($this->_profile_this);

		log_message('debug', 'PVA Controller class initialized');

		// Register autoloader
		spl_autoload_register(array('PVA_Controller','_autoload'));

		// Load PVA config file XXX This should probably not be loaded here.
		$this->load->config('pva_config');
		
		// Session required
		$this->load->library('session');
		$this->data['userdata'] = $this->session->all_userdata();
		
		// Holds view specific css
		$this->data['stylesheets'] = array();
		
		// Holds view specific javascripts 
		$this->data['scripts'] = array();
                
                $this->data['js_templates'] = array();
		
		// Holds errors for display by the view
		$this->data['errors'] = array();
		
		// holds alerts for display by the view (inner method)
		$this->data['alert'] = array();
		
		// Set defaults so controllers can override later
		$this->data['site_name'] = config_item('site_name');
		$this->data['meta_title'] = config_item('site_name');
		$this->data['meta_description'] = config_item('site_description');

		// Public, private or admin access
		$access = $this->uri->segment(1);

		if ($access == 'admin' OR $access == 'private')
		{			
			// Verify user logged in
			$this->load->library('tank_auth');

			if ( ! $this->tank_auth->is_logged_in())
			{
				// User not logged in so redirect to login
				$this->load->helper('url');
				$this->session->set_flashdata('return_url',uri_string());
				redirect('/auth/login/');
			}

			/* XXX Is this even used?
			 * FRONT END ALERT
			 * If a session message is set shove into main data array
			 * Change to read ('item', 'value') or an array?
			 * OPTIONS:
			 * $this->session->set_flashdata('success', 'MESSAGE'); Green
			 * $this->session->set_flashdata('warning', 'MESSAGE'); Amber
			 * $this->session->set_flashdata('danger', 'MESSAGE'); Red
			 * $this->session->set_flashdata('info', 'MESSAGE'); Blue
			 */

			// ADMIN Alert
			if($this->session->flashdata('alert_message') != '')
			{
				$this->data['alert'] = TRUE;
				$this->data['alert_type'] = $this->session->flashdata('alert_type');
				$this->data['alert_message'] = $this->session->flashdata('alert_message');
			}
			else
			{
				$this->data['alert'] = FALSE;
			}
                        
		}

		if ($access == 'admin')
		{
			$this->_check_access($access);

			// Load Admin config file
			$this->load->config('admin/admin_config');

			// Indicate admin area
			$this->data['site_name']  .= ' - Admin';
			$this->data['meta_title'] .= ' - Admin';
			$this->_access = 'admin';
		}
		elseif ($access == 'private')
		{
			// Any common stuff for private access goes here.
			$this->_access = 'private';
		}
	}
	
	/**
	 * Handles Datatable POST
	 * 
	 * @param $model Optional Model object to use with this request.
	 */
	public function datatable($model = NULL)
	{
	    log_message('debug', 'Datatable POST:');
	    log_message('debug', print_r($_POST, TRUE));
	    
	    // Turn off profiling since the return is JSON
	    $this->output->enable_profiler(FALSE);
	    
	    $this->load->library('form_validation');
	    
	    if ($this->form_validation->run('datatables'))
	    {
	        if (is_null($model))
	        {
	            $class = get_class($this);
	            $model_name = substr($class, 0, strlen($class)-1);
	            $model = new $model_name();
	        }
	        
	        echo $model->get_datatable();
	        return;
	    }
	    log_message('debug', 'Form validation failed.');
	    (isset($_POST['draw'])) ? $draw = intval($_POST['draw']) : $draw = 1;
	    $error = array(
	        'draw' => $draw,
	        'recordsTotal' => 0,
	        'recordsFiltered' => 0,
	        'data' => NULL,
	        'error' => 'Improper datatable request sent.'
	    );
	    echo json_encode($error);
	}
	
	/**
	 * Checks that the user has sufficient privileges
	 * 
	 * Redirects to the unauthorized screen if they don't.
	 * @param string $access the minimum level required.
	 */
	protected function _check_access($access)
	{
		$access = 'is_'.$access; 
		if( ! isset($this->data['userdata'][$access]) OR ! $this->data['userdata'][$access])
		{
			// Insufficient access.  Redirect to UNAUTH page
			$this->load->helper('url');
			redirect('/auth/unauth/');
		}
	}
	
	/**
	 * Gets the notes for the provided entity type and ID.
	 * 
	 * @param string $type the entity type (e.g. 'user', 'article', etc.)
	 * @param number $id the ID of the entity
	 * @param boolean $private TRUE if private notes should be returned
	 * @return array of note objects
	 */
	protected function _get_notes($type, $id, $private = FALSE)
	{
		$out = array();
		$note_model = new Note();
		$note_model->entity_type = $type;
		$note_model->entity_id = $id;
		$note_model->private_note = $private;
		$notes = $note_model->get_notes();
		if ($notes)
		{
			$this->load->helper('html');
			foreach ($notes as $note)
			{
				$note_user = $note->get_user();
				$note->name = pva_id($note_user->id) . ' ' . $note_user->name;
				$out[] = $note;
			}
		}
		return $out;
	}

	/**
	 * Renders HTML views as part of overall template
	 *
	 * This method should be called to display a view to ensure it is wrapped in
	 * the template as appropriate. If an array of views is loaded, the views
	 * will be rendered in the order set in the array.
	 *
	 * @param string|array $view The view(s) to render. Optional. If not provided,
	 * a view with the same name as the current controller will be rendered.
	 */
	protected function _render($view = NULL)
	{
		// Default view to current class
		if (is_null($view)) $view = strtolower(get_class($this));
		
		// Load helpers
		$this->load->helper('url');
		
		// No cacheing
		$this->_no_cache();
                
                // Load Javascript code at the bottom of the page if it exists
                if (file_exists(APPPATH . 'views/js/' . $view . '.php') && is_file(APPPATH . 'views/js/' . $view . '.php'))
                {
                    $this->data['js_templates'][] = $this->load->view('js/' . $view . '.php', '',TRUE);
                }
                
                // Get the view(s) and place in view_output
		if (is_array($view))
		{
			foreach ($view as $subview)
			{
				log_message('debug', 'Rendering '.$subview);
				$this->data['view_output'] .= $this->load->view($view, $this->data, TRUE);
			}
		}
		else
		{
			log_message('debug', 'Rendering '.$view);
			$this->data['view_output'] = $this->load->view($view, $this->data, TRUE);
		}
		                
		// Render the appropriate template
		if ($this->_access == 'admin')
		{
			// Load the admin template
			log_message('debug', 'Using admin template.');
			$this->load->view('templates/admin', $this->data);
		}
		else
		{
			// Load the site template
			log_message('debug', 'Using site template.');
			
			// Set title if not set already
			if ( ! array_key_exists('title', $this->data)) $this->data['title'] = $view;
			$this->load->view('templates/pva', $this->data);
		}
	}
	
	/**
	 * Sets response headers so browsers do not cache the output.
	 */
	protected function _no_cache()
	{
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
	}
	
	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string Type of email to send. Corresponds to the views/email/type
	 * files for both -html and -txt.
	 * @param	string Email address to send to.
	 * @param   string Subject for the email.
	 * @param	array Data for the email view to use.
	 * @return	boolean TRUE if the email was queued for delivery.
	 */
	protected function _send_email($type, $email, $subject, &$data)
	{
		log_message('debug', 'Sending email of type: '.$type);
		$this->load->library('email');
		$this->load->helper('url');
		$this->email->from(config_item('webmaster_email'), config_item('site_name'));
		$this->email->reply_to(config_item('webmaster_email'), config_item('site_name'));
		$this->email->to($email);
		$this->email->subject($subject);
		
		$this->data['subject'] = $subject;
		$this->data['view_output_html'] = $this->load->view('email/'.$type.'-html', $data, TRUE);
		$this->data['view_output_txt'] = $this->load->view('email/'.$type.'-txt', $data, TRUE);
		
		$this->email->message($this->load->view('templates/email-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('templates/email-txt', $data, TRUE));
		log_message('debug', 'Email object: '.print_r($this->email, TRUE));
		
		return $this->email->send();
	}

	/**
	 * PVA Application autoloader
	 *
	 * Allows calling objects without having to load models first.
	 * @param string $class The class name to load.
	 */
	protected function _autoload($class)
	{
		if (substr($class,0,4) == 'PVA_')
		{
			log_message('debug', 'Autoloading PVA core class '.$class);
			$file = APPPATH.'core/'.$class.'.php';
			$this->load_file($file);
		}
		elseif (substr($class,0,3) != 'CI_')
		{
			log_message('debug', 'Autoloading '.$class);
			$path = array('models','libraries');
			foreach ($path as $dir)
			{
				$file_class = $class;
				if ($dir == 'models')
				{
					$file_class = strtolower($class);
				}
				$file = APPPATH.$dir.'/'.$file_class.'.php';
				log_message('debug', 'Looking for file '.$file);
				if ($this->load_file($file))
				{
					break;
				}
			}
		}
		else 
		{
			log_message('debug', 'Autoloading CI core class '.$class);
			$file = BASEPATH.'core/'.substr($class,3).'.php';
			$this->load_file($file);
		}
	}
	
	private function load_file($file)
	{
		if (file_exists($file) && is_file($file))
		{
			log_message('debug', 'Autoloading file '.$file);
			@include_once($file);
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Creates user alerts in a consistent format.
	 * 
	 * @param string $msg The message to display
	 * @param string $type Type of message: info (default), primary, success, warning, danger
	 * @param boolean $flash TRUE if the message should persist in the session
	 */
	protected function _alert($msg, $type = 'info', $flash = FALSE)
	{
		$alert = array('type' => $type, 'msg' => $msg);
		$this->data['alerts'][] = $alert;
		if ($flash)
		{
			$this->session->set_flashdata('alerts', $this->data['alerts']);
		}
	}
	
	/**
	 * Creates flash data for messaging in a consistent format.
	 * 
	 * @deprecated Use _alert($msg, $type, $flash) instead
	 * @param string $type Type of message: info (default), primary, success, warning, danger
	 * @param string $title Title of the message
	 * @param string $msg The message itself
	 */
	protected function _flash_message($type = 'info', $title = '', $msg = '')
	{
		$this->session->set_flashdata('msg_type', $type);
		$this->session->set_flashdata('title', $title);
		$this->session->set_flashdata('message', $msg);
	}
	
	/**
	 * Creates data for inner controller messaging in a consistent format.
	 * 
	 * @deprecated Use _alert($msg, $type, $flash) instead
	 * @param string $type Type of message: info (default), primary, success, warning, danger
	 * @param string $msg The message itself
	 */
	protected function _alert_message($type = 'info', $msg = '')
	{
		$this->data['alert']['type'] = $type;
		$this->data['alert']['msg'] = $msg;
	}
}

/* End of file PVA_Controller.php */
/* Location: ./application/core/PVA_Controller.php */
