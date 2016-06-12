<?php

require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

// set json header and print output
require_once( __DIR__ . '/json_header.php' );



$retArr[0]['data'] = sendMAIL( 'v4d@me.com', 'Testnachricht', 'Testsubject' );



?>
