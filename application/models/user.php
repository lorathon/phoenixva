<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User model
 * 
 * Provides all business logic for working with users (i.e. pilots) in the system.
 * Borrows some functions from tank_auth.
 * 
 * @author Chuck
 *
 */
class User extends PVA_Model {
	
	/* Database tables */
	private $user_table         = 'users';
	private $user_profile_table = 'user_profiles';
	private $user_stats_table   = 'user_stats';
	
	/* Default user properties */
	public $username = NULL;
	public $name = NULL;
	public $email = NULL;
	public $birthday = NULL;
	public $password = NULL;
	public $activated = NULL;
	public $status    = NULL;
	public $banned    = NULL;
	public $ban_reason = NULL;
	public $new_password_key = NULL;
	public $new_password_requested = NULL;
	public $new_email = NULL;
	public $new_email_key = NULL;
	public $last_ip = NULL;
	public $last_login = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $admin_level = NULL;
	public $rank_id = NULL;
	public $hub = NULL;
	public $transfer_link = NULL;
	public $heard_about = NULL;
	
	/* Related objects */
	protected $_user_profile = NULL;
	protected $_user_stats = NULL;
	
	function __construct()
	{
		parent::__construct();
		
		// Create empty related objects
		$this->_user_profile = new User_profile();
		$this->_user_stats   = new User_stats();
		
		// Set default order
		$this->_order_by = 'name asc';
	}
	
	/**
	 * Gets the user profile associated with this user object.
	 * 
	 * The user object must be populated separately. Normal usage would be:
	 * $user = new User();
	 * $user->id = 123;
	 * $user->find();
	 * $user->get_user_profile();
	 * 
	 * @return object User_profile object for the populated user
	 */
	function get_user_profile()
	{
		if ( ! is_null($this->id) && is_null($this->_user_profile->user_id))
		{
			// Populate user profile object
			$this->_user_profile->user_id = $this->id;
			$this->_user_profile->find();
		}
		return $this->_user_profile;
	}
	
	/**
	 * Gets the user stats associated with this user object.
	 * 
	 * The user object must be populated separately. Normal usage would be:
	 * $user = new User();
	 * $user->id = 123;
	 * $user->find();
	 * $user->get_user_profile();
	 * 
	 * @return object User_stats object for the populated user
	 */
	function get_user_stats()
	{
		if ( ! is_null($this->id) && is_null($this->_user_stats->user_id))
		{
			// Populate user stats object
			$this->_user_stats->user_id = $this->id;
			$this->_user_stats->find();
		}
		return $this->_user_stats;
	}
	
	/**
	 * Sets the user profile for this user.
	 * 
	 * The user profile object is expected to be fully populated.
	 * @param User_profile $profile
	 */
	function set_user_profile($profile)
	{
		$this->_user_profile = $profile;
	}
	
	/**
	 * Sets the user stats for this user.
	 * 
	 * The user stats object is expected to be fully populated.
	 * @param User_stats $stats
	 */
	function set_user_stats($stats)
	{
		$this->_user_stats = $stats;
	}
	
	/**
	 * Creates a new user in the system
	 * 
	 * New users are not automatically activated or created in all required
	 * databases. Use User->activate_user() for that.
	 * 
	 * @return int|bool id of the created user on success or FALSE on failure.
	 */
	function create()
	{
		// Set the time
		$this->created = date('Y-m-d H:i:s');
		$this->modified = $this->created;
		
		// Set the email activation code
		$this->new_email_key = md5(rand().microtime());
		
		// Set the default rank
		$this->rank_id = 1;
		
		// Hash the password
		$this->_hash_password();
		
		// Massage data
		$this->email = strtolower($this->email);
		$this->name = $this->_set_name($this->name);
		
		// Prep the data
		$user_parms = $this->_prep_data();
		
		// Do these creates in a transaction so the user is entirely created.
		$this->db->trans_start();
		
		// Create the master user record
		$this->db->insert($this->user_table, $user_parms);
		
		// Use the user id from the previous insert
		$this->id = $this->db->insert_id();
		$this->_user_profile->user_id = $this->id;
		$this->_user_stats->user_id = $this->id;
		
		// Prep sub table data
		$prof_parms = $this->_user_profile->_prep_data();
		$stat_parms = $this->_user_stats->_prep_data();
		
		// Create child records
		$this->db->insert($this->user_profile_table, $prof_parms);
		$this->db->insert($this->user_stats_table, $stat_parms);
		
		// Transaction complete
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return $this->id;
	}
	
	/**
	 * Saves a user
	 * 
	 * This method can only be used for updating a user. If saving a new user,
	 * use the create() method instead.
	 * 
	 * @see PVA_Model::save()
	 * @see User::create()
	 * @return bool FALSE if trying to create a user.
	 */
	function save()
	{
		// Can only be used for updating
		if (is_null($this->id))
		{
			return FALSE;
		}
		parent::save();
	}
	
	/**
	 * Deletes a user from the system
	 * 
	 * Unlike creating a user, deleting a user will permanently remove them from
	 * all databases. This cannot be undone.
	 */
	function delete()
	{
		// Users can't be deleted at the moment.
		return FALSE;
	}
	
