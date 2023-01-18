<?php

/** PocketBase PHP Connector Version 0.1 **/

if( !defined( 'API_SERVER' ) ){
	define( 'API_SERVER', 'http://127.0.0.1:8090' );
}
if( !defined( 'API_PREFIX' ) ){
	define( 'API_PREFIX', '' );
}

class PB{

	public static $_URL = API_SERVER ;
	public static $_API_PREFIX = API_PREFIX ;
	public static $headers = array() ;
	public static $headerx = array() ;
	public static $collection ;

	// Initiates the record
  public static function _init($name = 'users') {
    self::$collection = $name ;
    $authorization = isset($_SESSION[self::$_API_PREFIX.'_authStore_token']) ? $_SESSION[self::$_API_PREFIX.'_authStore_token'] : '' ;
    self::$headers = array( 'Content-Type: application/json', 'Authorization: Bearer ' . $authorization );
		self::$headerx = array( 'Authorization: Bearer ' . $authorization );
  }

	// Get a record
	public static function GET( $id, $_collection = '' ){
		self::_init( $_collection );
		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records/{$id}";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL . $request );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result   = json_decode($response);
		curl_close($ch);

		return $result ;

	}

	// List records
	public static function LIST( $post = [], $_collection = '' ){
		self::_init( $_collection );
		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records" . '?' . http_build_query($post) ;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL . $request );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result   = json_decode($response);
		curl_close($ch);

		return $result ;

	}

	// Create a record
	public static function CREATE( $post, $_collection = '' ){
		self::_init( $_collection );
		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,  $post );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headerx );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);

		return $result ;

	}

	// Updates a record
	public static function UPDATE( $id, $post, $_collection = '' ){
		self::_init( $_collection );
		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records/{$id}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headerx );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);

		return $result ;

	}

	// Authenticate session
	public static function AUTHENTICATE( $identity, $password ){

		$request = "/api/collections/users/auth-with-password";
		$post = array( 'identity' => $identity, "password" => $password );
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post );
		// curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);
		
		if(isset($result->token)){
			$_SESSION[self::$_API_PREFIX.'_authStore_token'] = $result->token ;
			$_SESSION[self::$_API_PREFIX.'_userid'] = $result->record->id ;
			$_SESSION[self::$_API_PREFIX.'_username'] = $result->record->username ;
			$_SESSION[self::$_API_PREFIX.'_email'] = $result->record->email ;
			$_SESSION[self::$_API_PREFIX.'_name'] = $result->record->name ;
			$_SESSION[self::$_API_PREFIX.'_avatar'] = $result->record->avatar ;
			$_SESSION[self::$_API_PREFIX.'_role'] = $result->record->role ;
			$_SESSION[self::$_API_PREFIX.'_verified'] = $result->record->verified ;
		}

		return $result ;

	}

	// Delete a record
	public static function DELETE( $id, $_collection = '' ){
		self::_init( $_collection );
		$request = "/api/collections/".self::$collection."/records/{$id}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);

		return $result ;
	}

	// Unsets the session
	public static function QUIT( ){

		foreach($_SESSION as $key => $value){
			if (strpos($key, self::$_API_PREFIX.'_') === 0){
				unset($_SESSION[$key]); 
			}
		}

	}

}