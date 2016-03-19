<?php

// Get Data from bmp085

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

	
if( isset( $_GET['alt'] ) ) {
	$alt = $_GET['alt'];
} else if ( isset( $_POST['alt'] ) ) {
	$alt = $_POST['alt'];
} else if( isset( $argv[1] ) ) {
	$alt = $argv[1];	// we've been called from cmd line
	print_r( "[ " . date( 'd.m.Y H:i:s' ) . " ] " );
} else {
	$alt = 70; // default 
}

$retArr[0]['alt'] = $alt;
$retArr[0]['pa'] = getPa( $alt );

// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) . "\n" );
	

// Helper functions	

function getPa( $alt = null ) {
	if( $alt != null ) {
		$absolutPath = realpath("../../");
		$altitude = (int) shell_exec("sudo ".$absolutPath."/inc/bin/bmp085 $alt" );
		$altitude = $altitude / 100;

		if( $altitude != null ) {
			
			$tmpfile = $absolutPath."/inc/tmp/bmp085.dat";
			$filetime = filemtime( $tmpfile );
			$now = time();
			
			// 3600s = 1h
			if( ( $now - 60 ) > $filetime ) {

				// get old data 
				require_once( '../class/class.File.php' );
				$tmparr = json_decode( File::read( $tmpfile ) );
				
				// if no data available, set default (new file etc.)
				if( ! isset( $tmparr ) ) $tmparr = array( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
				
				// shift Data in to array
				$tmparr = arrShifter( $tmparr, $altitude );
				
				// update bmp file data (json encode)
				File::write( $tmpfile, json_encode( $tmparr ) );
			
			}
			
			return $altitude;
		} else {
			return false;
		}
	} 
}


?>
