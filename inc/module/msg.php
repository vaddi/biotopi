<?php

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

// get all messages 
//getCliMsg();
			
// get messages by date ( true | unset = after date | false = before date )
//getCliMsgByDate( '06.03.2016 22:50', false );

// get messages from now
//getCliMsgByDate( date( DATEFORM ) ); 

// get message by id
//getCliMsgById( 2 );


// listen url 
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

$retArr[0]['data'] = $erg;

// set json header and print output
require_once( 'json_header.php' );
header( 'Content-type: application/json; charset=UTF-8' );
print_r( json_encode( $retArr ) );

?>
