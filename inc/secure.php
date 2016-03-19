<?php

// $secLvl must be set and a valid number
if( ! isset( $secLvl ) || ! is_numeric( $secLvl ) ) $secLvl = 0; 

if( $secLvl == 0 ) {
	// validate nothing?
	
}

if( $secLvl >= 1 ) {
	// validate cid token
	$cid = null;
	if( isset( $_REQUEST['cid'] ) && $_REQUEST['cid'] != null && $_REQUEST['cid'] != "" ) {
		$cid = base64_decode( urldecode( substr( $_REQUEST['cid'], 0, -1 ) ) );
	}

	if( $cid !== SERVERTOKEN ) {
		ignore_user_abort(true);
		if( REDIRECT ) {
			$redirectUrl = "../../";
			header("Location: ".$redirectUrl, true);
		} else {
			header( "HTTP/1.1 403 Forbidden" );
		}
		header("Connection: close", true);
		exit;
	}
}

if( $secLvl >= 2 ) {
	// validate login token
	$lid = null;
	if( isset( $_REQUEST['lid'] ) && $_REQUEST['lid'] != null && $_REQUEST['lid'] != "" ) {
		$lid = base64_decode( urldecode( substr( $_REQUEST['lid'], 0, -1 ) ) );
	}

	if( $lid !== SERVERTOKEN ) {
		ignore_user_abort(true);
		if( REDIRECT ) {
			$redirectUrl = "../../";
			header("Location: ".$redirectUrl, true);
		} else {
			header( "HTTP/1.1 403 Forbidden" );
		}
		header("Connection: close", true);
		exit;
	}
}

?>
