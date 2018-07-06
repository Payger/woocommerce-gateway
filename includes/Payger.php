<?php

/**
 * PHP Rest CURL
 * https://github.com/jmoraleda/php-rest-curl
 * (c) 2014 Jordi Moraleda
 */

Class Payger {

	/**
	 * Variable: $url
	 * Description:  A Payger Instance.
	 */
	private $url = 'https://merchant-api-{{ ENV }}.payger.com/api/v1/{{ CLASS }}';

	/**
	 * Variable: $username
	 * Description:  A Payger User.
	 */
	protected static $username;

	/**
	 * Variable: $password
	 * Description:  The password for the $username Payger account
	 */
	protected static $password;

	/**
	 * Variable: $token
	 * Description:  OAuth 2.0 token
	 */
	protected static $token;

	/**
	 * Function: getNewAuthToken
	 * Parameters: none
	 * Description: Retrieve access token from OAuth server
	 * Returns: token on success, otherwise null
	 */
	public static function getNewAuthToken()
	{
		$obj = array(
			'grant_type' => 'password',
			'username'   => self::$username,
			'password'   => self::$password,
		);

		$response = Payger::exec( 'POST', 'oauth/token', $obj );


		$token = false;
		if ( isset( $response['data'] ) ) {
			$response = $response['data'];
			$token    = $response->access_token;
		}
		return $token;
	}

	/*
	* Function: connect()
	 * Parameters:   none
	 * Description:  Authenticate and set the oAuth 2.0 token
	 * Returns:  TRUE on login success, otherwise FALSE
	 *
	 */
	public static function connect()
    {
	    // if we do have a valid token no need to
	    // get new one
	    /*if ( self::check() ) {
		    //error_log('TENHO VALID TOKEN');
		    return true;
	    }*/

	    $token = self::getNewAuthToken();

	   // error_log('GETTING NEW TOKEN '.$token );

	    if ( ! $token ) {
		    return false;
	    }
	    self::setToken( $token );
	    return true;
    }

	/**
	 * Function: check()
	 * Parameters:   none
	 * Description:  check if token is set and its a valid one
	 * Returns:  TRUE on login success, otherwise FALSE
	 */
	public static function check()
	{
		if( ! self::$token )
			return false;
		return true;
	}


	/**
	 * Function: getUrl()
	 * Description:  Set $url
	 * Returns:  returns a value if successful, otherwise FALSE
	 */
	public function getUrl()
	{
		return $this->url;
	}
	/**
	 * Function: setUsername()
	 * Parameters:   $value = Username for the REST API User
	 * Description:  Set $username
	 * Returns:  returns FALSE is falsy, otherwise TRUE
	 */
	public static function setUsername($value)
	{
		if ( ! $value ) {
			return false;
		}
		self::$username = $value;
		return true;
	}
	/**
	 * Function: setPassword()
	 * Parameters:   none
	 * Description:  Set $password
	 * Returns:  returns FALSE is falsy, otherwise TRUE
	 */
	public static function setPassword( $value )
	{
		if ( ! $value )
			return false;
		self::$password = $value;
		return true;
	}

	/**
	 * Function: setToken()
	 * Parameters:   none
	 * Description:  Set $token
	 * Returns:  returns FALSE is falsy, otherwise TRUE
	 */
	public static function setToken($value)
	{
		if(!$value)
			return false;
		self::$token = $value;
		//error_log('JUST SET NEW TOKEN '.$value);
		return true;
	}

	public static function exec($method, $endpoint, $obj = array()) {

		$url = 'https://merchant-api-{{ ENV }}.payger.com/api/v1/{{ CLASS }}'; //FIXME Payger::getUrl()
		$url = str_replace( '{{ ENV }}', 'test', $url ); //TODO change this dynamically
		$url = str_replace( '{{ CLASS }}', $endpoint, $url );

		$curl = curl_init();

		switch( $method ) {
			case 'GET':
				if(strrpos($url, "?") === FALSE) {
					$url .= '?' . http_build_query($obj);
				}
				break;

			case 'POST':
				curl_setopt($curl, CURLOPT_POST, TRUE);
				break;

			case 'PUT':
			case 'DELETE':
			default:
				break;
		}

	//	error_log('URL '.$url);


		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 40 ); // times out after 40s
		curl_setopt( $curl, CURLOPT_ENCODING, "" );
		curl_setopt( $curl, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, strtoupper($method) );

		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);


		//Get new token
		if ( 'oauth/token' == $endpoint ) {

			$post_data = "grant_type=password&username=key8&password=O5pCmkzuQx";

			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					"authorization: Basic a2V5ODpPNXBDbWt6dVF4",
					"cache-control: no-cache",
					"content-type: application/x-www-form-urlencoded",
				));
		} else {

			$post_data = http_build_query($obj);

//			$obj = "externalId=43&asset=bitcoin&amount=15.00";

//			error_log('ENDPOINT '.$endpoint );
//			error_log('USING TOKEN '.self::$token);
//			error_log('USER '.self::$username);
//			error_log('PASS '.self::$password);

			curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
				"authorization: Bearer " . self::$token,
				"cache-control: no-cache",
				"content-type: application/json"
			) );

			curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $obj ) );
		}

		// Exec
		$response = curl_exec($curl);
		$info     = curl_getinfo($curl);

		curl_close($curl);

		// Data
		$header = trim(substr($response, 0, $info['header_size']));
		$body = substr($response, $info['header_size']);

		return array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
	}



	public static function get($url, $obj = array()) {
		return Payger::exec("GET", $url, $obj);
	}

	public static function post($url, $obj = array()) {
		return Payger::exec("POST", $url, $obj);
	}

	public static function put($url, $obj = array()) {
		return Payger::exec("PUT", $url, $obj);
	}

	public static function delete($url, $obj = array()) {
		return Payger::exec("DELETE", $url, $obj);
	}
}
