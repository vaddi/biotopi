<?php

// Get Data from dht11

//require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

$absolutPath = realpath("../../");
$values = explode(" ", shell_exec("sudo " . $absolutPath . "/inc/bin/dht11" ));

if( valid( $values[0] ) && valid( $values[1] ) ) {
	$retArr[0]['rf'] = reminder( 'rf', $values[0] );
	$retArr[0]['temp'] = reminder( 'temp', $values[1] );
} else {
	$retArr[0]['unknown'] = null;
}
	
// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );


// Helper functions

function valid( $var ) {
	$valid = false;
	if( is_array( $var ) ) {
		$valid = isset( $var[0] ) ? true : false;
		$valid = $var[0] != null ? true : false;
		$valid = ! empty( $var[0] ) ? true : false;	
	} else {
		$valid = $var != "" ? true : false;
		$valid = $var != null ? true : false;
		$valid = ! empty( $var ) ? true : false;
	}
	return $valid;
}

function reminder( $unit = null ,$value = null ) {
	if( $unit != null ) {
		if( $value != null ) {
			if( DHT11HIST > 0 ) {
//				$value = str_replace( "\n", '', $value );
				$tmpfile = realpath("../../")."/inc/tmp/dht11_" . $unit . ".dat";
				$filetime = filemtime( $tmpfile );
				$now = time();
				// 3600s = 1h
				if( ( $now - DHT11HIST ) > $filetime ) {
					// get old data 
					require_once( '../class/class.File.php' );
					$tmparr = json_decode( File::read( $tmpfile ) );
					// if no data available, set default (24h)
					if( ! isset( $tmparr ) ) $tmparr = array( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
					// shift Data in to array
					$tmparr = arrShifter( $tmparr, $value );
					// update bmp file data (json encode)
					File::write( $tmpfile, json_encode( $tmparr ) );
				}
			}
			return $value;
		} else {
			return false;
		}
	} 
}


?>
