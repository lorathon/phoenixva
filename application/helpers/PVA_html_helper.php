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
 * Returns button attributes for an anchor link.
 * 
 * @param string $type The button type to create
 * @return array Associative array suitable for use in anchor().
 */
function button($type)
{
	$class = 'btn btn-'.$type;
	$role  = 'button';
		
	$btn_array = array(
			'class' => $class,
			'role'  => $role,
			);
	
	return $btn_array;
}

/**
 * Returns the date format used by the system.
 * 
 * @return string
 */
function pva_date_format()
{
	return 'YYYY-MM-DD';
}

/**
 * Formats hours and minutes for display
 * 
 * @param int $hours Total number of minutes
 * @param string $separator Optional separator (defaults to :)
 * @return string formatted hours and minutes for display
 */
function format_hours($hours,$separator = ':')
{
	$mins = $hours % 60;
	$hours = floor($hours / 60);
	return $hours.$separator.str_pad($mins,2,0);
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
 * Returns only the date from a date time.
 * 
 * @param string $datetime A string in the format "DATE TIME"
 * @return string containing only the data part.
 */
function strip_time($datetime)
{
	$parts = explode(' ',$datetime);
	return $parts[0];
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