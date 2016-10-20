<?php

// Set Data to Display

// security level 0 file
$secLvl = 0;

$log = null;

//require_once( "../config.php" );
require_once( '/var/www/inc/functions.php' );
require_once( '/var/www/inc/secure.php' );

//print_r( $_SERVER );
$lcdtext = "";

if( isset( $_REQUEST['text'] ) ) {
//	$text = rawUrlEncode( $_REQUEST['text'] ); 
		$text = $_REQUEST['text'];
} else if( isset( $_SERVER['argc'] ) && $_SERVER['argc'] >= 2 ) {
	// we've been called from cmd line
	$text = $argv[1];
	if( $text == "cron" ) {
		$dht11 = dht11();
		$erg = array( 'air' => map( getAD( 2 ), 0, 1024, 0, 194 ),
									'air0' => round( ds18b20( '28-000004cd81ba' ), 1 ),
									'gndtmp' => round( ds18b20( '28-000004bfd99e' ), 1 ),
									'gndmst' => recode( getAD( 4 ) ),
									'light' => recode( getAD( 3 ) ),
									'uv' => uvState( getAD( 5 ) ),
									'air_hum' => $dht11['rf'],
									'air_temp' => $dht11['temp'],
									'relais' => relais( 'get' ),
									'sr04' => sr04(),
									'bmp085' => bmp085( 70 )									
		);
//		print_r( $dht11 );
		$air_temp = round( ( $erg['air'] + $erg['air0']  + $erg['air_temp']) / 3.0, 1 );
		$line1 = "Air: " . $air_temp . "C | " . $erg['air_hum'] . "%";
		$lcdtext .= $line1 . filler( ( 19 - strlen( $line1 ) ) );
		$line2  = "Ground: " . $erg['gndtmp'] . "C | " . $erg['gndmst'] . "%";
		$lcdtext .= $line2 . filler( ( 19 - strlen( $line2 ) ) );
		$line3  = "Light: " . $erg['light'] . " | R: " . $erg['relais'];
		$lcdtext .= $line3 . filler( ( 19 - strlen( $line3 ) ) );
		$line4  = "UV: " . $erg['uv'] . "|" . $erg['bmp085'] . "hPa";
		$lcdtext .= $line4 . filler( ( 19 - strlen( $line4 ) ) );
	}
	$log = date( 'd.m.Y H:i:s' );
} else {
	$lcdtext = "";
}

// write to display
sendLCD( $lcdtext );

if( isset( $erg ) ) {
	$retArr = $erg;
} else {
	$text = preg_replace( '# {2,}#', ' ', $text );
	$retArr[0]['resp'] = $text;
}

require_once( 'json_header.php' );
if( $text == "cron" ) {
	if( $log !== null ) { 
//		print_r( "[" . $log . "] " ); 
	}
} else {
	print_r( json_encode( $retArr ) );
}

// Helper functions

function map( $x, $in_min, $in_max, $out_min, $out_max ) {
	$erg = ( $x - $in_min ) * ( $out_max - $out_min ) / ( $in_max - $in_min ) + $out_min;
	return round( $erg, 2 );
}

function recode( $value ) {
	if( $value >= 820 ) {
		return 4;
	} else if( $value >= 615 && $value < 820 ) {
		return 3;
	} else if( $value >= 410 && $value < 615 ) {
		return 2;
	} else if( $value >= 250 && $value < 410 ) {
		return 1;
	} else if( $value >= 0 && $value < 250 ) {
		return 0;
	}
}

function lightState( $value ) {
	if( $value >= 820 ) {
		return "very bright";
	} else if( $value >= 615 && $value < 820 ) {
		return "bright";
	} else if( $value >= 410 && $value < 615 ) {
		return "day";
	} else if( $value >= 250 && $value < 410 ) {
		return "dusk";
	} else if( $value >= 0 && $value < 250 ) {
		return "night";
	}
}

// http://www.cutedigi.com/blog/use-uv-sensor-with-arduino/
function uvState( $value ) {
	if( $value >= 240 && $value < 221 ) {
		return 11;
	} else if( $value >= 200 && $value < 221 ) {
		return 10;
	} else if( $value >= 180 && $value < 200 ) {
		return 9;
	} else if( $value >= 162 && $value < 180 ) {
		return 8;
	} else if( $value >= 142 && $value < 162 ) {
		return 7;
	} else if( $value >= 124 && $value < 142 ) {
		return 6;
	} else if( $value >= 103 && $value < 124 ) {
		return 5;
	} else if( $value >= 83 && $value < 103 ) {
		return 4;
	} else if( $value >= 65 && $value < 83 ) {
		return 3;
	} else if( $value >= 46 && $value < 65 ) {
		return 2;
	} else if( $value >= 10 && $value < 46 ) {
		return 1;
	} else if( $value >= 0 && $value < 10 ) {
		return 0;
	}
}

function moistureState( $value ) {
	if( $value >= 820 ) {
		return "very dry";
	} else if( $value >= 615 && $value < 820 ) {
		return "dry";
	} else if( $value >= 410 && $value < 615 ) {
		return "ok";
	} else if( $value >= 250 && $value < 410 ) {
		return "wet";
	} else if( $value >= 0 && $value < 250 ) {
		return "very wet";
	}
}

function filler( $num = null ) {
	if( $num == null ) return;
	$str = "";
	for ( $i = 0; $i <= $num; $i++ ) $str .= " ";
	return $str;
}

?>
