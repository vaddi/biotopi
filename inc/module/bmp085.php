<?php

// Get Data from bmp085

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

$log = null;

if( isset( $_GET['alt'] ) ) {
	$alt = $_GET['alt'];
} else if ( isset( $_POST['alt'] ) ) {
	$alt = $_POST['alt'];
} else if( isset( $argv[1] ) ) {
	$alt = $argv[1];	// we've been called from cmd line
	$log = "[" . date( 'd.m.Y H:i:s' ) . "] ";
} else {
	$alt = 70; // default 
}

$retArr[0]['alt'] = $alt;
$retArr[0]['pa'] = bmp085( $alt );

// set json header and print output
require_once( __DIR__ . '/json_header.php' );
if( $log !== null ) print_r( $log );
print_r( json_encode( $retArr ) . "\n" );
	
?>
