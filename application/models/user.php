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
	private $user_table = 'users';
	private $user_profile_table = 'user_profiles';
	private $user_stats_table = 'user_stats';
	
	/* Default user properties */
	public $name = '';
	public $email = '';
	public $birthday = '';
	public $password = '';
	public $activated = 0;
	public $status    = 0;
	public $banned    = 0;
	public $ban_reason = '';
	public $new_password_key = '';
	public $new_password_requested = '';
	public $new_email = '';
	public $new_email_key = '';
	public $last_ip = '';
	public $last_login = '';
	public $created = '';
	public $modified = NULL;
	public $admin_level = 0;
	public $rank_id = '';
	public $hub = '';
	public $transfer_link = '';
	public $heard_about = '';
	
	/* Related objects */
	protected $user_profile = NULL;
	protected $user_stats = NULL;
	
	function __construct()
	{
		parent::__construct();
		
		// Create empty related objects
		$this->user_profile = new User_profile();
		$this->user_stats   = new User_stats();
	}
	
	/**
	 * Gets the user profile associated with this user object.
	 * 
	 * The user object must be populated separately.
	 * 
	 * @return User_profile
	 */
	function get_user_profile()
	{
		return $this->user_profile;
	}
	
	/**
	 * Gets the user stats associated with this user object.
	 * 
	 * The user object must be populated separately.
	 * 
	 * @return User_stats
	 */
	function get_user_stats()
	{
		return $this->user_stats;
	}
	
	/**
	 * Sets the user profile for this user.
	 * 
	 * The user profile object is expected to be fully populated.
	 * @param User_profile $profile
	 */
	function set_user_profile($profile)
	{
		$this->user_profile = $profile;
	}
	
	/**
	 * Sets the user stats for this user.
	 * 
	 * The user stats object is expected to be fully populated.
	 * @param User_stats $stats
	 */
	function set_user_stats($stats)
	{
		$this->user_stats = $stats;
	}
	
	/**
	 * Creates a new user in the system
	 * 
	 * New users are not automatically activated or created in all required
	 * databases. Use User->activate_user() for that.
	 * 
	 * @return int|bool id of the created user on success or FALSE on failure.
	 */
	function create_user()
	{
		// Set the time
		$this->created = date('Y-m-d H:i:s');
		
		// Set the email activation code
		$this->new_email_key = md5(rand().microtime());
		
		// Hash the password
		$this->benchmark->mark('password_hash_start');
		if (PHP_VERSION_ID < 50500)
		{
			// Get password library if PHP version less than 5.5.0
			$this->load->library('password');
		}
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		$this->benchmark->mark('password_hash_end');
		
		// Do these creates in a transaction so the user is entirely created.
		$this->db->trans_start();
		
		// Create the master user record
		$this->db->insert($this->user_table, $this);
		
		// Use the user id from the previous insert
		$this->id = $this->db->insert_id();
		$this->user_profile->user_id = $this->id;
		$this->user_stats->user_id = $this->id;
		
		// Create child records
		$this->db->insert($this->user_profile_table, $this->user_profile);
		$this->db->insert($this->user_stats_table, $this->user_stats);
		
		// Transaction complete
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return $this->id;
	}
	
	/**
	 * Deletes a user from the system
	 * 
	 * Unlike creating a user, deleting a user will permanently remove them from
	 * all databases. This cannot be undone.
	 */
	function delete_user()
	{

	}
	
	/**
	 * Activates a user so they can login
	 * 
	 * Activates the user in the main database and creates their user accounts
	 * in other required databases.
	 */
	function activate_user()
	{

	}
	
	/**
	 * Bans a user from accessing the system.
	 * 
	 * Banned users cannot login and cannot re-register.
	 */
	function ban_user()
	{

	}
	
	/**
	 * Places a user on leave of absence.
	 * 
	 * Users on a leave of absence (LOA) can re-activate themselves by filing a 
	 * PIREP or logging in and marking themselves as being off LOA.
	 */
	function loa_user()
	{

	}
	
	/**
	 * Retires a user.
	 * 
	 * Retired users cannot login and must be re-activated by an administrator.
	 */
	function retire_user()
	{

	}
	
	/**
	 * Issues a formal warning for the user.
	 * 
	 * Warning a user keeps a count so admins can decide whether to ban. Usually
	 * a warning can be issued by any staff member but only executive staff can
	 * issue a ban.
	 */
	function warn_user()
	{

	}
	
	/**
	 * Changes a user's password.
	 * 
	 * The old password is required for validation purposes.
	 * @param string $old_pass
	 */
	function change_password($old_pass)
	{
		
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
}