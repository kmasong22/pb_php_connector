<?php

/** PocketBase PHP Connector Version 0.1 **/

if( !defined( 'API_SERVER' ) ){
	define( 'API_SERVER', 'http://127.0.0.1:8090' );
}

class PB{

	public static $_URL = API_SERVER ;
	public static $headers = array() ;
	public static $collection ;

  public static function _init($name = 'users') {
    self::$collection = $name ;
    $authorization = isset($_SESSION['authStore_token']) ? $_SESSION['authStore_token'] : '' ;
    self::$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $authorization );
  }

	public static function GET( $id, $_collection = '' ){

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

	public static function LIST( $post = [], $_collection = '' ){

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
	public static function CREATE( $data, $_collection = '' ){

		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);

		return $result ;

	}

	public static function UPDATE( $id, $post, $_collection = '' ){

		$_collection = $_collection == '' ? self::$collection : $_collection ;

		$request = "/api/collections/".$_collection."/records/{$id}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);

		return $result ;

	}

	public static function AUTHENTICATE( $identity, $password ){

		$request = "/api/collections/users/auth-with-password";
		$data = array( 'identity' => $identity, "password" => $password );
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_URL.$request );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers );
		$response = curl_exec($ch);
		$result = json_decode($response);

		curl_close($ch);
		
		if(isset($result->token)){
			$_SESSION['authStore_token'] = $result->token ;
		}

		return $result ;

	}

	public static function DELETE( $id ){

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

	public static function QUIT(){

		unset($_SESSION['authStore_token']);

	}

}