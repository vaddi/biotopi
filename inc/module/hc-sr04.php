<?php

// Get Data from hs-sr04 

//require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

$retArr[0]['name'] = "hc-sr04";
$retArr[0]['dist'] = getDist(  );

// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );


// Helper functions

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