	/**
	 * Activates a user so they can login
	 * 
	 * Activates the user in the main database and creates their user accounts
	 * in other required databases.
	 * 
	 * @return boolean TRUE if user was activated or FALSE if there was a problem.
	 */
	function activate()
	{
		// Populate the user if we have a match
		if ($this->find())
		{
			// User found, activate if not activated or banned
			if ( ! $this->activated && ! $this->banned)
			{
				$this->activated = 1;
				$this->new_email_key = '';
				$this->status = 1;
				$this->save();
				
				// TODO Create user in other systems
			}
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Verifies the user for login.
	 * 
	 * It's important to note that this does not create sessions or anything. That
	 * is left up to the controller/application. If the user is logged in successfully,
	 * the user object will be fully populated.
	 * 
	 * @return boolean TRUE if user logged in or FALSE if information does not match.
	 */
	function login()
	{
		// Get clear password from the object
		$pass_clear = $this->password;
		
		// Set the clear password to null
		$this->password = NULL;
		
		// Populate if we have a match
		$this->find();
		
		// Validate		
		if ($this->activated && ! $this->banned && $this->_verify_password($pass_clear))
		{
			// Populate sub tables
			$this->_user_profile->user_id = $this->id;
			$this->_user_stats->user_id = $this->id;
			$this->_user_profile->find();
			$this->_user_stats->find();
			
			// Set last login
			$this->last_login = date('Y-m-d H:i:s');
			$this->save();
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Bans a user from accessing the system.
	 * 
	 * Banned users cannot login and cannot re-register.
	 */
	function ban()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->banned = 1;
		if (is_null($this->ban_reason))
		{
			$this->ban_reason = 'No reason given';
		}
		
		$this->save();
	}
	
	/**
	 * Unbans a user.
	 * 
	 * Allows a previously banned user to login again.
	 */
	function unban()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->banned = 0;
		$this->ban_reason = '';
		
		$this->save();
	}
	
	/**
	 * Places a user on leave of absence.
	 * 
	 * Users on a leave of absence (LOA) can re-activate themselves by filing a 
	 * PIREP or logging in and marking themselves as being off LOA.
	 */
	function loa()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->status = 4;
		$this->save();
	}
	
	/**
	 * Retires a user.
	 * 
	 * Retired users cannot login and must be re-activated by an administrator.
	 */
	function retire()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->status = 5;
		$this->save();
	}
	
	/**
	 * Issues a formal warning for the user.
	 * 
	 * Warning a user keeps a count so admins can decide whether to ban. Usually
	 * a warning can be issued by any staff member but only executive staff can
	 * issue a ban.
	 */
	function warn()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->status = 2;
		$this->save();
	}
	
	/**
	 * Changes a user's password.
	 * 
	 * The old password is required for validation purposes. User object should
	 * be populated with id and new password.
	 * 
	 * @param string $old_pass
	 * @return boolean FALSE if there was an error changing the password.
	 */
	function change_password($old_pass)
	{		
		if ( ! is_null($this->id) && $this->_verify_password($old_pass))
		{
			$this->_hash_password();
			$this->save();
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	private function _hash_password()
	{
		if (is_null($this->password)) return FALSE;
		
		$this->benchmark->mark('password_hash_start');
		if (PHP_VERSION_ID < 50500)
		{
			// Get password helper if PHP version less than 5.5.0
			$this->load->helper('password');
		}
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		$this->benchmark->mark('password_hash_end');
	}
	
	private function _verify_password($clear_pass)
	{
		if (is_null($this->password)) return FALSE;
		
		$this->benchmark->mark('password_verify_start');
		if (PHP_VERSION_ID < 50500)
		{
			// Get password helper if PHP version less than 5.5.0
			$this->load->helper('password');
		}
		return password_verify($clear_pass, $this->password);
	}
	
	private function _set_name($name)
	{
		$string =ucwords(strtolower($name));
		
		foreach (array('-', '\'') as $delimiter) 
		{
			if (strpos($string, $delimiter)!==false) 
			{
				$string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
			}
		}
		return $string;
	}
}

/**
 * User_profile object is essentially a sub of the User model.
 * 
 * The user profile contains information the user is allowed to update themselves.
 * @author Chuck
 *
 */
class User_profile extends PVA_Model {
	
	/* Default Properties */
	public $user_id = '';
	public $location = '';
	public $avatar = '';
	public $background_sig = '';
	public $modified = NULL;
	
	function __construct($user_id = NULL)
	{
		parent::__construct();
		$this->user_id = $user_id;
	}
}

/**
 * User_stats object is essentially a sub of the User model.
 * 
 * User stats are usually calculated by the system as a result of filings and
 * administrative actions.
 * 
 * @author Chuck
 *
 */
class User_stats extends PVA_Model {
	
	/* Default properties */
	public $user_id = '';
	public $total_pay = 0;
	public $pay_adjustment = 0;
	public $airlines_flown = 0;
	public $aircraft_flown = 0;
	public $airports_landed = 0;
	public $fuel_used = 0;
	public $total_landings = 0;
	public $total_gross = 0;
	public $total_expenses = 0;
	public $flights_early = 0;
	public $flights_ontime = 0;
	public $flights_late = 0;
	public $flights_manual = 0;
	public $flights_rejected = 0;
	public $hours_flights = 0;
	public $hours_transfer = 0;
	public $hours_adjustment = 0;
	public $hours_type_rating = 0;
	public $current_location = '';
	public $modified = NULL;
	
	function __construct($user_id = NULL)
	{
		parent::__construct();
		
		$this->user_id = $user_id;
	}
	
	/**
	 * Total flights for the user.
	 * 
	 * @return number
	 */
	function total_flights()
	{
		return $this->flights_early + $this->flights_late + $this->flights_ontime;
	}
	
	/**
	 * Total hours for the user.
	 * 
	 * @return number
	 */
	function total_hours()
	{
		return $this->hours_flights + $this->hours_transfer + $this->hours_adjustment;
	}
}