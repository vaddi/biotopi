<?php

// Get Data from mcp3008

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

$log = null;

if( isset( $_REQUEST['id'] ) ) {
	$id = $_REQUEST['id'];
} else if( isset( $_SERVER['argc'] ) && $_SERVER['argc'] >= 2 ) {
	// we've been called from cmd line
	if( isset( $argv[1] ) && ( $argv[1] <= 9 && $argv[1] >= 0 ) ) {
		$id = $argv[1];
	} else {
		// get all
		$id = null;
	}
	
	$log = "[" . date( 'd.m.Y H:i:s' ) . "] ";
} else {
	$id = null; // default 
}

if( $id === null ) {
	// get all
	for ( $i = 0; $i <= 7; $i++ ) {
		$retArr[ $i ]['id'] = $i;
		$retArr[ $i ]['value'] = getAD( $i );
	}
} else {
	// get by id
	$retArr[0]['id'] = $id;
	$retArr[0]['value'] = getAD( $id );
}

// set json header and print output
require_once( __DIR__ . '/json_header.php' );
if( $log !== null ) print_r( $log );
print_r( json_encode( $retArr ) . "\n" );
	

// Helper functions	

function getAD2( $id = null ) {
	if( $id === null ) { $id = ""; } else { $id = " " . $id; }
	
	$absolutPath = realpath("../../") == "/" ? __DIR__ ."/../.." : realpath("../../");
	$value = ( (int) shell_exec("sudo " . $absolutPath . "/inc/bin/mcp3008$id" ) );

//	if( MCP3008HIST > 0 ) {
//		$tmpfile = $absolutPath . "/inc/tmp/" . MCP3008FILE;
//		$filetime = filemtime( $tmpfile );
//		$now = time();
//	
//		// 3600s = 1h
//		if( ( $now - ( MCP3008HIST * 60 ) ) > $filetime ) {

//			// get old data 
//			require_once( $absolutPath . '/inc/class/class.File.php' );
//			$tmparr = json_decode( File::read( $tmpfile ) );
//		
//			// if no data available, set default (new file etc.)
//			if( ! isset( $tmparr ) ) $tmparr = array( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
//		
//			// shift Data in to array
//			$tmparr = arrShifter( $tmparr, $value );
//		
//			// update bmp file data (json encode)
//			File::write( $tmpfile, json_encode( $tmparr ) );
//	
//		}
//	}

	return $value;
}

?>
