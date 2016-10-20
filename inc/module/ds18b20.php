<?php

// Get Data from ds18b20

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

// set json header
require_once( 'json_header.php' );

if( isset( $_GET['device'] ) ) {
	$device = $_GET['device'];
	$retArr[0]['device'] = $device;
	$retArr[0]['temp'] = ds18b20( $device );
} else if( isset( $argv[1] ) ) {
	// called from commandline (param all)
	$device = ($argv[1] == 'all') ? $argv[1] : null;
	if( $device != null ) {
		// iterate over all devices 
		$path = '/sys/devices/w1_bus_master1/';
		exec( 'ls -d '.$path.'28-*', $msg, $err );
		print_r( "[" . date( 'd.m.Y H:i:s' ) . "] " );
		foreach( $msg as $key => $device ) {
			$dev = str_replace( $path, '', $device );
			$retArr[0][ 'device_' . $key ] = $dev;
			$retArr[0][ 'temp_' . $key ] = ds18b20( $dev );
		}
	} else {
		$retArr[0] = 'no device';
	}
} else {
	$retArr[0]['device'] = false;
	$retArr[0]['temp'] = 0.0;
}

// print output
print_r( json_encode( $retArr ) . "\n" );

?>
