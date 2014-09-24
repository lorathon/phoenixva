<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Returns the user info in a consistent format.
 * 
 * @param object $user User object of the user to display.
 * @return string in the format PVA#### First Last with a link to the user's profile.
 */
function user($user)
{
	return anchor('private/profile/view'.$user->id, pva_id($user->id).' '.$user->name);
}

/**
 * Returns the user ID formatted for display.
 * 
 * @param string|object $user Either the ID or a full User object.
 * @return string the formatted ID (PVA####)
 */
function pva_id($user)
{
	if (is_object($user))
	{
		$user = $user->id;
	}
	return 'PVA'.str_pad($user, 4, '0', STR_PAD_LEFT);
}