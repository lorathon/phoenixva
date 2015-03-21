<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
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
						'rules' => 'trim|required|xss_clean|valid_email]',
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
						'rules' => 'trim|required|xss_clean|numeric',
						),
				array(
						'field' => 'transfer_hours',
						'label' => 'Transfer Hours',
						'rules' => 'trim|xss_clean|numeric|less_than[150.01]',
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
						'rules' => 'trim|required|xss_clean|valid_email]',
						),
				),
		);