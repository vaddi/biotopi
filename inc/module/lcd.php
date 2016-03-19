<?php

// Set Data to Display

// security level 0 file
$secLvl = 1;

require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );
	
if( isset( $_GET['text'] ) ) {
	$text = rawUrlEncode( $_GET['text'] ); 
//		$text = $_GET['text'];
} else if( isset( $_POST['text'] ) ) {
	$text = rawUrlDecode( $_POST['text'] );
//		$text = $_POST['text'];
} else {
	$text = null;
}

// bei \n anzahl an freizeichen bis % 20 == 0
$text = textForm( $text );

// write log
if( $retArr[0]['resp'] ) { logger( $text ); }
$retArr[0]['resp'] = display( $text );

// set json header and print output
require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );
	

// Helper functions

function display( $text = null ) {
	$erg = null;
	if( $text != null ) {
		$erg = false;
		$absolutPath = realpath("../../");
		$cmd = exec( "sudo /usr/bin/python ".$absolutPath."/inc/bin/other/lcd.py '".$text."'", $msg, $err );
		if($err == 0) {
			$erg = true;
		}
		if($text == "") {
			$erg = null;
		}
	}
	return $erg;
}

function filler( $num = null ) {
	if( $num == null ) return;
	$str = "";
	for ( $i = 0; $i <= $num; $i++ ) $str .= " ";
	return $str;
}

function textForm( $text = null, $string = null ) {
	if( $text === null ) return;
	if( $string === null ) $string = "%0A";
//	if( $string === null ) $string = "\n";
	$space = 0;
	
	// http://www.w3schools.com/tags/ref_urlencode.asp
	$in = array( '%3D', '%20', '%21', '%26', '%C3%B6', '%C3%A4', '%C3%BC', '%C3%96', '%C3%84', '%C3%9C' );
	$out = array( '=', ' ', '!', '&', 'oe', 'ae', 'ue', 'Oe', 'Ae', 'Ue' ); 
	$text = str_replace( $in, $out, $text );
	$text = str_replace( '%0A', 'f', $text);
	
//  // str2arr
//	$textArr = str_split( $text );
//	$last = count( $textArr );
//	foreach( $textArr as $pos => $char ) {
////		if( $char == "\n" ) { /* expand until line has 20 chars - current pos */ error_log("current pos: \"" . $i . "\"", 0); }

////		if( $char . $char[ $pos +1 ] . $char[ $pos +2 ] == "%0A" ) {
////			echo $pos;
////		}
//		if( $char == '%' ) { 
//			if( isset( $textArr[ $pos +1 ] ) && isset( $textArr[ $pos +2 ] ) ) {
//				$tmpStr = $char . $textArr[ $pos +1 ] . $textArr[ $pos +2 ];
//				if( $tmpStr == '%0A' ) {
//					echo $pos . " ";
//				}
//			}
//		}
////		echo $char;
//	}
////echo strpos( $text, '%0A' );


//	// line
//	substr( $text, 0, 20 ) >= 0 ? $tmpText[] = substr( $text, 0, 20 ) : "";
//	substr( $text, 20, 20 ) >= 0 ? $tmpText[] = substr( $text, 20, 20 ) : "";
//	substr( $text, 40, 20 ) >= 0 ? $tmpText[] = substr( $text, 40, 20 ) : "";
//	substr( $text, 60, 20 ) >= 0 ? $tmpText[] = substr( $text, 60, 20 ) : "";
//	
//	foreach ($tmpText as $key => $value) {
//		$text = str_replace( $in, $out, $text );
//	}

	
//	$text = replacer( $text, $string );
//echo $text;
  // strpos_r
//	$return = "";
//	foreach ( strpos_r($text, $string) as $key => $pos) {
//		$return .= str_replace( $string, filler( 20 - $pos ), $text );
//		echo 20 - $pos . " ";
//	}
//	print_r($return);
//	echo $text;
//	echo filler( 5 ) . "test";
//	return $return;


//  // str2arr
//	$textArr = str_split( $text );
//	foreach( $textArr as $char ) {
//		if( $char == "\n" ) { /* expand until line has 20 chars - current pos */ error_log("current pos: \"" . $i . "\"", 0); }
////		error_log("current pos: \"" . $textArr[ $i ] . "\"", 0);
////		error_log("found newline on pos: \"" . $i . "\"", 0);
//	}
	

//	
//	foreach( $tmpText as $key => $row ) {
//		
//		$tmpText[ $key ] = str_replace( "\n", filler( $space ), $text );
//	}
	
//	$text = "";	
//	foreach( $tmpText as $rowNum => $row ) {
//		$textArr = str_split( $row ); // string2array
//		$tmp = "";
//		for ( $i = 0; $i < count( $textArr ); $i++ ) {
//		
//			$tmpStr = isset( $textArr[ $i ] ) ? $textArr[ $i ] : '';
//			$tmpStr .= isset( $textArr[ $i +1 ] ) ? $textArr[ $i +1 ] : '';
//			if( $tmpStr  == "\\n" ) { /* expand until line has 20 chars - current pos */ error_log("found return: " . $i, 0); }
////			error_log("str: " . $tmpStr , 0);
////			error_log("row: " . $rowNum . " pos: \"" . ( count( $textArr ) - $i ) . "\" content: " . $textArr[$i], 0);
//			$tmp .= $textArr[ $i ];
//		}
//		$text .= $tmp;
//	}
////	error_log("TXT2LCD: \"" . $text  . "\"", 0);
////	error_log("TXT2LCD: \"" . $text . "\"", 0);
	return $text;
}

