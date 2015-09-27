<?php

//if( isset( $_GET['sid'] ) ) $client_sid = $_GET['sid'];

//if( empty( session_id() ) ) session_start();
//$this_sid = session_id();

//if( $this_sid != $client_sid ) {
//	// noID or wrongID, redirect to mainindex
//	echo "<meta http-equiv='refresh' content='0; url=./' />";
//} else { 
	
	if( isset( $_GET['alt'] ) ) {
		$alt = $_GET['alt'];
		$retArr[0]['alt'] = $alt;
		$retArr[0]['pa'] = getPa( $alt );
		print_r( json_encode( $retArr ) );
//		echo $_GET['alt'];
	} 
	
	
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
