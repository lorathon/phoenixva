<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/** MODIFIED
 * 10-11-2013
 * function: _show_message
 * Change Redirect to ''
 * BY: Jeffrey F. Kobus
*/

class Auth extends PVA_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
	}

	function index()
	{
		redirect('/auth/login/');
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('private/profile');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$this->data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$this->data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			$this->data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
			$max_login_exceeded = $this->tank_auth->is_max_login_attempts_exceeded($login);
			if ($max_login_exceeded) {
				if ($this->data['use_recaptcha'])
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				else
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
			}
			$this->data['errors'] = array();

			if ($this->form_validation->run()) 
			{
				// Validation OK
				$user = new User();
				$user->username = $this->form_validation->set_value('login');
				$user->password = $this->form_validation->set_value('password');
				$user->last_ip = $this->input->ip_address();
				if ($user->login()) 
				{
					// Login success
					$this->session->set_userdata(array(
							'user_id'  => $user->id,
							'username' => $user->username,
							'status'   => $user->activated,
							'admin'    => $user->admin_level,
							'name'     => $user->name,
							'rank'     => $user->rank_id,
							'hub'      => $user->hub,
							));
					
					$this->session->set_flashdata('title', 'Welcome');
					$this->session->set_flashdata('message', 'You have successfully logged into your account.');
                    redirect('private/profile');

				} 
				else 
				{
					$errors = array('Unable to log in.');
					
					if ($user->banned)
					{
						$errors[] = 'You are not able to log in at this time.';
					}
					
					if ( ! $user->activated)
					{
						$errors[] = 'Login information does not match an activated user.';
					}
					$this->data['errors'] = $errors;				
				}
			}
			$this->data['show_captcha'] = FALSE;
			if ($max_login_exceeded) {
				$this->data['show_captcha'] = TRUE;
				if ($this->data['use_recaptcha']) {
					$this->data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$this->data['captcha_html'] = $this->_create_captcha();
				}
			}
			
			$this->data['title'] = 'Sign In';
            $this->data['meta_title'] = config_item('site_name');            
            $this->_render('auth/login_form');
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();
		$this->_show_message('info', $this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('private/profile');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} elseif (!$this->config->item('allow_registration', 'tank_auth')) {	// registration is off
			$this->_show_message('error', $this->lang->line('auth_message_registration_disabled'));

		} else {
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('location', 'Location', 'trim|required|xss_clean');
			$this->form_validation->set_rules('crew_center', 'Crew Center', 'trim|required|xss_clean');
			$this->form_validation->set_rules('transfer_hours', 'Transfer Hours', 'trim|xss_clean|numeric|less_than[150.01]');
			$this->form_validation->set_rules('transfer_link', 'Transfer Link', 'trim|xss_clean');
			$this->form_validation->set_rules('heard_about', 'Heard About', 'trim|xss_clean');
						
			$captcha_registration	= $this->config->item('captcha_registration', 'tank_auth');
			$use_recaptcha			= $this->config->item('use_recaptcha', 'tank_auth');
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			$this->data['errors'] = array();

			$email_activation = $this->config->item('email_activation', 'tank_auth');

			if ($this->form_validation->run()) 
			{
				// validation ok, create user object
				$user = new User();
				$user->name = $this->form_validation->set_value('first_name').' '.$this->form_validation->set_value('last_name');
				$user->email = $this->form_validation->set_value('email');
				$user->username = $user->email;
				$user->password = $this->form_validation->set_value('password');
				$user->birthday = $this->form_validation->set_value('birth_date');
				$user->last_ip = $this->input->ip_address();
				$user->hub = $this->form_validation->set_value('crew_center');
				$user->transfer_link = $this->form_validation->set_value('transfer_link');
				$user->heard_about = $this->form_validation->set_value('heard_about');
				
				// Set user profile info
				$user_profile = $user->get_user_profile();
				$user_profile->location = $this->form_validation->set_value('location');
				$user->set_user_profile($user_profile);
				
				// Set user stat info
				$user_stats = $user->get_user_stats();
				$user_stats->hours_transfer = $this->form_validation->set_value('transfer_hours');
				$user->set_user_stats($user_stats);
				
				if ( ! $user->create() === FALSE) 
				{
					// User created

					// Set values for the views
					$this->load->helper('html');
					$this->data['title'] = 'Application Submitted';
					$this->data['site_name'] = $this->config->item('website_name', 'tank_auth');
					$this->data['user_id'] = pva_id($user->id);
					$this->data['username'] = $user->username;
					$this->data['email'] = $user->email;
					$this->data['new_email_key'] = $user->new_email_key;
					$this->data['transfer_link'] = $user->transfer_link;
					$this->data['transfer_hours'] = $user_stats->hours_transfer;
					

					if ($email_activation) 
					{
						// Send activate email
						$this->data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

						$this->_send_email('activate', $user->email, $this->data);

						$this->_render('auth/register_result');

					} 
					else 
					{
						if ($this->config->item('email_account_details', 'tank_auth')) 
						{
							// Send welcome email
							$this->_send_email('welcome', $user->email, $this->data);
						}

						$this->_show_message('info', $this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
					}
				} 
				else 
				{
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$this->data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$this->data['captcha_html'] = $this->_create_captcha();
				}
			}
			
			// Get hub list for desired crew center
			$this->data['hubs'] = array();
			$airport = new Airport();
			if ($hubs = $airport->find_hubs())
			{
				$this->data['hubs'][''] = 'Select one:';
				foreach ($hubs as $hub)
				{
					$this->data['hubs'][$hub->id] = $hub->icao.' - '.$hub->name;
				}
			}
			
			$this->data['title'] = 'Join Phoenix Virtual Airways';
			$this->data['use_username'] = $use_username;
			$this->data['captcha_registration'] = $captcha_registration;
			$this->data['use_recaptcha'] = $use_recaptcha;
            $this->data['meta_title'] = config_item('site_name');            
            $this->_render('auth/register_form');
		}
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$this->data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($this->data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$this->data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$this->data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $this->data['email'], $this->data);

					$this->_show_message('info', sprintf($this->lang->line('auth_message_activation_email_sent'), $this->data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/send_again_form', $this->data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate($id, $email_key)
	{
		$user = new User();
		$user->id = $id;
		$user->new_email_key = $email_key;

		// Activate user
		if ($user->activate()) 
		{
			// TODO Notify staff
			
			// success
			$this->tank_auth->logout();
			$this->session->set_flashdata('title', 'Activation Successful');
			$this->session->set_flashdata('message', $this->lang->line('auth_message_activation_completed'));
			redirect('auth/login');
		} 
		else 
		{
			// fail
			$this->session->set_flashdata('title', 'Activation Error');
			$this->session->set_flashdata('message', $this->lang->line('auth_message_activation_failed'));
			redirect('');
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$this->data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($this->data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$this->data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->_send_email('forgot_password', $this->data['email'], $this->data);

					$this->_show_message('info', $this->lang->line('auth_message_new_password_sent'));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			//$this->load->view('auth/forgot_password_form', $this->data);
            $this->data['meta_title'] = config_item('site_name');            
            $this->data['subview'] = 'auth/forgot_password_form';
            $this->load->view('_layout_modal', $this->data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password()
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$this->data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($this->data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$this->data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				$this->_send_email('reset_password', $this->data['email'], $this->data);

				$this->_show_message('success', $this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));

			} else {														// fail
				$this->_show_message('error', $this->lang->line('auth_message_new_password_failed'));
			}
		} else {
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message('error', $this->lang->line('auth_message_new_password_failed'));
			}
		}
		$this->load->view('auth/reset_password_form', $this->data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$this->data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->_show_message('success', $this->lang->line('auth_message_password_changed'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_password_form', $this->data);
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$this->data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($this->data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$this->data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $this->data['new_email'], $this->data);

					$this->_show_message('info', sprintf($this->lang->line('auth_message_new_email_sent'), $this->data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_email_form', $this->data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message('success', $this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message('error', $this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$this->data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message('info',$this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$this->data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/unregister_form', $this->data);
		}
	}

	/**
	 * Show info message
	 * 
	 * @param string - success | info | warning | error
	 * @param	string
	 * @return	void
	 */
	function _show_message($status, $message)
	{        
		$this->session->set_flashdata($status, $message);
		redirect('');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */