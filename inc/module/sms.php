<?php

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

if( isRunning( 'gammu-smsd' ) ) {
	
	$log = null;
	
	$smssendfile = defined( 'SMSSENTFILE' ) && SMSSENTFILE !== "" ? SMSSENTFILE : 'sentstate.dat';
	$smsreceivedfile = defined( 'SMSRECEIVEDFILE' ) && SMSRECEIVEDFILE !== "" ? SMSRECEIVEDFILE : 'receivedstate.dat';
	$smsfailedfile = defined( 'SMSFAILEDFILE' ) && SMSFAILEDFILE !== "" ? SMSFAILEDFILE : 'failedstate.dat';
	$smssignalfile = defined( 'SMSSIGNALFILE' ) && SMSSIGNALFILE !== "" ? SMSSIGNALFILE : 'signalstate.dat';
	
	if( isset( $argv[1] ) && $argv[1] !== null && $argv[1] !== "" ) {
		// run from cron
		$log = "[" . date( 'd.m.Y H:i:s' ) . "] ";
		$erg[ 'sent' ] = getData( $smssendfile, 'Sent' );
		$erg[ 'received' ] = getData( $smsreceivedfile, 'Received' );
		$erg[ 'failed' ] = getData( $smsfailedfile, 'Failed' );
		$erg[ 'signal' ] = getData( $smssignalfile, 'NetworkSignal' );
		
	} else if( isset( $_REQUEST['cmd'] ) && $_REQUEST['cmd'] !== null && $_REQUEST['cmd'] !== "" ) {
		// command delete
		switch( $_REQUEST['cmd'] ) {
		
			// delete one file
			case "delfile":
				if( isset( $_REQUEST['file'] ) && $_REQUEST['file'] !== null && $_REQUEST['file'] !== "" && isset( $_REQUEST['folder'] ) && $_REQUEST['folder'] !== null && $_REQUEST['folder'] !== "" ) {
					$file = '/var/spool/gammu/' . escapeshellarg( $_REQUEST['folder'] ) . '/' . escapeshellarg( urldecode( $_REQUEST['file'] ) );
					exec( 'sudo /bin/rm ' . $file, $msg, $err );
					if( $err == 0 ) $erg = true;
						else $erg = false;
				}
				break;
		
			// delete all files in folder
			case "delfolder":
				if( isset( $_REQUEST['folder'] ) && $_REQUEST['folder'] !== null && $_REQUEST['folder'] !== "" ) {
					$folder = '/var/spool/gammu/' . escapeshellarg( $_REQUEST['folder'] ) . '/*';
					exec( 'sudo /bin/rm ' . $folder, $msg, $err );
					if( $err == 0 ) $erg = true;
						else $erg = false;
				}
				break;
			
			// Get gammu sentstate data
			case "sentstate":
//				$erg = getData( 'sentstate.dat', 'NetworkSignal' );
				$erg[ 'sent' ] = getData( $smssendfile, 'Sent' );
				$erg[ 'received' ] = getData( $smsreceivedfile, 'Received' );
				$erg[ 'failed' ] = getData( $smsfailedfile, 'Failed' );
				$erg[ 'signal' ] = getData( $smssignalfile, 'NetworkSignal' );
				break;
			
			default:
				// unknown command
				$erg = gammuData( 'Sent' );
				
		} // end switch
		
	} else {
	
		// no command given
		$erg = null;
		
	}

} else {
	// gammu not running
	$erg = null;
}

$retArr[0]['data'] = $erg;
$retArr[0]['state'] = isRunning( 'gammu-smsd' );

// set json header and print output
require_once( 'json_header.php' );
if( $log !== null ) print_r( $log );
print_r( json_encode( $retArr ) );


// Helper functions	

function getData( $file = null, $data = null ) {
	if( $file === null || $data === null ) return;
	$absolutPath = realpath("../../") == "/" ? __DIR__ ."/../.." : realpath("../../");
	if( SENTHIST > 0 ) {
		$tmpfile = $absolutPath."/inc/tmp/".$file;
		$filetime = filemtime( $tmpfile );
		$now = time();
		$sent = (int) gammuData( $data );
		$sent = is_numeric( $sent ) ? $sent : null;
		// get old data 
		require_once( $absolutPath . '/inc/class/class.File.php' );
		$tmparr = json_decode( File::read( $tmpfile ) );
		// if no data available, set default (new file etc.)
		if( ! isset( $tmparr ) ) $tmparr = array( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
		// 3600s = 1h
		if( ( $now - ( SENTHIST * 30 ) ) > $filetime ) {
			// shift Data in to array
			$tmparr = arrShifter( $tmparr, $sent );
			// update bmp file data (json encode)
			File::write( $tmpfile, json_encode( $tmparr ) );
		} 
		return $tmparr;
	} else {
		return $tmparr;
	}
}

?>
