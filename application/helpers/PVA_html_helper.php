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

/**
 * Builds a modal dialog using a title and body.
 * 
 * @param string $title
 * @param string $body
 * @return string
 */
function modal_window($title = '', $body = '')
{
	$output  = '<div class="modal fade">';
	$output .= '<div class="modal-dialog">';
	$output .= '<div class="modal-content">';
	$output .= '<div class="modal-header">';
	$output .= '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
	$output .= '<h4 class="modal-title">'.$title.'</h4>';
	$output .= '</div>';
	$output .= '<div class="modal-body">';
	$output .= "<p>{$body}</p>";
	$output .= '</div>';
	$output .= '<div class="modal-footer">';
	$output .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
	$output .= '</div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->';
	
	return $output;
}