<?php

// Get Data from ds18b20

//require_once( "../config.php" );
//require_once( '../functions.php' );
//require_once( '../secure.php' );

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

// set json header
require_once( 'json_header.php' );

if( isset( $_GET['device'] ) ) {
	$device = $_GET['device'];
	$retArr[0]['device'] = $device;
	$retArr[0]['temp'] = getTemp( $device );
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
			$retArr[0][ 'temp_' . $key ] = getTemp( $dev );
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


// Helper functions

function getTemp( $device = null ) {
	if( $device != null ) {
		$absolutPath = realpath("/var/www");
		$temp = shell_exec("$absolutPath/inc/bin/ds18b20 $device" );
		if( $temp != null ) {
			
			if( DS18B20HIST > 0 ) {
				$temp = str_replace( "\n", '', $temp );
				$file = ( defined( 'DS18B20FILE' ) && strpos( DS18B20FILE, '{DEVICE}' ) !== false ) ? str_replace( '{DEVICE}', $device, DS18B20FILE ) : DS18B20FILE;
				$tmpfile = $absolutPath."/inc/tmp/" . $file;
				$filetime = filemtime( $tmpfile );
				$now = time();
			
				// 3600s = 1h
				if( ( $now - DS18B20HIST ) > $filetime ) {

					// get old data 
					require_once( $absolutPath.'/inc/class/class.File.php' );
					$tmparr = json_decode( File::read( $tmpfile ) );
				
					// if no data available, set default (24h)
					if( ! isset( $tmparr ) ) $tmparr = array( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
				
					// shift Data in to array
					$tmparr = arrShifter( $tmparr, $temp );
				
					// update bmp file data (json encode)
					File::write( $tmpfile, json_encode( $tmparr ) );
			
				}
			}
			
			return $temp;
		} else {
			return false;
		}
	} 
}


?>
