<?php

// Get Data from ds18b20

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

if( isset( $_GET['device'] ) ) {
	$device = $_GET['device'];
	$retArr[0]['device'] = $device;
	$retArr[0]['temp'] = getTemp( $device );
} else {
	$retArr[0]['device'] = false;
	$retArr[0]['temp'] = 0.0;
}

// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );


// Helper functions

function getTemp( $device = null ) {
	if( $device != null ) {
		$absolutPath = realpath("../../");
		$temp = shell_exec("$absolutPath/inc/bin/ds18b20 $device" );
		if( $temp != null ) {
			 return $temp;
		} else {
			return false;
		}
	} 
}


?>
