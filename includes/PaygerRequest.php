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
	 * The Environment for the request - Test / Sandbox or Production
	*/
	protected static $_env = '';

	/*
	 * The API url we're hitting. {{ ENV }} will get replaced with $domain
	* when you set PaygerRequest::init($domain, $token)
	*/
	protected $_api_url = 'https://merchant-api-{{ ENV }}.payger.com/api/v{{ VERSION }}/{{ CLASS }}';
	
	/*
	 * Stores the current method we're using. Example:
	*
	*/
	protected $_method = '';
	
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
	public static function init( $key, $secret )
	{
		self::$_key    = $key;
		self::$_secret = $secret;
	}
	
	/*
	 * Set up the request object and assign a method name
	*
	* @param array $method The method name from the API, like 'client.update' etc //TODO fix this description
	* @return null
	*/
	public function __construct( $method )
	{
		$this->_method = $method;
	}
	
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
	public function request( $id = '' ) {
		/*
		if ( ! self::$_key || ! self::$_secret ) {
			throw new PaygerRequestException('You need to call Request::init($domain, $token) with your domain and token.');
		}*/

		//Build Data to Send

		$url = str_replace( '{{ ENV }}', self::$_env, $this->_env );

		$class = explode(".", $this->_method);
		
		$ch = curl_init();    // initialize curl handle

		curl_setopt_array($ch, array(
			CURLOPT_URL => "http://merchant-api-test.payger.com/api/v1/oauth/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "grant_type=password&username=key8&password=O5pCmkzuQx",
			CURLOPT_HTTPHEADER => array(
				"authorization: Basic Y2xpZW50MTpTRkRQZzU3YlZKWXliV1px",
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded",
				"postman-token: db37403b-d067-dc8f-d3e9-157ab2474a2a"
			),
		));

		$response = curl_exec( $ch );
		$err = curl_error( $ch );

		curl_close( $ch );

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}

		error_log( 'RESPONSE ');
		error_log( $response );

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
	
		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
		if(curl_errno($ch))
		{
			$this->_error = 'A cURL error occured: ' . curl_error($ch);
			return;
		}
		else
		{
			curl_close($ch);
		}

		// if weird simplexml error then you may have the a user with
		// a user_meta wc_ie_client_id defined that not exists in InvoiceXpress
		if ($result && $result != " ") {
			$res = print_r($result, true);
			//error_log("result = {".$res."}");
			
			$response = json_decode(json_encode(simplexml_load_string($result)), true);
			//$r = print_r($response, true);
			//error_log("response = ".$r);
		
			$this->_response = $response;
		}
		
		$this->_success = (($http_status == '201 Created') || ($http_status == '200 OK'));
		//error_log("http status = ".$http_status);
		
		if(isset($response['error']))
		{
			$this->_error = $response['error'];
		}*/
	
	}
}