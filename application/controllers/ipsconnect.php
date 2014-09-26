<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @class	ipsConnect
 * @brief	This is where you put the code for your application
 *
 */
class Ipsconnect extends PVA_Controller {
	
	/**
	 * Constructor
	 *
	 * Use this to do any initiation required by your application
	 */
	public function __construct()
	{
		$this->profiler = FALSE;
		parent::__construct();

		$this->secret_key = 'secretkey';

		$this->url = 'http://www.example.com';
		$this->url_to_this_file = $this->url . '/ipsconnect.php';
	}

	/**
	 * Process Login
	 *
	 * @param	string	Identifier - may be 'id', 'email' or 'username'
	 * @param	string	Value for identifier (for example, the user's ID number)
	 * @param	string	The password, md5 encoded
	 * @param	string	md5( IPS Connect Key (see login method) . Identifier Value )
	 * @param	string	Redirect URL, Base64 encoded
	 * @param	string	md5( IPS Connect Key . $redirect )
	 * @return	mixed	If the redirect URL is provided, this function should redirect the user to that URL with three additional paramaters:
	 *						connect_status		value from below
	 *						connect_id			the ID number in this app
	 *						connect_username	the username
	 *						connect_displayname	the display name
	 *						connect_email		the email address
	 *						connect_unlock		If the account is locked, the number of seconds until it unlocks
	 *					If blank, will output to screen a JSON object with the same parameters
	 *					Values:
	 *						SUCCESS			login successful
	 *						WRONG_AUTH		Password incorrect
	 *						NO_USER			Identifier did not match member account
	 *						MISSING_DATA	Identifier or password was blank
	 *						ACCOUNT_LOCKED	Account has been locked by brute-force prevention
	 *						VALIDATING		Account has not been validated
	 */
	public function login( $identifier, $identifierValue, $md5Password, $key, $redirect, $redirectHash )
	{
		// @todo - log the user in


		// Set the master cookie
		// @todo - ensure this cookie is also set when then user logs into your application locally
		if ( $redirect )
		{
			setcookie( "ipsconnect_" . md5( $this->url_to_this_file ), '1', time()+60*60*24*30, '/' );
		}

		// Return
		if ( $redirect )
		{
			$redirect = ( ( $key == md5( $this->secret_key . $identifierValue ) ) and ( $redirectHash == md5( $this->secret_key . $redirect ) ) ) ? $redirect : base64_encode( $this->url );
		}
		$this->_return( $redirect, array( 'connect_status' => 'NO_USER' ) );
	}

	/**
	 * Process Logout
	 *
	 * @param	int		ID number
	 * @param	string	md5( IPS Connect Key (see login method) . ID number )
	 * @param	string	Redirect URL, Base64 encoded
	 * @param	string	md5( IPS Connect Key . $redirect )
	 * @return	mixed	If the redirect URL is provided, this function should redirect the user to that URL
	 *					If blank, will output blank screen
	 */
	public function logout( $id, $key, $redirect, $redirectHash )
	{
		// Check key
		if ( $key != md5( $this->secret_key . $id ) )
		{
			$this->_return( base64_encode( $this->url ) );
		}
			
		// @todo - log the user out

		// Set the master cookie
		// @todo - ensure this cookie is also set when then user logs into your application locally
		// Note thate you ARE NOT unsetting this cookie, but setting it with a value of 0
		setcookie( "ipsconnect_" . md5( $this->url_to_this_file ), '0', time()+60*60*24*30, '/' );

		// Return
		if ( $redirect )
		{
			$redirect = ( $redirectHash == md5( $this->secret_key . $redirect ) ) ? $redirect : base64_encode( $this->url );
		}
		$this->_return( $redirect );
	}

