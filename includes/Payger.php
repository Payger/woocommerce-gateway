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
	private $username;

	/**
	 * Variable: $password
	 * Description:  The password for the $username Payger account
	 */
	private $password;

	/**
	 * Variable: $token
	 * Description:  OAuth 2.0 token
	 */
	protected static $_token;


	/**
	 * Function: getNewAuthToken
	 * Parameters: none
	 * Description: Retrieve access token from OAuth server
	 * Returns: token on success, otherwise null
	 */
	public function getNewAuthToken()
	{
		$obj = array(
			'grant_type' => 'password',
			'username'   => $this->username,
			'password'   => $this->password,
		);

		$response = Payger::exec( 'POST', 'oauth/token', $obj );


		$token = false;
		if ( isset( $response['data'] ) ) {
			$response = $response['data'];
			$token    = $response->access_token;
		}

		// error_log('TOKEN '.$token);

		return $token;
	}

	/*
	* Function: connect()
	 * Parameters:   none
	 * Description:  Authenticate and set the oAuth 2.0 token
	 * Returns:  TRUE on login success, otherwise FALSE
	 *
	 */
	public function connect()
    {

	    // if we do have a valid token no need to
	    // get new one
	    if ( $this->check() ) {
		    error_log('TENHO VALID TOKEN');
		    return true;
	    }


	    $token = $this->getNewAuthToken();
	    if ( ! $token ) {
		    return false;
	    }
	    self::setToken( $token );

	    return true;
    }

	/**
	 * Function: check()
	 * Parameters:   none
	 * Description:  check if token is set
	 * Returns:  TRUE on login success, otherwise FALSE
	 */
	public function check()
	{
		if( ! self::$_token )
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
	public function setUsername($value)
	{
		if ( ! $value ) {
			return false;
		}
		$this->username = $value;
		return true;
	}
	/**
	 * Function: setPassword()
	 * Parameters:   none
	 * Description:  Set $password
	 * Returns:  returns FALSE is falsy, otherwise TRUE
	 */
	public function setPassword($value)
	{
		if(!$value)
			return false;
		$this->password = $value;
		return true;
	}

	/**
	 * Function: setToken()
	 * Parameters:   none
	 * Description:  Set $token
	 * Returns:  returns FALSE is falsy, otherwise TRUE
	 */
	public function setToken($value)
	{
		if(!$value)
			return false;
		self::$_token = $value;
		return true;
	}

	public static function exec($method, $endpoint, $obj = array()) {

		$url = 'https://merchant-api-{{ ENV }}.payger.com/api/v1/{{ CLASS }}'; //FIXME Payger::getUrl()
		$url = str_replace( '{{ ENV }}', 'test', $url ); //TODO change this dinamically
		$url = str_replace( '{{ CLASS }}', $endpoint, $url );


		$curl = curl_init();

		$post_data = "grant_type=password&username=key8&password=O5pCmkzuQx";

		switch($method) {
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

		error_log('URL '.$url);


		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 40 ); // times out after 40s
		curl_setopt( $curl, CURLOPT_ENCODING, "" );
		curl_setopt( $curl, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, strtoupper($method) );
		if( $post_data ) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); ///json_encode($obj)
		}

		if ( 'oauth/token' == $endpoint ) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					"authorization: Basic Y2xpZW50MTpTRkRQZzU3YlZKWXliV1px",
					"cache-control: no-cache",
					"content-type: application/x-www-form-urlencoded",
				));
		} else {
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
				"authorization: Bearer " . self::$_token,
				"cache-control: no-cache",
				"content-type: application/json",
			) );
		}


		//new
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);

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
