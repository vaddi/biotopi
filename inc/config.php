<?php

// Application Settings
define( 'APPNAME', 'BiotoPi' );				// Application Name
define( 'ENV', 'dev' ); 							// Enviroment (dev,prod)
define( 'SECRET', 'tOpSeCr3tT0k3n!'); // Secret token

// Set default Timezone 
// var_dump( DateTimeZone::listIdentifiers() ); // list them all
//date_default_timezone_set( 'UTC' );
date_default_timezone_set( 'Europe/Berlin' );

// Used Database type
define( 'DB', 'SQLite' ); // Used Database (SQLite,MySQL)
//define( 'DB', 'MySQL' ); // Used Database (SQLite,MySQL)

// MySQL connection parameters
define( 'MYSQL_USER', 'dbuser' );
define( 'MYSQL_PASS', 'unsicher' );
define( 'MYSQL_NAME', 'biotopi' );
define( 'MYSQL_HOST', 'spawn' );
define( 'MYSQL_PORT', 3306 );

// SQLite connection parameters
define( 'SQLITE_TYPE', 'FILE' );	// FILE | MEMORY (In planning)
define( 'SQLITE_FILE', __DIR__ . '/db/database.db' );

// set server and client token
$unpack = unpack( 'H*', SECRET );
$secret = array_shift( $unpack );
define('SERVERTOKEN', strtotime( date( 'd.m.Y H:00:00' ) ) . '_' . $secret );
define('CLIENTTOKEN', urlencode( base64_encode( SERVERTOKEN ) ) );

// helper constants
define('PROTOCOL', stripos( isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'http' ,'https') === true ? 'https://' : 'http://' );
define('HOST', isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : 'localhost' );	
define('PATH', dirname($_SERVER['PHP_SELF']) );
define('URL', PROTOCOL . HOST . PATH ); 
define('CLIENTLANG', substr( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en' , 0, 2 ) );

?>
