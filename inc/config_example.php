<?php

// APPNAME		Applicationname from Foldername
// DEBUG			Set Debugging Level 0-2
// MAIL				Receiver Adress for Contact form
// NAV				Navigation 0 = navigation.php, or set a path to a php file

//define('APPNAME', str_replace('/', '', dirname($_SERVER['PHP_SELF'])) ); // for subdir rootpath
define( 'APPNAME', 'My App' );	// Name of Your Application
define( 'DEBUG', 0 );						// 1 = on, 0 = off

//define('NAV', 0);
define( 'NAV', 'inc/nav.php' ); // 0 = default, path to your navigation.php file

define( 'BASEPATH', '/var/www' );									// default server rootpath 
define( 'FAVICON', 'inc/img/myapp.ico' ); 				// path to your favicon.ico file
define( 'CLIENTMSGFILE', 'inc/tmp/cliMsg.dat' ); 	// storagefile for Clientmessages

define( 'ENCRYPTION_KEY', '!%$#@^&*' ); // App private Encryption Key
define( 'REDIRECT', false ); // Redirect to mainpage if ajax client token are wrong

define( 'SMSMAX', 160 ); 	// max lengt for sms
define( 'LCDMAX', 80 ); 	// max lengt for LCD
define( 'MAILMAX', 750 ); // max lengt for email
define( 'CLIMAX', 500 ); 	// max lengt for client Messages

define( 'DATEFORM', 'd.m.Y H:i' ); // default dateformate

// SMTP Settings for mail sending
define( 'MAILUSER', "myname@domain.com" ); 	// SMTP Auth
define( 'MAILPASS', "yourpassword" ); 			// SMTP Path
define( 'MAILHOST', "smtp.domain.com" ); 		// SMTP Host

?>
