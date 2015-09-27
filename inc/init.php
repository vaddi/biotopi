<?php

// DEBUG			Set Debugging Level 0-2
// MAIL				Receiver Adress for Contact form
// CLIENT			Set the Browser User Agent String
// CLIENT_IP	Set the Client IP
// PROTOCOL		Set the protocol
// HOST				Set the Hostname
// APPNAME		Applicationname from Foldername
// APPLANG		Browser Language (en, de, etc)
// PATH				Set a simple url path
// URL				Comlete URL
// SCRIPT			Set the complete url
// SCRIPT_URL	


// Create Session
$session = session_id();
if(empty($session)) session_start();

// define some constants
define('DEBUG', 0);
define('MAIL', "mvattersen@gmail.com" );
define('CLIENT', $_SERVER['HTTP_USER_AGENT'] );
define('CLIENT_IP', $_SERVER['REMOTE_ADDR']);
define('PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' );
//define('HOST', gethostname() );				// DEPRECATED
define('HOST', $_SERVER['SERVER_NAME'] );
//define('APPNAME', str_replace('/', '', dirname($_SERVER['PHP_SELF'])) );
define('APPNAME', "BiotoPi" );
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
