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
	public $waivers_js             = NULL;
	public $waivers_cat            = NULL;
	
	/* Related objects */
	protected $_user_profile        = NULL;
	protected $_user_stats          = NULL;
	protected $_user_awards         = NULL;
	
	/* Derived properties */
	private $_is_premium = NULL;
	private $_is_admin   = NULL;
	private $_is_manager = NULL;
	private $_is_exec    = NULL;
	private $_is_super   = NULL;
	private $_manager_list = NULL;
	
	/* Constants */
	const WAITING = 0;
	const NEWREG = 1;
	const PROBATION = 2;
	const ACTIVE = 3;
	const LOA = 4;
	const FURLOUGH = 5;
	const RESIGNED = 6;
	const BANNED = 7;
	const REJECTED = 8;
	
	const ACCESS_PREMIUM = 10;
	const ACCESS_ADMIN = 50;
	const ACCESS_MANAGER = 60;
	const ACCESS_EXEC = 70;
	const ACCESS_SUPER = 90;
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
		
		// Create empty related objects
		$this->_user_profile = new User_profile();
		$this->_user_stats   = new User_stats();
		$this->_user_awards = new User_award();
		
		// Set default order
		$this->_order_by = 'name asc';
	}
	
	/**
	 * Gets the pay rate for this user.
	 * 
	 * @return number
	 */
	public function get_pay_rate()
	{
		$rank = new Rank($this->rank_id);
		return $rank->pay_rate;
	}
	
	/**
	 * Gets the user profile associated with this user object.
	 * 
	 * The user object must be populated separately. Normal usage would be:
	 * $user = new User($id);
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
	 * $user = new User(123);
	 * $user->get_user_stats();
	 * 
	 * @return object User_stats object for the populated user
	 */
	function get_user_stats()
	{
		if ( ! is_null($this->id)&& is_null($this->_user_stats->user_id))
		{
			// Populate user stats object
			$this->_user_stats->user_id = $this->id;
			$this->_user_stats->find();
		}
		return $this->_user_stats;
	}
        
	/**
	 * Gets the user awards associated with this user object.
	 * 
	 * The user object must be populated separately. Normal usage would be:
	 * $user = new User($id);
	 * $user->get_user_awards();
	 * 
	 * @return object User_awards object for the populated user
	 */
	function get_user_awards()
	{    
		$this->_user_awards->user_id = $this->id;                    
		$this->_user_awards = $this->_user_awards->find_all();
		                
		return $this->_user_awards;
	}
        
	function get_user_awards_by_type($type_id = NULL)
	{
		if(is_null($type_id)) return array();

		$this->_user_awards->user_id = $this->id;       
		return $this->_user_awards->get_by_type($type_id);
	}

	function get_awards_not_granted()
	{
		$this->_user_awards->user_id = $this->id;
		return $this->_user_awards->get_not_granted();
	}

	function get_awards_not_granted_dropdown()
	{
		$this->_user_awards->user_id = $this->id;
		return $this->_user_awards->get_dropdown();
	}

	function grant_award($award_id = NULL)
	{
		if( is_null($award_id)) 
			return FALSE;
            
		$user_award = new User_award();
		$user_award->user_id = $this->id;
		$user_award->award_id = $award_id;            
		$user_award->save();
	}
        
	function revoke_award($user_award_id = NULL)
	{
		if( is_null($user_award_id))
			return FALSE;

		$user_award = new User_award($user_award_id);
		$user_award->delete();            
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
		$db_legacy->select('pilotid, firstname,	lastname, location,	currlocation,
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
		$this->_user_stats->hours_flights = $this->_hours_to_mins($row->totalhours);
		$this->_user_stats->hours_transfer = $this->_hours_to_mins($row->transferhours);
		$this->_user_stats->hours_adjustment = $this->_hours_to_mins($row->bonushours);
		$this->_user_stats->hours_type_rating = $this->_hours_to_mins($row->trhours);
		$this->_user_stats->total_pay = $row->totalpay;
		$this->_user_stats->pay_adjustment = $row->payadjust;
		
		// Translate current location
		$airport = new Airport();
		$airport->icao = $row->currlocation;
		$airport->find();		
		$this->_user_stats->current_location = $airport->id;		
		
		// Calculate totals
		$db_legacy->select('sum(landingrate) as totallandings, 
				max(landingrate) as softestlanding,
				min(landingrate) as hardestlanding,
				sum(fuelused) as totalfuel,
				sum(gross) as gross, 
				sum(expenses) as expenses');
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
			$this->_user_stats->landing_softest = abs($row->softestlanding);
			$this->_user_stats->landing_hardest = abs($row->hardestlanding);
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
                
                // Get user awards
		$db_legacy->select('name, dateissued')
		          ->from('phpvms_awardsgranted')
                          ->join('phpvms_awards', 'phpvms_awards.awardid = phpvms_awardsgranted.awardid')
		          ->where('pilotid', $this->id);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{                            
                            /* Search for award by name */                            
                            $award = new Award();
                            $award->name = $row->name;
                            $award->find();
                            
                            $user_award = new User_award();
                            
                            $user_award->user_id    = $this->id;
                            $user_award->award_id   = $award->id;
                            $user_award->created    = $row->dateissued;
                            $user_award->save();               
			}
		}
                
                // Get user badges
		$db_legacy->select('name, dateissued')
		          ->from('phpvms_badgesgranted')
                          ->join('phpvms_badges', 'phpvms_badges.badgeid = phpvms_badgesgranted.badgeid')
		          ->where('pilotid', $this->id);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{                            
                            /* Search for award by name */                            
                            $award = new Award();
                            $award->name = $row->name;
                            $award->find();
                            
                            $user_award = new User_award();
                            
                            $user_award->user_id    = $this->id;
                            $user_award->award_id   = $award->id;
                            $user_award->created    = $row->dateissued;
                            $user_award->save();                               
			}
		}
		
		// Get user comments
		$db_legacy->select('adminid, comment, date, staff')
		          ->from('phpvms_pilotcomments')
		          ->where('pilotid', $this->id);
		
		$query = $db_legacy->get();
		
		if ($query->num_rows() > 0)
		{
			$note = new Note();
			$note->entity_type = 'user';
			$note->entity_id = $this->id;
			
			foreach ($query->result() as $row)
			{
				$note->user = $row->adminid;
				$note->note = $row->comment;
				$note->date = $row->date;
				$note->private_note = $row->staff;
				
				$note->save();
			}
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
		$this->_set_retirement('+14 days');
		
		// Set starting location
		if (is_null($this->_user_stats->current_location))
		{
			$airport = new Airport($this->hub);
			$this->_user_stats->current_location = $airport->icao;
			$this->grant_jumpseat();
		}
		
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
		// XXX This doesn't appear to be correct. Id should be populated always.
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
			log_message('error', 'New user database transaction failed.');
			return FALSE;
		}
		
		return $this->id;
	}
	
	/**
	 * Processes a PIREP for the user.
	 * 
	 * @param Pirep $pirep object to process
	 */
	public function process_pirep($pirep)
	{
		if (is_null($this->id))
		{
			log_message('error', 'Trying to process PIREP for null user.');
			return FALSE;
		}
		
		// Filing a PIREP activates the user
		if (in_array($this->status, array(
				self::LOA,
				self::RESIGNED,
				self::FURLOUGH,
		))) {
			$this->make_active();
		}
		$this->_set_retirement();
				
		$stats = $this->get_user_stats();
		
		if ($pirep->status == Pirep::APPROVED)
		{
			// Charge the category if needed so the PIREP is Rejected post haste
			$rank = new Rank($this->rank_id);
			if (!$rank->check_aircraft_category($pirep->airline_aircraft_id))
			{
				if (!$this->charge_cat_waiver())
				{
					$pirep->status == Pirep::REJECTED;
					$pirep->save();
					$pirep->set_note('[SYSTEM] - PIREP Rejected/Unable to charge CAT waiver.', $this->id);
				}
			}
		}
		
		if ($pirep->status == Pirep::APPROVED)
		{	
			$stats->fuel_used = $stats->fuel_used + $pirep->fuel_used;
			$stats->total_landings = $stats->total_landings + $pirep->landing_rate;
			$stats->hours_flights = $stats->hours_flights + $pirep->hours_total();
			$stats->hours_hub = $stats->hours_hub + $pirep->hours_total();
			
			if ($stats->landing_hardest < $pirep->landing_rate)
			{
				$stats->landing_hardest = $pirep->landing_rate;
			}
			if ($stats->landing_softest == 0
					OR $stats->landing_softest > $pirep->landing_rate)
			{
				$stats->landing_softest = $pirep->landing_rate;
			}
			
			$stats->total_expenses = $stats->total_expenses + $pirep->get_property('_expenses');
			$stats->total_gross = $stats->total_gross + $pirep->get_property('_gross_income');
			$stats->total_pay = $stats->total_pay + $pirep->get_property('_pilot_pay_total');
			
			// Promote/Demote?
			if ($rank->min_hours > $stats->total_hours())
			{
				// Demotion
				$prev_rank = $rank->next_rank(TRUE);
				if ($prev_rank)
				{
					$this->rank_id = $prev_rank->id;
					$this->set_note('Demoted to '.$prev_rank->rank, $this->id);
				}
			}
			else 
			{
				$next_rank = $rank->next_rank();
				if ($next_rank && $stats->total_hours() >= $next_rank->min_hours)
				{
					// Promotion
					$this->rank_id = $next_rank->id;
					$this->set_note('Promoted to '.$next_rank->rank, $this->id);
					$user_updates = TRUE;
				}
			}
			
			// Online flyer award
			if ($pirep->online)
			{
				$award = new Award(array('name' => 'Online Flyer'));
				$this->grant_award($award->id);
			}			
		}
		elseif ($pirep->status == Pirep::REJECTED)
		{
			$stats->flights_rejected++;
		}
		
		$stats->current_location = $pirep->arr_icao;
		$stats->last_flight_date = date('Y-m-d');
		$stats->save();
		$this->save();
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
			log_message('error', 'Trying to save a new user. Users can only be updated.');
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
		log_message('error', 'Unsupported function: tried to delete a user.');
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

				// Background checking (business logic level)
				log_message('debug', 'Running background check on user ID: '.$this->id);
				
				$birth = strtotime($this->birthday);
				$maxbirth = strtotime('-16 year');
				$minbirth = strtotime('-100 year');
				log_message('debug', 'Birthdate: '.$birth);
				log_message('debug', 'Youngest Birthdate: '.$maxbirth);
				log_message('debug', 'Oldest Birthdate: '.$minbirth);
				
				if ($birth >= $maxbirth)
				{
					// Too young
					$this->set_note('[SYSTEM] - Account rejected: Too young.', $this->id, TRUE);
					$this->ban_reason = 'Too young at time of application';
					$this->ban();
					return FALSE;
				}
				if ($birth <= $minbirth)
				{
					// Too old (over 100 years!)
					$this->set_note('[SYSTEM] - Account rejected: Too old.', $this->id, TRUE);
					$this->ban_reason = 'Too old at time of application';
					$this->ban();
					return FALSE;
				}
				
				
				// Background check ok, activate the user
				$this->activated = 1;
				$this->new_email_key = '';
				if ($this->status == self::WAITING)
				{
					$this->status = self::NEWREG;
				}
				$this->_set_retirement();
				$this->save();
				
				// Update hub stats
				$hub_stat = new Hub_stat();
				$hub_stat->airport_id = $this->hub;
				$hub_stat->update_pilots_assigned();

				// TODO Create user in other systems
				
			}
			return TRUE;
		}
		
		return FALSE;
	}

	/**
	 * Makes a user fully active.
	 * 
	 * If a user is banned, this function will have no effect. Use unban() instead.
	 * 
	 * @return boolean FALSE if user id is not populated
	 */
	function make_active()
	{
		if (is_null($this->id)) return FALSE;
		$this->find();
		if (!$this->banned)
		{
			$this->activated = 1;
			$this->status = self::ACTIVE;
			$this->_set_retirement();
			$this->save();
		}
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
			
			// Update session log
			$sesslog = new Session_log();
			$sesslog->add_log($this->id, $curr_ip);
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Requests a transfer to the given hub_id
	 * @param int $hub_id
	 * @return boolean FALSE if the user object is not populated
	 */
	function request_transfer($hub_id)
	{
		if (is_null($this->id) OR $this->hub_transfer > 0) return FALSE;
		
		$this->hub_transfer = $hub_id;
		$this->save();
	}
	
	/**
	 * Approves any pending transfer.
	 * 
	 * @return boolean FALSE if the user object is not populated
	 */
	function approve_transfer()
	{
		if (is_null($this->id) OR $this->hub_transfer == 0) return FALSE;

		$out_hub = $this->hub;
		$in_hub = $this->hub_transfer;
		
		$this->hub = $this->hub_transfer;
		$this->hub_transfer = 0;
		$this->save();
		
		// Update out hub stats
		$hub_stat = new Hub_stat();
		$hub_stat->airport_id = $out_hub;
		$hub_stat->update_pilots_assigned();
		
		// Update in hub stats
		$hub_stat = new Hub_stat();
		$hub_stat->airport_id = $in_hub;
		$hub_stat->update_pilots_assigned();
		
		
		// Reset hub seniority
		$stats = $this->get_user_stats();
		$stats->hours_hub = 0;
		$stats->save();		
	}
	
	/**
	 * Rejects any pending transfer.
	 * 
	 * @return boolean FALSE if the user object is not populated
	 */
	function reject_transfer()
	{
		if (is_null($this->id)) return FALSE;
		
		$this->hub_transfer = 0;
		$this->save();
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
		$this->status = self::BANNED;
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
		$this->status = self::PROBATION;
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
		
		$this->status = self::LOA;
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
		
		$this->status = self::RESIGNED;
		$this->retire_date = date('Y-m-d H:i:s');
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
		
		$this->status = self::PROBATION;
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
	function change_password($old_pass, $admin = FALSE)
	{		
		if ( ! is_null($this->id) && ! is_null($this->password))
		{
			$user = new User($this->id);
			if ($admin OR $user->_verify_password($old_pass))
			{
				$user->password = $this->password;
				$user->_hash_password();
				$user->save();

				return TRUE;
			}
		}
		
		return FALSE;
		
	}
	
	/**
	 * Resets a user's password.
	 * 
	 * Object must be populated with id and password key prior to calling this
	 * method.
	 * 
	 * @param string $new_pass
	 * @return boolean TRUE if the password was changed, FALSE if the user is not found
	 */
	public function reset_password($new_pass)
	{
		if ($this->find())
		{
			$this->password = $new_pass;
			$this->_hash_password();
			$this->save();
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Finds all the managers for this user.
	 * 
	 * Managers are staff members, manager level or above, who are assigned to 
	 * the same hub.
	 * 
	 * @return array|boolean Array of User objects for each manager or FALSE if
	 * no managers are found.
	 */
	public function find_managers()
	{
		if (is_null($this->_manager_list))
		{
			$search = new User();
			$search->hub = $this->hub;
			$search->admin_level = '>= '.self::ACCESS_MANAGER;
			$this->_manager_list = $search->find_all();
		}
		return $this->_manager_list;
	}
	
	/**
	 * Grants a jumpseat to the user
	 * 
	 * Users can have up to 128 jumpseats. This increases the number of jumpseats
	 * they have available by 1.
	 * 
	 * @return boolean TRUE if granted, FALSE otherwise
	 */
	public function grant_jumpseat()
	{
		if ($this->find())
		{
			$this->waivers_js++;
			$this->save();
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Charges a jumpseat
	 * 
	 * Will use a waiver if the user has one, otherwise charges an hours adjustment
	 * based on the distance between the user's current location and the ICAO of
	 * the airport passed in.
	 * 
	 * @param string $icao of the departure airport
	 * @return boolean TRUE if jumpseat charged or waiver used. FALSE if the User
	 * was not found.
	 */
	public function charge_jumpseat($icao)
	{
		if ($this->find())
		{
			// Jumpseat waivers available?
			if ($this->waivers_js > 0)
			{
				$this->waivers_js--;
				$this->save();
				$this->set_note("Jumpseat waiver used to get to {$icao}.", $this->id);
			}
			else
			{
				$stats = $this->get_user_stats();
				$curr_loc = new Airport(array('icao' => $stats->current_location));
				$dep_loc = new Airport(array('icao' => $icao));
				$distance = Calculations::calculate_airport_distance($curr_loc, $dep_loc);
					
				if ($distance > 3000) $stats->hours_adjustment--;
				if ($distance > 2000) $stats->hours_adjustment--;
				if ($distance > 1000) $stats->hours_adjustment--;
				if ($distance > 100) $stats->hours_adjustment--;
				if ($distance > 1) $stats->hours_adjustment--;
				$stats->save();
				$this->set_note("Jumpseat charged for {$distance} miles.", $this->id);
			}
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Grants a CATegory waiver to the user
	 * 
	 * Users can have up to 128 category waivers allowing them to fly any aircraft
	 * in the fleet one time per waiver. This method increases the number of cat
	 * waivers they have available by 1.
	 * 
	 * @return boolean TRUE if granted, FALSE otherwise
	 */
	public function grant_cat_waiver()
	{
		if ($this->find())
		{
			$this->waivers_cat++;
			$this->save();
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Charges a CATegory waiver
	 * 
	 * @return boolean TRUE if a waiver was charged. FALSE otherwise.
	 */
	public function charge_cat_waiver()
	{
		if ($this->find())
		{
			if ($this->waivers_cat > 0)
			{
				$this->waivers_cat--;
				$this->save();
				$this->set_note('CAT waiver charged.', $this->id);
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * Determines if user is a premium user.
	 * 
	 * @return boolean TRUE if user is a premium user.
	 */
	function is_premium()
	{
		if (is_null($this->_is_premium))
		{
			$this->_set_access();
		}
		return $this->_is_premium;
	}
	
	/**
	 * Determines if user is an admin user.
	 *
	 * @return boolean TRUE if user is an admin user.
	 */
	function is_admin()
	{
		if (is_null($this->_is_admin))
		{
			$this->_set_access();
		}
		return $this->_is_admin;
	}
	
	/**
	 * Determines if user is a manager user.
	 *
	 * @return boolean TRUE if user is a manager user.
	 */
	function is_manager()
	{
		if (is_null($this->_is_manager))
		{
			$this->_set_access();
		}
		return $this->_is_manager;
	}

	/**
	 * Determines if user is an executive user.
	 *
	 * @return boolean TRUE if user is an executive user.
	 */
	function is_executive()
	{
		if (is_null($this->_is_exec))
		{
			$this->_set_access();
		}
		return $this->_is_exec;
	}
	
	/**
	 * Determines if user is an admin user.
	 *
	 * @return boolean TRUE if user is an admin user.
	 */
	function is_superadmin()
	{
		if (is_null($this->_is_super))
		{
			$this->_set_access();
		}
		return $this->_is_super;
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
	
	/**
	 * Access levels for the system are defined here.
	 */
	private function _set_access()
	{
		($this->admin_level >= self::ACCESS_PREMIUM) ? $this->_is_premium = TRUE : $this->_is_premium = FALSE;
		($this->admin_level >= self::ACCESS_ADMIN) ? $this->_is_admin = TRUE : $this->_is_admin = FALSE;
		($this->admin_level >= self::ACCESS_MANAGER) ? $this->_is_manager = TRUE : $this->_is_manager = FALSE;
		($this->admin_level >= self::ACCESS_EXEC) ? $this->_is_exec = TRUE : $this->_is_exec = FALSE;
		($this->admin_level >= self::ACCESS_SUPER) ? $this->_is_super = TRUE : $this->_is_super = FALSE;
	}
	
	/**
	 * Sets the user's retirement date
	 */
	private function _set_retirement($retirement = '+30 days')
	{
		if ($this->is_premium())
		{
			$retirement = '+90 days';
		}
		if ($this->is_admin())
		{
			$retirement = '+180 days';
		}
		if ($this->is_executive())
		{
			$retirement = '+365 days';
		}
		$retire_date = strtotime($retirement);
		$this->retire_date = date('Y-m-d H:i:s', $retire_date);
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

/**
 * User_awards object is essentially a sub of the User model.
 * 
 * The user awards contains all awards granted to the user.
 * @date 02/15/2015
 * @author Jeff
 *
 */
class User_award extends PVA_Model {
	
	/* Default Properties */
	public $user_id     = NULL;
        
	public $award_id    = NULL;
        
	public $created     = NULL;
        
        protected $_order_by    = 'created desc';
        
        protected $_awards_table    = 'awards';
        protected $_join            = 'awards.id = user_awards.award_id';
        protected $_awards_key      = 'awards.award_type_id';
	
	function __construct($id = NULL)
	{
            parent::__construct($id);
	} 
        
        /*Override save to ensure no double rows (WIP)
        * See if row exists with user_id and award_id
        * if it exists return FALSE
        * if not create new user award row
        */
        function save()
        {            
            $user_award = new User_award();
            $user_award->user_id = $this->user_id;
            $user_award->award_id = $this->award_id;
            
            if(! $user_award->find())
            {
                $now = date('Y-m-d H:i:s');
                if(is_null($this->created)) 
                {
                    $this->created = $now;
                }
                parent::save();
            } 
        }
        
        /*
         * Retrieve user awrds for user based
         * on award_type.  
         */
        
        function get_by_type($type_id)
        {
            $this->db->select($this->_table_name . '.*, awards.award_type_id')
                    ->from($this->_table_name)
                    ->join($this->_awards_table, $this->_join)
                    ->where($this->_awards_key, $type_id)
                    ->where('user_id', $this->user_id)
                ;
            
            $query = $this->db->get();  
            return $this->_get_objects($query);
        }
        
        /*
         * Retrieve all awards that a user has
         * NOT been granted.  This can be used to 
         * grant an award to the user.  Awards
         * can only be granted once
         * 
         * return Award Objects
         */
        function get_not_granted()
        {
            //SELECT * FROM da05_awards WHERE da05_awards.id NOT IN (SELECT award_id FROM da05_user_awards WHERE user_id = 2)
            $this->db->select('*')
                    ->from($this->_awards_table)
                    ->where($this->_awards_table . '.id NOT IN (SELECT award_id FROM ' . 
                            $this->db->dbprefix($this->_table_name) . ' WHERE user_id = ' . $this->user_id.')')
                ;
            
            $query = $this->db->get();
            return $this->_get_objects($query);            
        }       
        
        function get_dropdown()
        {
            $awards = $this->get_not_granted();
            
            $data = array();
	    
	    if(count($awards) > 0)
	    {
		foreach($awards as $award)
		{
		    $data[$award->id] = $award->name;
		}     
	    }
            return $data;
        }
        
}

/**
 * User_aircraft object is essentially a sub of the User model.
 * 
 * The user aircraft contains all aircraft flown by the user.
 * @date 02/15/2015
 * @author Jeff
 *
 */
class User_aircraft extends PVA_Model {
	
	/* Default Properties */
	public $user_id         = NULL;
	public $aircraft_id     = NULL;
        public $total_flights   = NULL;
	public $total_hours     = NULL;
        public $total_landings  = NULL;
        public $totsal_gross    = NULL;
        public $total_expenses  = NULL;
        public $total_pay       = NULL;
        public $flights_early   = NULL;
        public $flights_ontime  = NULL;
        public $flights_late    = NULL;
        public $flights_manual  = NULL;
        public $fuel_used       = NULL;
	
	function __construct($user_id = NULL)
	{
		parent::__construct();
                $this->_table_name = 'user_aircraft';
		$this->user_id = $user_id;
	} 
        
}
