<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends PVA_Controller {
		
	/**
	 * Short circuit to the view
	 */
	function index()
	{
		$this->view();	
	}
	
	/**
	 * Display a pilot profile
	 * 
	 * Defaults to the currently logged in pilot if no ID is provided.
	 * 
	 * @param string $id of the pilot to display
	 */
	function view($id = NULL)
	{
		if (is_null($id)) 
		{
			// Default to current user
			$id = $this->session->userdata('user_id');
		}
				
		// Make sure this page is not cached
		$this->_no_cache();
		
		// Own profile?
		$this->data['own_profile'] = ($id == $this->session->userdata('user_id'));
		
		// Get the user
		$user = new User($id);
		$user->find();
		
		if ($user->name)
		{
			// User found
			
			// Load helpers
			$this->load->helper('html');
			$this->load->helper('url');
			
			// Populate user info
			$this->data['meta_title'] = pva_id($user).' '.$user->name;
			$this->data['title'] = $user->name;
			$this->data['user_id'] = $user->id;
			$this->data['name'] = pva_id($user).' '.$user->name;
			$this->data['birthday'] = $user->birthday;
			$this->data['joined'] = strip_time($user->created);
			$this->data['ipbuser_id'] = 0;

			// Premium user
			$this->data['is_premium'] = $user->is_premium();
			
			// Status
			$status_array = $this->config->item('user_status');
			$this->data['status'] = $status_array[$user->status];
			$this->data['raw_status'] = $user->status;
			
			// Hub
			$hub = new Airport($user->hub);
			$this->data['hub'] = $hub->icao;
			
			// Populate profile
			$user_profile = $user->get_user_profile();
			$this->data['avatar'] = 'http://www.phoenixva.org/lib/images/noavatar.png';
			if (strlen($user_profile->avatar > 0))
			{
				$this->data['avatar'] == $user_profile->avatar;
			}
			$this->data['signature'] = $user_profile->background_sig;
			$this->data['home_town'] = $user_profile->location;
			
			// Populate stats
			$user_stats = $user->get_user_stats();
			$this->data['total_flights'] = $user_stats->total_flights();
			$this->data['early_flights'] = $user_stats->flights_early;
			$this->data['ontime_flights'] = $user_stats->flights_ontime;
			$this->data['delayed_flights'] = $user_stats->flights_late;
			$this->data['early_percent'] = 0;
			$this->data['ontime_percent'] = 0;
			$this->data['delayed_percent'] = 0;
			$this->data['landing_avg'] = 0;
			$this->data['landing_softest'] = 0;
			$this->data['landing_hardest'] = 0;
			$this->data['landing_danger'] = 0;
			$this->data['landing_warning'] = 0;
			$this->data['landing_success'] = 0;
				
			if ($user_stats->total_flights() > 0)
			{
				$this->data['early_percent'] = 100 * ($user_stats->flights_early / $user_stats->total_flights());
				$this->data['ontime_percent'] = 100 * ($user_stats->flights_ontime / $user_stats->total_flights());
				$this->data['delayed_percent'] = 100 * ($user_stats->flights_late / $user_stats->total_flights());
				$landing_avg = $user_stats->total_landings / $user_stats->total_flights();
				$this->data['landing_avg'] = number_format($landing_avg,2);
					
				$landing_pct = $landing_avg / 1000;
				if ($landing_pct > 0.75)
				{
					$this->data['landing_danger'] = 100 * ($landing_pct - 0.75);
					$this->data['landing_warning'] = 25;
					$this->data['landing_success'] = 50;
				}
				elseif ($landing_pct > 0.5)
				{
					$this->data['landing_warning'] = 100 * ($landing_pct - 0.5);
					$this->data['landing_success'] = 50;
				}
				else
				{
					$this->data['landing_success'] = 100 * $landing_pct;
				}
				
				$this->data['landing_softest'] = $user_stats->landing_softest;
				$this->data['landing_hardest'] = $user_stats->landing_hardest;
			}
			else
			{
				$this->data['help'] = '<h4>Welcome to PVA!</h4>
						<p>Be sure to download PVACARS so you can file your flights.</p>';
			}
			
			$this->data['total_hours'] = format_hours($user_stats->total_hours());
			$this->data['flight_hours'] = format_hours($user_stats->hours_flights);
			$this->data['transfer_hours'] = format_hours($user_stats->hours_transfer);
			$this->data['bonus_hours'] = format_hours($user_stats->hours_adjustment);
			$this->data['type_hours'] = format_hours($user_stats->hours_type_rating);
			
			$this->data['airlines_flown'] = number_format($user_stats->airlines_flown);
			$this->data['aircraft_flown'] = number_format($user_stats->aircraft_flown);
			$this->data['airports_flown'] = number_format($user_stats->airports_landed);
			
			$this->data['total_pay'] = number_format($user_stats->total_pay,2);
			
			// Current location
			$this->data['location'] = 'No Info';
			if (strlen($user_stats->current_location) > 0)
			{
				$location = new Airport($user_stats->current_location);
				$this->data['location'] = $location->icao;
			}
			
			// Get rank info
			$rank = new Rank($user->rank_id);
			$this->data['rank'] = $rank->rank;
			$this->data['rank_image'] = $rank->rank_image;
			$this->data['next_rank'] = FALSE;
			
			if ($next_rank = $rank->next_rank())
			{
				$this->data['next_rank'] = $next_rank->rank;
				$this->data['next_rank_hours'] = $next_rank->min_hours;
				$next_rank_mins = $next_rank->min_hours * 60;
				$this->data['next_rank_percent'] = 100 * ($user_stats->total_hours() / ($next_rank_mins));
				$this->data['next_rank_to_go'] = format_hours($next_rank_mins - $user_stats->total_hours());
			}
			
			// Get Bids
			$this->data['bids'] = array();
			
			// Get Logbook
			$this->data['logs'] = array();
			
			// Get Notes XXX Probably need this in PVA_Controller
			$this->data['notes'] = array();
			$note_model = new Note('user', $user->id);
			$notes = $note_model->get_notes();
			if ($notes)
			{
				foreach ($notes as $note)
				{
					$note_user = $note->get_user();
					$note->name = pva_id($note_user->id).' '.$note_user->name;
					$this->data['notes'][] = $note;
				}
			}
		}
		else 
		{
			// User doesn't exist
			$this->data['title'] = 'No Pilot Found';
			$this->data['errors'][] = 'A pilot with that ID could not be located.';
		}
		$this->_render();
	}
}