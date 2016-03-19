<?php

require_once( "../config.php" );
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

// write log
//require_once( '../functions.php');
//if( $erg ) { logger( $erg ); }

//header( 'Access-Control-Allow-Origin: *' );
//header( 'Cache-Control: no-cache, must-revalidate' );
//header( 'Expires: ' . expireDate() );
//header( 'Content-type: application/json; charset=UTF-8' );

$retArr[0]['resp'] = $erg;
print_r( json_encode( $retArr ) );
		

function relais( $cmd = null, $relais = null ) {
	if( $cmd === null /* || ( $relais > 255 || $relais < 0 ) */ ) return false;
	if( $relais !== null && ( $relais > 255 || $relais < 0 ) ) return false;
	
	$erg = false;
	$absolutPath = realpath("../../");
	require_once( '../class/class.File.php' );
	switch( $cmd ) {
		case "set" :
			$value = exec( "sudo $absolutPath/inc/bin/relais $cmd $relais", $msg, $err );
			if( $value == "34344" ) return false; 
			//	www-data need write access to file dir
			$value = File::write( "$absolutPath/inc/tmp/relais.dat", $value );
		break;
		case "get" :
			$value = (int) File::read( "$absolutPath/inc/tmp/relais.dat" );
		break;
		default :
			$value = null;
		break;
	}
	return $value;
}


?>
