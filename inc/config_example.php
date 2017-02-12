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

// 
define( 'BMP085FILE', 'bmp085.dat' );		// bmb085 data file
define( 'BMP085HIST', 60 );							// file update time in minutes
define( 'DS18B20FILE', 'ds18b20_{DEVICE}.dat' );	// ds18b20 data file ({DEVICE} is automaticly replaced by onewire device id)
define( 'DS18B20HIST', 60 );						// file update time in minutes
define( 'DHT11FILE', 'dht11_{UNIT}.dat' );	// dht11 data file
define( 'DHT11HIST', 60 );							// file update time in minutes
define( 'MCP3008HIST', 60 );
define( 'MCP3008FILE', 'mcp3008.dat' );

define( 'SMSSENTFILE', 'sentstate.dat' );		// sms sent data file
define( 'SMSRECEIVEDFILE', 'receivedstate.dat' );		// sms received data file
define( 'SMSFAILEDFILE', 'failedstate.dat' );		// sms failed data file
define( 'SMSSIGNALFILE', 'signalstate.dat' );		// sms signal data file
define( 'SENTHIST', 1 );								// file update time in minutes

define( 'SMSMAX', 160 ); 	// max lengt for sms
define( 'LCDMAX', 80 ); 	// max lengt for LCD
define( 'MAILMAX', 750 ); // max lengt for email
define( 'CLIMAX', 500 ); 	// max lengt for client Messages

define( 'DATEFORM', 'd.m.Y H:i' ); // default dateformate

define( 'DBFILE', '/var/www/inc/tmp/database.db' );

// SMTP Settings for mail sending
define( 'MAILUSER', "myname@domain.com" ); 	// SMTP Auth
define( 'MAILPASS', "yourpassword" ); 			// SMTP Path
define( 'MAILHOST', "smtp.domain.com" ); 		// SMTP Host

?>
