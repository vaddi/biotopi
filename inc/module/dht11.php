<?php

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
//	error_log("Ungueltige Variable " . var_dump( $var ) , 0);
	return $valid;
}
//if( isset( $_GET['sid'] ) ) $client_sid = $_GET['sid'];

//if( empty( session_id() ) ) session_start();
//$this_sid = session_id();

//if( $this_sid != $client_sid ) {
//	// noID or wrongID, redirect to mainindex
//	echo "<meta http-equiv='refresh' content='0; url=./' />";
//} else { 
	
//	if( isset( $_GET['alt'] ) ) {
	$absolutPath = realpath("../../");
	$values = explode(" ", shell_exec("sudo " . $absolutPath . "/inc/bin/dht11" ));
	
	if( valid( $values[0] ) && valid( $values[0] ) ) {
		$retArr[0]['rf'] = $values[0];
		$retArr[0]['temp'] = $values[1];
	} else {
		$retArr[0]['unknown'] = null;
	}
	

	print_r( json_encode( $retArr ) );
//		echo $_GET['alt'];
//	} 
	
	
//} // END else 

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
