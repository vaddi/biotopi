<?php

require_once( "../inc/config.php" );
require_once( '../inc/functions.php' );

// validate cid
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

// get all messages 
//getCliMsg();
			
// get messages by date ( true | unset = after date | false = before date )
//getCliMsgByDate( '06.03.2016 22:50', false );

// get messages from now
//getCliMsgByDate( date( DATEFORM ) ); 

// get message by id
//getCliMsgById( 2 );


// listen url 
// id 	 -> get by id
// date  -> get orders behind (old = before) a given date
// order -> get (all|init) orders

if( isset( $_REQUEST['id'] ) ) {
	$erg = getCliMsgById( $_REQUEST['id'] );
} else if( isset( $_REQUEST['date'] ) ) {
	if( isset( $_REQUEST['order'] ) && $_REQUEST['order'] == "old" ) {
		$erg = getCliMsgByDate( $_REQUEST['date'], false );
	} else {
		$erg = getCliMsgByDate( $_REQUEST['date'], true );
	}
} else {
	if( isset( $_REQUEST['order'] ) && $_REQUEST['order'] == 'init'  ) {
		// init 
//		$erg = getCliMsg();
		$erg = getCliMsgByDate( date( 'd.m.Y H:i' ), true );
	} else {
//		$erg = null;
		$erg = getCliMsg();
	}
}

header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sun, 1 Jan 2017 00:00:01 GMT');
header('Content-type: application/json; charset=UTF-8');

$retArr[0]['data'] = $erg;
print_r( json_encode( $retArr ) );

?>
