<?php

// Get Data from dht11

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

$absolutPath = realpath("../../");
$values = explode(" ", shell_exec("sudo " . $absolutPath . "/inc/bin/dht11" ));

if( valid( $values[0] ) && valid( $values[0] ) ) {
	$retArr[0]['rf'] = $values[0];
	$retArr[0]['temp'] = $values[1];
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

function getPa( $alt = null ) {
	if( $alt != null ) {
		$absolutPath = realpath("../../");
		$altitude = shell_exec("sudo $absolutPath/inc/bin/bmp085 $alt" );
		if( $altitude != null ) {
			return $altitude;
		} else {
			return false;
		}
	} 
}


?>
