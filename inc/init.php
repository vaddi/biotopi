<?php

// CLIENT			Set the Browser User Agent String
// CLIENT_IP	Set the Client IP
// PROTOCOL		Set the protocol
// HOST				Set the Hostname
// APPLANG		Browser Language (en, de, etc)
// PATH				Set a simple url path
// URL				Comlete URL
// SCRIPT			Set the complete url
// SCRIPT_URL	

// Include our config file
incl('/var/www/inc/config.php');

if( isset( $fromscript ) && !$fromscript ) {
// Create Session
	$session = session_id();
	if(empty($session)) session_start();
}
// set client token
//define('SERVERTOKEN', strtotime( date( 'd.m.Y H:00:00' ) ) . '_' . session_id() );
define('SERVERTOKEN', strtotime( date( 'd.m.Y H:00:00' ) ) . '_' . str2hex( ENCRYPTION_KEY ) );
define('CLIENTTOKEN', urlencode( base64_encode( SERVERTOKEN ) ) );
//$_SESSION["client"] = CLIENTTOKEN; // store token in session 

// define some constants
define('CLIENT', isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'cmdline' );
define('CLIENT_IP', isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1' );
define('PROTOCOL', stripos( isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'http' ,'https') === true ? 'https://' : 'http://' );
define('HOST', isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : 'localhost' );
define('APPLANG', substr( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en' , 0, 2 ) );	
define('PATH',  dirname($_SERVER['PHP_SELF']));
define('URL', PROTOCOL . HOST . PATH ); 		
define('SCRIPT', str_replace('/','', str_replace( PATH, '', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/') ) );
define('SCRIPT_URL', URL . '/' . SCRIPT );
define('VERSION', "0.2");

// check for installer file to help install the neccessary stuff
$fload = 'install.php'; 
if (file_exists($fload)) return include $fload; 

?>