function strpos_r($haystack, $needle) {
    if(strlen($needle) > strlen($haystack)) trigger_error(sprintf("%s: length of argument 2 must be <= argument 1", __FUNCTION__), E_USER_WARNING);

    $seeks = array();
    while($seek = strrpos($haystack, $needle)) {
        array_push($seeks, $seek);
        $haystack = substr($haystack, 0, $seek);
    }
//    print_r( $seeks );
		$seeks = array_reverse( $seeks );
    return $seeks;
}

// num of spaces till % 20 == 0
function numspaces( $text, $line ) {
	$textArr = str_split( $text );
	foreach( $textArr as $char ) {
//		if( $char == "\n" ) { /* expand until line has 20 chars - current pos */ error_log("current pos: \"" . $i . "\"", 0); }
		
	}
	
}

function replacer( $text, $string ) {
	$return = "";
	$found = strpos_r($text, $string);
	$offset = 21;
//	print_r( $found );
//	$offset = count( $found ) + 20;
//	$offset = 20;
	foreach ( $found as $key => $pos) {
		
//		// define offset (20x4)
//		if( $pos <= 20 ) { $offset = 20; }
//		else if( $pos <= 40 ) {	$offset = 40; }
//		else if( $pos <= 60 ) { $offset = 60;	}
//		else if( $pos <= 80 ) { $offset = 80;	} 
//		else { $offset = 20; }
		

			$offset = $offset +20;
			
			$start = $pos ;
			$length = strlen($string);
			
//			$text = substr_replace($string,filler( $offset - $start ),$start,$length); 
//			$return = putinplace($text,filler($offset - $pos),$pos);
//		if( $key > 1 ) $offset = $offset -1;
//		$offset = $offset - ( $pos / strlen( $string ) );
//		echo $pos . " ";
//		if( $key > 0 ) continue;
//		$return .= str_replace( $string, filler( $offset - $pos ), $text );
			$return = $text;
	}
	
	$return=str_replace( $string, "", $return); // clean the text
	print_r( $return );
	return $return;
}

function putinplace($string=NULL, $put=NULL, $position=false)
{
    $d1=$d2=$i=false;
    $d=array(strlen($string), strlen($put));
    if($position > $d[0]) $position=$d[0];
    for($i=$d[0]; $i >= $position; $i--) $string[$i+$d[1]]=$string[$i];
    for($i=0; $i<$d[1]; $i++) $string[$position+$i]=$put[$i];
    return $string;
}

?>
