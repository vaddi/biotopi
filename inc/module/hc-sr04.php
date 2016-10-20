<?php

// Get Data from hs-sr04 

require_once( '../functions.php' );
require_once( '../secure.php' );

$retArr[0]['name'] = "hc-sr04";
$retArr[0]['dist'] = sr04();

// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );

?>
