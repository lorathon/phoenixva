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
	public $username               = NULL;
	public $name                   = NULL;
	public $email                  = NULL;
	public $birthday               = NULL;
	public $password               = NULL;
	public $activated              = NULL;
	public $status                 = NULL;
	public $banned                 = NULL;
	public $ban_reason             = NULL;
	public $new_password_key       = NULL;
	public $new_password_requested = NULL;
	public $new_email              = NULL;
	public $new_email_key          = NULL;
	public $last_ip                = NULL;
	public $last_login             = NULL;
	public $created                = NULL;
	public $modified               = NULL;
	public $retire_date            = NULL;
	public $admin_level            = NULL;
	public $rank_id                = NULL;
	public $hub                    = NULL;
	public $hub_transfer           = NULL;
	public $transfer_link          = NULL;
	public $heard_about            = NULL;
	public $ipbuser_id             = NULL;
	
	/* Related objects */
	protected $_user_profile = NULL;
	protected $_user_stats   = NULL;
	
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
	 * $user->get_user_stats();
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
	 * Populates user object based on legacy data
	 * 
	 * The email must be populated on the user object prior to calling this method.
	 * This is likely to be a resource intensive method due to multiple queries
	 * on the legacy database.
	 * 
	 * @return boolean FALSE if email is not provided or legacy user is not found or banned
	 */
	function populate_legacy()
	{
		if (is_null($this->email))
		{
			return FALSE;
		}
		
		// Connect to the legacy database
		$db_legacy = $this->load->database('legacy', TRUE);
		
		// Find the legacy user
		$db_legacy->select('pilotid, firstname,	lastname, location,	currlocation
				birthday, bgimage, sigfontcolor, totalhours, totalpay, transferhours,
				rankid, joindate, lastpirep, bonushours, trhours, payadjust, stat_airlines, stat_aircraft,
				stat_airports');
		$db_legacy->from('phpvms_pilots');
		$db_legacy->where('retired !=', 2);
		$db_legacy->where('email', $this->email);
		$db_legacy->limit(1);
		
		$query = $db_legacy->get();
		$row = $query->row();
		
		if (is_null($row->pilotid))
		{
			// No legacy user to transfer
			return FALSE;
		}
		
		// Populate the user object using legacy data
		$this->id = $row->pilotid;
		$this->name = $row->firstname.' '.$row->lastname;
		$this->username = $this->email;
		$this->birthday = $row->birthday;
		$this->status = 3;
		$this->created = $row->joindate;
		$this->rank_id = $row->rankid;
		$this->transfer_link = 'http://phoenixva.org/index.php/profile/view/'.$row->pilotid;
		$this->heard_about = 'Legacy pilot transferred '.date('Y-m-d');
		
		// Populate the user profile object using legacy data
		$this->_user_profile->user_id = $this->id;
		$this->_user_profile->location = $row->location;
		$this->_user_profile->background_sig = $row->bgimage;
		$this->_user_profile->sig_color = $row->sigfontcolor;
		$this->_user_profile->avatar = 'http://www.phoenixva.org/lib/avatars/PVA'.str_pad($this->id, 4, 0, STR_PAD_LEFT).'.png';
		
		// Populate the user stats object using legacy data
		$this->_user_stats->user_id = $this->id;
		$this->_user_stats->aircraft_flown = $row->stat_aircraft;
		$this->_user_stats->airlines_flown = $row->stat_airlines;
		$this->_user_stats->airports_landed = $row->stat_airports;
		$this->_user_stats->current_location = $row->currlocation;
		$this->_user_stats->hours_flights = $this->_hours_to_mins($row->totalhours);
		$this->_user_stats->hours_transfer = $this->_hours_to_mins($row->transferhours);
		$this->_user_stats->hours_adjustment = $this->_hours_to_mins($row->bonushours);
		$this->_user_stats->hours_type_rating = $this->_hours_to_mins($row->trhours);
		$this->_user_stats->total_pay = $row->totalpay;
		$this->_user_stats->pay_adjustment = $row->payadjust;
		
		// Calculate totals
		$db_legacy->select('sum(landingrate) as totallandings, sum(fuelused) as totalfuel,
				sum(gross) as gross, sum(expenses) as expenses');
		$db_legacy->from('phpvms_pireps');
		$db_legacy->where('accepted', 1);
		$db_legacy->where('pilotid', $this->id);
		
		$query = $db_legacy->get();

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$this->_user_stats->fuel_used = $row->totalfuel;
			$this->_user_stats->total_expenses = $row->expenses;
			$this->_user_stats->total_gross = $row->gross;
			$this->_user_stats->total_landings = abs($row->totallandings);
		}
		
		// Calculate ontime stats
		for ($n = 1; $n < 4; $n++)
		{
			$db_legacy->select('count(*) as flights');
			$db_legacy->from('phpvms_pireps');
			$db_legacy->where('ontime', $n);
			$db_legacy->where('accepted', 1);
			$db_legacy->where('pilotid', $this->id);
			
			$query = $db_legacy->get();
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row();
				switch ($n)
				{
					case 1:
						$this->_user_stats->flights_ontime = $row->flights;
						break;
					case 2:
						$this->_user_stats->flights_late = $row->flights;
						break;
					case 3:
						$this->_user_stats->flights_early = $row->flights;
						break;
				}
			}
		}
		
		// Count manual flights
		$db_legacy->select('count(*) as flights');
		$db_legacy->from('phpvms_pireps');
		$db_legacy->where('source', 'manual');
		$db_legacy->where('accepted', 1);
		$db_legacy->where('pilotid', $this->id);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$this->_user_stats->flights_manual = $row->flights;
		}
		
		// Count rejected flights
		$db_legacy->select('count(*) as flights');
		$db_legacy->from('phpvms_pireps');
		$db_legacy->where('accepted', 0);
		$db_legacy->where('pilotid', $this->id);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$this->_user_stats->flights_rejected = $row->flights;
		}
		
		// Get IP Board user ID
		$db_legacy->select('member_id');
		$db_legacy->from('ipbmembers');
		$db_legacy->where('email', $this->email);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$this->ipbuser_id = $row->member_id;
		}
		return TRUE;
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
	 * databases. Use User->activate() for that.
	 * 
	 * @return int|bool id of the created user on success or FALSE on failure.
	 */
	function create()
	{
		// Set the time
		if (is_null($this->created)) $this->created = date('Y-m-d H:i:s');
		$this->modified = $this->created;
		
		// Set the email activation code
		$this->new_email_key = md5(rand().microtime());
		
		// Set the default rank
		if (is_null($this->rank_id)) $this->rank_id = 1;
		
		// Set the default hub transfer (there is none)
		if (is_null($this->hub_transfer)) $this->hub_transfer = 0;
		
		// Set the default ipbuser_id (not one of these either)
		if (is_null($this->ipbuser_id)) $this->ipbuser_id = 0;
		
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
		
		// Use minutes for transfer hours if not legacy user
		if (is_null($this->id))
		{
			$this->_user_stats->hours_transfer = $this->_hours_to_mins($this->_user_stats->hours_transfer);
		}
		
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

				// TODO Background checking (business logic level)
				
				// Background check ok, activate the user
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
	 * Makes a user fully active.
	 * 
	 * @return boolean FALSE if user id is not populated
	 */
	function make_active()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->status = 3;
		$this->save();
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
		
		// Get IP address from the object
		$curr_ip = $this->last_ip;
		
		// Set the object properties to null for lookup
		$this->password = NULL;
		$this->last_ip = NULL;
		
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
			$this->last_ip = $curr_ip;
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
		$this->status = 7;
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
		$this->status = 2;
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
		$verified = password_verify($clear_pass, $this->password);
		$this->benchmark->mark('password_verify_end');
		 
		return $verified; 
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
	public $user_id = NULL;
	public $location = NULL;
	public $avatar = NULL;
	public $background_sig = NULL;
	public $sig_color = NULL;
	public $bio = NULL;
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
	public $user_id = NULL;
	public $total_pay = NULL;
	public $pay_adjustment = NULL;
	public $airlines_flown = NULL;
	public $aircraft_flown = NULL;
	public $airports_landed = NULL;
	public $fuel_used = NULL;
	public $total_landings = NULL;
	public $landing_softest = NULL;
	public $landing_hardest = NULL;
	public $total_gross = NULL;
	public $total_expenses = NULL;
	public $flights_early = NULL;
	public $flights_ontime = NULL;
	public $flights_late = NULL;
	public $flights_manual = NULL;
	public $flights_rejected = NULL;
	public $hours_flights = NULL;
	public $hours_transfer = NULL;
	public $hours_adjustment = NULL;
	public $hours_type_rating = NULL;
	public $hours_hub = NULL;
	public $current_location = NULL;
	public $last_flight_date = NULL;
	public $modified = NULL;
	
	function __construct($user_id = NULL)
	{
		parent::__construct();
		$this->_table_name = 'user_stats';
		$this->user_id = $user_id;
	}
	
	/**
	 * Total flights for the user.
	 * 
	 * @return number
	 */
	function total_flights()
	{
		return array_sum(array(
				$this->flights_early, 
				$this->flights_late, 
				$this->flights_ontime,
				));
	}
	
	/**
	 * Total hours for the user.
	 * 
	 * @return number
	 */
	function total_hours()
	{
		return array_sum(array(
				$this->hours_flights, 
				$this->hours_transfer, 
				$this->hours_adjustment,
				$this->hours_type_rating,				
				));
	}
}