	/**
	 * Register a new account
	 *
	 * @param	string	Key - this can be anything which is known only to the applications. Never reveal this key publically.
	 *					For IPS Community Suite installs, this key can be obtained in the Login Management page in the ACP
	 * @param	string	Username
	 * @param	string	Display name
	 * @param	string	The password, md5 encoded
	 * @param	string	Email address
	 * @param	string	If set, this account should be considered to be waiting for email validation. If this is the case, a URL is provided which will be the URL from which the user to resend the email.
	 * @return	void	Outputs to screen JSON object with 2 parameters
	 'status'	One of the following values:
	 BAD_KEY				The key provided was invalid
	 SUCCESS				Account created
	 EMAIL_IN_USE		Email is already in use
	 USERNAME_IN_USE		Username is already in use
	 BAD_KEY				Key was invalid
	 MISSING_DATA		Not all data was provided
	 FAIL				Other error
	 'id' with master ID number (0 if fail) - if user already exists, will provide ID of existing user
	 */
	public function register( $key, $username, $displayname, $md5Password, $email, $revalidateurl )
	{
		// Check key
		if ( $key != $this->secret_key )
		{
			echo json_encode( array( 'status' => 'BAD_KEY', 'id' => 0 ) );
			exit;
		}

		if ( !$email or !$md5Password )
		{
			echo json_encode( array( 'status' => 'MISSING_DATA', 'id' => 0 ) );
			exit;
		}

		// @todo - create the account

		// Return
		echo json_encode( array( 'status' => 'FAIL', 'id' => 0 ) );
		exit;
	}

	/**
	 * Validate Cookie Data
	 *
	 * @param	string	JSON encoded cookie data
	 * @return	void	Outputs to screen a JSON object with the bollowing properties:
	 *						connect_status		SUCCESS, VALIDATING (successful, but account has not been validated) or FAIL
	 *						connect_id			the ID number in this app
	 *						connect_username	the username
	 *						connect_displayname	the display name
	 *						connect_email		the email address
	 */
	public function cookies( $data )
	{
		$cookies = json_decode( stripslashes( urldecode( $data ) ), TRUE );

		echo json_encode( array( 'connect_status' => 'FAIL' ) );
		exit;
	}

	/**
	 * Check data
	 *
	 * @param	string	Key - this can be anything which is known only to the applications. Never reveal this key publically.
	 *					For IPS Community Suite installs, this key can be obtained in the Login Management page in the ACP
	 * @param	int		If provided, do not throw an error if the "existing user" is the user with this ID
	 * @param	string	Username
	 * @param	string	Display Name
	 * @param	string	Email address
	 * @return	void	Outputs to screen a JSON object with four properties (status, username, displayname, email) - 'status' will say "SUCCESS" - the remainding 3 properties will each contain a boolean value, or NULL if no value was provided.
	 *					The boolean value indicates if it is OK to register a new account with that data (this may be because there is no existing user with that, or the app allows duplicates of that data)
	 *					If the key is incorrect - 'status' will be "BAD_KEY" and the remaining 3 parameters will all be NULL.
	 */
	public function check( $key, $id, $username, $displayname, $email )
	{
		$return = array( 'username' => NULL, 'displayname' => NULL, 'email' => NULL );

		// Check key
		if ( $key != $this->secret_key )
		{
			echo json_encode( array_merge( array( 'status' => 'BAD_KEY' ), $return ) );
			exit;
		}

		// @todo - check data

		// Return
		echo json_encode( array_merge( array( 'status' => 'SUCCESS' ), $return ) );
		exit;
	}

