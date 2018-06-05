<?php
/**
 * A simple PHP API wrapper for the Payger API.
 * All post vars can be found on the developer site: https://developers-test.payger.com/
 * Stay up to date on Github:
 *
 * PHP version 5
 *
 * @author     WidgiLabs <contact@widgilabs.com>
 * @license    MIT
 * @version    1.0
 */
class PaygerRequestException extends Exception {}
class PaygerRequest {
	/*
	 * The Key you need when making a request
	*/
	protected static $_key = '';
	
	/*
	 * The API Secret you need when making a request
	*/
	protected static $_secret = '';

	/*
	 * The API Token you need when making a request
	*/
	protected static $_token = false;

	/*
	 * The Environment for the request - Test / Sandbox or Production
	*/
	protected static $_env = 'test';

	/*
	 * The API url we're hitting. {{ ENV }} will get replaced with $domain
	* when you set PaygerRequest::init($domain, $token)
	*/
	protected $_api_url = 'https://merchant-api-{{ ENV }}.payger.com/api/v1/{{ CLASS }}';
	
	/*
	 * Stores the current method we're using. Example:
	*
	*/
	protected $_endpoint = '';

	/*
	 * Any arguments to pass to the request
	*/
	protected $_args = array();
	
	/*
	 * Determines whether or not the request was successful
	*/
	protected $_success = false;
	
	/*
	 * Holds the error returned from our request
	*/
	protected $_error = '';
	
	/*
	 * Holds the response after our request
	*/
	protected $_response = array();

	/*
	 * Initialize and store the Key and Secret for making requests
	*
	* @param string $key
	* @param string $secret
	* @return null
	*/
	public function init( $key, $secret )
	{
		self::$_key    = $key;
		self::$_secret = $secret;

		//get token if non existent
		$this->request( '/oauth/token', 'POST' );

		//TODO Refresh token if expired
	}
	
	/*
	 * Set up the request object and assign a method name
	*
	* @param array $method The method name from the API, like 'client.update' etc //TODO fix this description
	* @return null
	*/
	/*public function __construct( $endpoint )
	{
		$this->_endpoint = $endpoint;
	}*/
	
	/*
	 * Set the data/arguments we're about to request with
	*
	* @return null
	*/
	public function post( $data )
	{
		$this->_args = $data;
	}
	
	/*
	 * Determine whether or not it was successful
	*
	* @return bool
	*/
	public function success()
	{
		return $this->_success;
	}
	
	/*
	 * Get the error (if there was one returned from the request)
	*
	* @return string
	*/
	public function getError()
	{
		return $this->_error;
	}
	
	/*
	 * Get the response from the request we made
	*
	* @return array
	*/
	public function getResponse()
	{
		return $this->_response;
	}
	
	/*
	 * Send the request over the wire
	*
	* @return array
	*/
	public function request( $id = false, $method = 'POST' ) {

		if ( ! $id || ! self::$_token ) {
			throw new PaygerRequestException( 'You must have a valid token to pay with Payger, pelase contact website administrator', 'payger' );
		}

		//Build Data to Send

		$url = str_replace( '{{ ENV }}', self::$_env, $this->_api_url );
		$url = str_replace( '{{ CLASS }}', $id, $url );

		error_log('URL FOR REQUESR ' . $url );

		
		$ch = curl_init();    // initialize curl handle

		$url = "http://merchant-api-test.payger.com/api/v1/oauth/token";
		$method = "POST";
		$post_data = "grant_type=password&username=key8&password=O5pCmkzuQx";

		curl_setopt( $ch, CURLOPT_URL, $url ); // set url to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // return into a variable
		curl_setopt( $ch, CURLOPT_TIMEOUT, 40 ); // times out after 40s
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );

		if( $post_data ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}


		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"authorization: Basic Y2xpZW50MTpTRkRQZzU3YlZKWXliV1px",
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			"postman-token: db37403b-d067-dc8f-d3e9-157ab2474a2a"
		));


//		if ($class[1] != "get")
//			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
		//curl_setopt($ch, CURLOPT_USERPWD, self::$_token . ':X');
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec( $ch );

		$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		if ( curl_errno($ch) )
		{
			$this->_error = 'A cURL error occured: ' . curl_error( $ch );
			return;
		}
		else
		{
			curl_close($ch);
		}


		if ( $result && $result != " ") {
			$response = json_decode( $result, true);

			$r = print_r($response, true);
			error_log("response = ".$r);

			$this->_response = $response;
		}

		$this->_success = ( ( $http_status == '201 Created' ) || ( $http_status == '200 OK' ) );

		if(isset($response['error']))
		{
			$this->_error = $response['error'];
		}


		/*
		if ($class[1] == "change-state" || $class[1] == "email-invoice") {
			$url = str_replace( '{{ CLASS }}', "invoice/" . $id . "/" . $class[1], $url );
		} elseif ($class[0] == "clients" && $class[1] == "find-by-code") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$url = str_replace('{{ CLASS }}', "clients/find-by-code", $url);
		} elseif ($class[0] == "clients" && $class[1] == "update") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			$url = str_replace('{{ CLASS }}', "clients/".$id, $url);
		} elseif ($class[0] == "clients" && $class[1] == "get") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$url = str_replace('{{ CLASS }}', "clients/".$id, $url);
		} elseif ($class[0] == "simplified_invoices" && $class[1] == "get") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$url = str_replace('{{ CLASS }}', "simplified_invoices/".$id, $url);
		} elseif ($class[0] == "sequences" && $class[1] == "get") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$url = str_replace('{{ CLASS }}', "sequences", $url);
		} else {
			$url = str_replace('{{ CLASS }}', $class[0], $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			//error_log("POST Request = true");
		}


		//DEBUG
		//error_log("URL = ".$url);

		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 40s
		if ($class[1] != "get")
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
		curl_setopt($ch, CURLOPT_USERPWD, self::$_token . ':X');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml; charset=utf-8"));
	
		*/
	
	}
}