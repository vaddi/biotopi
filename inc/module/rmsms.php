<?php

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

if( isset( $_REQUEST['file'] ) && $_REQUEST['file'] !== null && $_REQUEST['file'] !== "" && 
		isset( $_REQUEST['folder'] ) && $_REQUEST['folder'] !== null && $_REQUEST['folder'] !== "" ) {
	
	$in = array(  );
	$out = array(  );
	
	$file = '/var/spool/gammu/' . escapeshellarg( $_REQUEST['folder'] ) . '/' . escapeshellarg( urldecode( $_REQUEST['file'] ) );
	$erg = exec( 'sudo /bin/rm ' . $file, $msg, $err );
	if( $err == 0 ) {
		$erg = true;
	} else {
		$erg = false;
	}
} else {
	$erg = null;
}


header( 'Access-Control-Allow-Origin: *' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Expires: ' . expireDate() );
header( 'Content-type: application/json; charset=UTF-8' );

$retArr[0]['data'] = $erg;
print_r( json_encode( $retArr ) );

?>
