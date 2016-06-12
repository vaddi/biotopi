<?php

require_once( '/var/www/inc/functions.php' );

// set json header
header( 'Access-Control-Allow-Origin: *' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Expires: ' . expireDate() );
//header( 'Content-type: application/json; charset=UTF-8' );

?>
