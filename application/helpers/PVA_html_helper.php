<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Returns airport info in a consistent format.
 * 
 * @param object $airport Airport object of the airport to display.
 * @return string in the format ICAO Name with a link to the airport details page.
 */
function airport($airport)
{
	$attribs = array();
	
	if ($airport->is_hub())
	{
		$attribs['class'] = 'hub';
	}
	
	return anchor('airport/view/'.$airport->id, $airport->icao.' '.$airport->name, $attribs);
}

/**
 * Returns the user info in a consistent format.
 * 
 * @param object $user User object of the user to display.
 * @return string in the format PVA#### First Last with a link to the user's profile.
 */
function user($user)
{
	return anchor('private/profile/view/'.$user->id, pva_id($user->id).' '.$user->name);
}

/**
 * Returns the user ID formatted for display.
 * 
 * In most cases you'll want to use the user() function instead of this one.
 * 
 * @see user()
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
 * XXX Not working due to css/js conflict.
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

/**
 * Sets the table layout for the system.
 * 
 * @return array suitable for use by $this->table->set_template()
 */
function table_layout()
{
	// Set the table layout
	$table_layout = array(
			'table_open' => '<div class="table-responsive"><table class="table table-condensed table-hover">',
			'table_close' => '</table></div>',
	);
	
	return $table_layout;
}