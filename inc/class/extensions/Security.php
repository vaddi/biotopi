<?php

/**
 * A simple class to hold our Security stuff
 */
class Security {
	
	/**
	 * Generate a Token (URL save characters)
	 * Form 0 = only Numbers
	 * Form 1 = only Letters
	 * Form 2 = Letters and Numbers
	 * Form 3 = Letters, Numbers and special Characters
	 * @param $length Integer 
	 * @param $form Integer
	 * @return String
	 */
	public static function genToken( $length = null, $form = null ) {
		if( $length === null ) 	$length = 32;	// default length
		if( $form === null ) 		$form = 2;		// default form
		$num 			= array( '0','1','2','3','4','5','6','7','8','9' );
		$letters 	= array( 'a','b','c','d','e','f','g','h','i','j','k','l','m',
											 'n','o','p','q','r','s','t','u','v','w','x','y','z',
											 'A','B','C','D','E','F','G','H','I','J','K','L','M',
											 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z' );
		$numchars = array( '0','1','2','3','4','5','6','7','8','9','!','(',')','-','_' );
		if( $form == 0 ) {
			$tmpArr = $num;
		} elseif( $form == 1 ) {
			$tmpArr = $letters;
		} elseif( $form == 2 ) {
			$tmpArr = $num + $letters;
		} elseif( $form == 3 ) {	
			$tmpArr = $numchars + $letters;
		} 
		$passArr = $tmpArr;
		$passwd = "";
		$last = '';
		for ($i = 0; $i < $length; $i++) {
			shuffle($passArr);
			if( $last != $passArr[$i] ) { 
				$passwd .= $passArr[$i]; 
			} else { 
				$i -1;
			}
			$last = $passArr[$i];
		}
		return $passwd;
	}
	
	
	/**
	 * get a token ( from string or if no string is given ceate a rnd token)
	 */
	public static function token( $string = null ) {
		$secret = Config::get( 'secret' );
		if( $secret === null || $secret === "" ) throw new Exception( 'No Secret found in DB!' ); 
		if( $string === null ) $string = self::genToken( 16 );
		$token = base64_encode( sha1( $string . $secret, true ) . $secret );
		// safe token in db
		return $token;
	}

	
	/**
	 * Helper function to encrypt data (strings, arrays, etc)
	 */
	public static function encrypt( $string = null, $appToken = null ) {
		if( $string === null || $string === "" ) return false;
		if( $appToken === null ) $appToken = Config::get( 'secret' );
		return base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $appToken, json_encode( $string ), MCRYPT_MODE_ECB ) );
	}


	/**
	 * Helper function to decrypt crypted data string
	 */
	public static function decrypt( $cryptedString = null, $appToken = null ) {
		if( $cryptedString === null || $cryptedString === "" ) return false;
		if( $appToken === null ) $appToken = Config::get( 'secret' );
		return json_decode( trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $appToken, base64_decode( $cryptedString ), MCRYPT_MODE_ECB ) ) );
	}
	
}

?>
