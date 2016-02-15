<?php

//if( isset( $_GET['sid'] ) ) $client_sid = $_GET['sid'];

//if( empty( session_id() ) ) session_start();
//$this_sid = session_id();

//if( $this_sid != $client_sid ) {
//	// noID or wrongID, redirect to mainindex
//	echo "<meta http-equiv='refresh' content='0; url=./' />";
//} else { 
	
	if( isset( $_GET['device'] ) ) {
		$device = $_GET['device'];
		$retArr[0]['device'] = $device;
		$retArr[0]['temp'] = getTemp( $device );
		
		print_r( json_encode( $retArr ) );
		
	} 
	
	
//} // END else 

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
