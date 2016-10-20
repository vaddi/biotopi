<?php

//require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

$erg = null;
if( isset( $_REQUEST['cmd'] ) ) {
	switch( $_REQUEST['cmd'] ) {
		case "set":
			if( isset( $_REQUEST['relais'] ) ) 
				$erg = relais( $_REQUEST['cmd'], $_REQUEST['relais'] );
			else 
				$erg = false;
			break;

		case "get":
			if( isset( $_REQUEST['relais'] ) ) {
				// TODO, only a specific output
				
				$erg = relais( $_REQUEST['values'], $cmd['relais'] );
			} else {
				$erg = relais( $_REQUEST['cmd'] );
			}
			break;
			
		case "rnd":
			$erg = relais( $_REQUEST['cmd'], $_REQUEST['relais'] );
			break;
		default:
			$erg = null;
			break;
	}
}

$retArr[0]['resp'] = $erg;

// write log
//require_once( '../functions.php');
//if( $erg ) { logger( $erg ); }

// set json header and print output
require_once( 'json_header.php' );

//echo "<pre>";
//print_r( $retArr );
//echo "</pre>";

print_r( json_encode( $retArr ) );

?>