	/**
	 * Change account data
	 *
	 * @param	int		ID number
	 * @param	string	md5( IPS Connect Key (see login method) . ID number )
	 * @param	string	New username (blank means do not change)
	 * @param	string	New displayname (blank means do not change)
	 * @param	string	New email address (blank means do not change)
	 * @param	string	New password, md5 encoded (blank means do not change)
	 * @param	string	Redirect URL, Base64 encoded
	 * @param	string	md5( IPS Connect Key . $redirect )
	 * @return	mixed	If the redirect URL is provided, this function should redirect the user to that URL with a single paramater - 'status'
	 *					If blank, will output to screen a JSON object with the same parameter
	 *					Values:
	 *						BAD_KEY				Invalid Key
	 *						NO_USER				ID number not match any member account
	 *						SUCCESS				Information changed successfully
	 *						USERNAME_IN_USE		The chosen username was in use and as a result NO information was changed
	 *						DISPLAYNAME_IN_USE	The chosen username was in use and as a result NO information was changed
	 *						EMAIL_IN_USE		The chosen username was in use and as a result NO information was changed
	 *						MISSING_DATA		No details to be changed were provided
	 */
	public function change( $id, $key, $username, $displayname, $email, $md5Password, $redirect, $redirectHash )
	{
		// Check key
		if ( $key != md5( $this->secret_key . $id ) )
		{
			$this->_return( base64_encode( $this->url ), array( 'status' => 'BAD_KEY' ) );
		}

		// @todo - change account data


		// Redirect
		if ( $redirect )
		{
			$redirect = ( $redirectHash == md5( $this->secret_key . $redirect ) ) ? $redirect : base64_encode( $this->url );
		}
		$this->_return( $redirect, array( 'status' => 'NO_USER' ) );

	}

	/**
	 * Account is validated
	 *
	 * @param	int		ID number
	 * @param	string	md5( IPS Connect Key (see login method) . ID number )
	 */
	public function validate( $id, $key )
	{
		if ( $key != md5( $this->secret_key . $id ) )
		{
			echo json_encode( array( 'status' => 'BAD_KEY' ) );
		}

		// @todo - validate account

		echo json_encode( array( 'status' => 'SUCCESS' ) );
	}

	/**
	 * Delete account(s)
	 *
	 * @param	array	ID Numbers
	 * @param	string	md5(  IPS Connect Key (see login method) . json_encode( ID number ) )
	 */
	public function delete( $ids, $key )
	{
		if ( $key != md5( $this->secret_key . json_encode( $ids ) ) )
		{
			echo json_encode( array( 'status' => 'BAD_KEY' ) );
		}

		// @todo - delete account

		echo json_encode( array( 'status' => 'SUCCESS' ) );
	}

	/**
	 * Handle redirect / output
	 *
	 * @param	string	Redirect URL, Base64 encoded
	 * @param	array	Params
	 * @return	null	Outputs to screen or redirects
	 */
	protected function _return( $redirect, $params=array() )
	{
		if ( $redirect )
		{
			header( 'Location: ' . base64_decode( $redirect ) . ( $_REQUEST['noparams'] ? '' : ( '&' . http_build_query( $params ) ) ) );
			exit;
		}
		else
		{
			if ( !empty( $params ) )
			{
				echo json_encode( $params );
			}
			exit;
		}
	}
}

/**
 *
 * Map - can modify to add additional parameters, but the IPS Community Suite will only send the defaults
 *
 */
$map = array(
		'login'		=> array( 'idType', 'id', 'password', 'key', 'redirect', 'redirectHash' ),
		'logout'	=> array( 'id', 'key', 'redirect', 'redirectHash' ),
		'register'	=> array( 'key', 'username', 'displayname', 'password', 'email', 'revalidateurl' ),
		'cookies'	=> array( 'data' ),
		'check'		=> array( 'key', 'id', 'username', 'displayname', 'email' ),
		'change'	=> array( 'id', 'key', 'username', 'displayname', 'email', 'password', 'redirect', 'redirectHash' ),
		'validate'	=> array( 'id', 'key' ),
		'delete'	=> array( 'id', 'key' )
);

/**
 *
 * Process Logic - do not modify
 *
*/
/* Original code
 $ipsConnect = new ipsConnect();
if ( isset( $_REQUEST['act'] ) and isset( $map[ $_REQUEST['act'] ] ) )
{
$params = array();
foreach ( $map[ $_REQUEST['act'] ] as $k )
{
if ( isset( $_REQUEST[ $k ] ) )
{
$params[ $k ] = $_REQUEST[ $k ];
}
else
{
$params[ $k ] = '';
}
}

call_user_func_array( array( $ipsConnect, $_REQUEST['act'] ), $params );
}