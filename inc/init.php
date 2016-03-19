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
incl('inc/config.php');

// Create Session
$session = session_id();
if(empty($session)) session_start();
// set client token
//define('SERVERTOKEN', strtotime( date( 'd.m.Y H:00:00' ) ) . '_' . session_id() );
define('SERVERTOKEN', strtotime( date( 'd.m.Y H:00:00' ) ) . '_' . str2hex( ENCRYPTION_KEY ) );
define('CLIENTTOKEN', urlencode( base64_encode( SERVERTOKEN ) ) );
$_SESSION["client"] = CLIENTTOKEN; // store token in session 

// define some constants
define('CLIENT', $_SERVER['HTTP_USER_AGENT'] );
define('CLIENT_IP', $_SERVER['REMOTE_ADDR']);
define('PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' );
define('HOST', $_SERVER['SERVER_NAME'] );
define('APPLANG', substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ) );	
define('PATH',  dirname($_SERVER['PHP_SELF']));
define('URL', PROTOCOL . HOST . PATH ); 		
define('SCRIPT', str_replace('/','', str_replace( PATH, '', $_SERVER['REQUEST_URI']) ) );
define('SCRIPT_URL', URL . '/' . SCRIPT );
define('VERSION', "0.2");

// check for installer file to help install the neccessary stuff
$fload = 'install.php'; 
if (file_exists($fload)) return include $fload; 

?>
