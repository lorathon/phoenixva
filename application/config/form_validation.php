<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
		'acars_processor/file_pirep' => array(
				array(
						'field' => 'user_id',
						'label' => 'User ID',
						'rules' => 'trim|required|integer',
				),
				array(
						'field' => 'client',
						'label' => 'Client',
						'rules' => 'trim|required|xss_clean',
				),
				array(
						'field' => 'ac_model',
						'label' => 'Aircraft Model',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'ac_title',
						'label' => 'Aircraft Title',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'afk_attempts',
						'label' => 'AFK Attempts',
						'rules' => 'trim|numeric',
				),
				array(
						'field' => 'afk_elapsed',
						'label' => 'AFK Elapsed',
						'rules' => 'trim|numeric',
				),
				array(
						'field' => 'flightnumber',
						'label' => 'Flight Number',
						'rules' => 'trim|required|xss_clean|min_length[4]',
				),
				array(
						'field' => 'arr_icao',
						'label' => 'Arrival ICAO',
						'rules' => 'trim|required|max_length[4]',
				),
				array(
						'field' => 'arr_lat',
						'label' => 'Arrival Latitude',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'arr_long',
						'label' => 'Arrival Longitude',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'cargo',
						'label' => 'Cargo Weight',
						'rules' => 'trim|numeric',
				),
				array(
						'field' => 'checkride',
						'label' => 'Checkride Flag',
						'rules' => 'trim|integer|exact_length[1]',
				),
				array(
						'field' => 'dep_icao',
						'label' => 'Departure ICAO',
						'rules' => 'trim|required|max_length[4]',
				),
				array(
						'field' => 'dep_lat',
						'label' => 'Departure Latitude',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'dep_long',
						'label' => 'Departure Longitude',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'distance',
						'label' => 'Distance',
						'rules' => 'trim|required|decimal',
				),
				array(
						'field' => 'event',
						'label' => 'Event Flag',
						'rules' => 'trim|integer|exact_length[1]',
				),
				array(
						'field' => 'flight_level',
						'label' => 'Flight Level',
						'rules' => 'trim|required|number',
				),
				array(
						'field' => 'flight_type',
						'label' => 'Flight Type',
						'rules' => 'trim|required|xss_clean',
				),
				array(
						'field' => 'fuel_out',
						'label' => 'Fuel Out',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_off',
						'label' => 'Fuel Off',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_toc',
						'label' => 'Fuel Top of Climb',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_tod',
						'label' => 'Fuel Top of Descent',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_on',
						'label' => 'Fuel On',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_in',
						'label' => 'Fuel In',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'fuel_used',
						'label' => 'Fuel Used',
						'rules' => 'trim|required|decimal',
				),
				array(
						'field' => 'hours_dawn',
						'label' => 'Hours Dawn',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'hours_day',
						'label' => 'Hours Day',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'hours_dusk',
						'label' => 'Hours Dusk',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'hours_night',
						'label' => 'Hours Night',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'landing_rate',
						'label' => 'Landing Rate',
						'rules' => 'trim|decimal',
				),
				array(
						'field' => 'online',
						'label' => 'Online Flag',
						'rules' => 'trim|exact_length[1]',
				),
				array(
						'field' => 'pax_business',
						'label' => 'Passengers Business Class',
						'rules' => 'trim|integer',
				),
				array(
						'field' => 'pax_economy',
						'label' => 'Passengers Economy Class',
						'rules' => 'trim|integer',
				),
				array(
						'field' => 'pax_first',
						'label' => 'Passengers First Class',
						'rules' => 'trim|integer',
				),
				array(
						'field' => 'route',
						'label' => 'Route',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'time_out',
						'label' => 'Time Out',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'time_off',
						'label' => 'Time Off',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'time_on',
						'label' => 'Time On',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'time_in',
						'label' => 'Time In',
						'rules' => 'trim|xss_clean',
				),
				array(
						'field' => 'comments',
						'label' => 'Comments',
						'rules' => 'trim|xss_clean',
				),
		),
		'acars_processor/update' => array(
		),
		'articles/edit' => array(
				array(
						'field' => 'pagetitle',
						'label' => 'Page Title',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'slug',
						'label' => 'URL Slug',
						'rules' => 'trim|required|xss_clean'
						),
				array(
						'field' => 'pagebody',
						'label' => 'Content',
						'rules' => 'trim|required|xss_clean'
						),
				array(
						'field' => 'note',
						'label' => 'Edit Note',
						'rules' => 'trim|required|xss_clean'
						),
				),
		'auth/change_email' => array(
				array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|xss_clean|valid_email',
						),
				),
		
		'auth/change_password' => array(
				array(
						'field' => 'old_password',
						'label' => 'Old Password',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'confirm_new_password',
						'label' => 'Confirm new Password',
						'rules' => 'trim|required|xss_clean|matches[new_password]',
						),
				),
		
		'auth/forgot_password' => array(
				array(
						'field' => 'login',
						'label' => 'Email or login',
						'rules' => 'trim|required|xss_clean',
						),
				),
		
		'auth/login' => array(
				array(
						'field' => 'login',
						'label' => 'Login',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'remember',
						'label' => 'Remember me',
						'rules' => 'integer',
						),
				),
		
		'auth/register' => array(
				array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|xss_clean|valid_email|is_unique[users.email]',
						),
				array(
						'field' => 'confirm_password',
						'label' => 'Confirm Password',
						'rules' => 'trim|required|xss_clean|matches[password]',
						),
				array(
						'field' => 'first_name',
						'label' => 'First Name',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'last_name',
						'label' => 'Last Name',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'birth_date',
						'label' => 'Birth Date',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'location',
						'label' => 'Location',
						'rules' => 'trim|required|xss_clean',
						),
				array(
						'field' => 'crew_center',
						'label' => 'Crew Center',
						'rules' => 'trim|required|numeric',
						),
				array(
						'field' => 'transfer_hours',
						'label' => 'Transfer Hours',
						'rules' => 'trim|numeric|less_than[150.01]',
						),
				array(
						'field' => 'transfer_link',
						'label' => 'Transfer Link',
						'rules' => 'trim|xss_clean|callback__check_transfer',
						),				
				array(
						'field' => 'heard_about',
						'label' => 'Heard About',
						'rules' => 'trim|xss_clean',
						),				
				),
		
		'auth/reset_password' => array(
				array(
						'field' => 'confirm_new_password',
						'label' => 'Confirm new Password',
						'rules' => 'trim|required|xss_clean|matches[new_password]',
						),
				),
		
		'auth/send_again' => array(
				array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|xss_clean|valid_email',
						),
				),
		);