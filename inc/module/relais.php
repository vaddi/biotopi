<?php

$values = array( 'cmd', 'relais' );

$new_values = null;
foreach ($values as $key => $value) {
	if( isset( $_GET[ $value ] ) ) {
		$new_values[ $value ] = $_GET[ $value ]; 
	} else if( isset( $_POST[ $value ] ) ) {
		$new_values[ $value ] = $_POST[ $value ];
	} else {
		$new_values[ $value ] = null;
	}	
}
$values = $new_values;

$erg = null;
if( isset( $values['cmd'] ) && isset( $values['relais'] ) ) {
	switch( $values['cmd'] ) {
		case 'set':
			$erg = relais( $values['cmd'], $values['relais'] );
			break;
		case 'get':
			$erg = relais( $values['cmd'], $values['relais'] );
			break;
		default:
//			relais( $values['cmd'], $values['relais'] );
			break;
	}
}

$retArr[0]['resp'] = $erg;
print_r( json_encode( $retArr ) );
		

function relais( $cmd = null, $relais = null ) {
	if( $cmd === null || $relais === null /* || ( $relais > 255 || $relais < 0 ) */ ) return false;
	if( $relais > 255 || $relais < 0 ) $relais = 0;
	
	$erg = false;
	$absolutPath = realpath("../../");
	$erg = shell_exec( "sudo $absolutPath/inc/bin/relais $cmd $relais" );

	return $erg;
}


?>
