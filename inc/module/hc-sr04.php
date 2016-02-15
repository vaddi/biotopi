<?php

//if( isset( $_GET['sid'] ) ) $client_sid = $_GET['sid'];

//if( empty( session_id() ) ) session_start();
//$this_sid = session_id();

//if( $this_sid != $client_sid ) {
//	// noID or wrongID, redirect to mainindex
//	echo "<meta http-equiv='refresh' content='0; url=./' />";
//} else { 
	
		$retArr[0]['name'] = "hc-sr04";
		$retArr[0]['dist'] = getDist(  );
		print_r( json_encode( $retArr ) );
//		echo $_GET['alt'];
	
	
//} // END else 

function getDist(  ) {
	$absolutPath = realpath("../../");
	$distance = shell_exec("sudo ".$absolutPath."/inc/bin/hc-sr04" );
	if( $distance != null ) {
		return $distance;
	} else {
		return false;
	}
}


?>
