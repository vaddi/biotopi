<?php

// Simple Functions Collection

// PHP File includer
function incl($file) {
	if (file_exists($file)) {
		return include $file;
	} else {
		 return $file . " doesn't exists! \n";
	}
}

require_once( '/var/www/inc/init.php' );

function arrSort( $array, $string ) {
	if( ! is_array( $array ) || $array == null || $string === null ) return $array;
	$erg = null;
	foreach ( $array as $key => $value ) {
		if( strpos( $value, $string ) !== false ) {
			$erg[] = $value;
			unset( $array[ $key ] );
		}
	}
	$erg = array_merge($erg, $array);
	return $erg;
}

function getAmount( $array, $string = null ) {
	if( ! is_array( $array ) || $array === null || $string === null ) return;
	$erg = 0;
	foreach ( $array as $key => $value ) {
		if( strpos( $value, $string ) !== false ) {
			$erg++;
		}
	}
	return $erg;
}

function arr2str( $array ) {
	if( ! is_array( $array ) || empty( $array ) ) return;
	$str = "";
	$curr = 0;
	$total = count( $array );
	foreach ($array as $key => $value) {
		$str .= $value . $curr++ >= $total ? "" : ", ";
	}
	return $str;
}

function arrShifter( $array = null, $value = null, $max = null ) {
	if( $array === null || ! is_array( $array ) || $value === null ) return;
	if( $max === null ) $max = count( $array );
	for( $i = 0; $i < $max; $i++ ) {
		if( ( $i + 1 ) < $max ) {
			$array[ $i ] = $array[ $i + 1 ];
		} else {
			$array[ $i ] = $value;
		}
	}
	return $array;
}

function colorize( $string = null, $element = null, $value = null, $title = null ) {
	if( $string === null ) return;
	if( $element === null ) $element = 'span';
	if( $value === null ) $value = '#666';
	if( $title === null ) $title = ''; else $title = ' title="' . $title . '"';
	return '<' . $element . ' style="color: ' . $value . ';"' . $title . '>' . $string . '</' . $element . '>';
}

// check for running process, by name or pid
function isRunning( $proc = null ) {
	if( $proc === null ) return;
	exec( 'ps -ef |grep -v grep |grep -cw ' . $proc, $msg, $err );
	if( $err == 0 ) {
		if( is_numeric( $msg[0] ) && $msg[0] > 0 ) {
			return true; 
		} else {
			return false;
		}
  } else {
    return false;
  }
}

// sort array by function
// usort( $array, "cmp");
function cmp( $a, $b ) {
	// get key from value with contains "min"
	$key = array_search ('min', $arr);
  return strcmp( $a[ $key ], $b[ $key ] );
}

function setCss( $path ) {
	if( $path === null ) return;
	$path = substr( $path, -1 ) == '/' ? $path : $path . '/';
	$verzeichnis_glob = glob( $path . "*.css", GLOB_NOSORT );
	$verzeichnis_glob = arrSort( $verzeichnis_glob, 'min' ); // sort %min% to position 1
	foreach($verzeichnis_glob as $key => $file) {
		echo '<link href="' . $file . '" rel="stylesheet">'."\n";
	}
}

function setJs( $path ) {
	if( $path === null ) return;
	$path = substr( $path, -1 ) == '/' ? $path : $path . '/';
	$verzeichnis_glob = glob( $path . "*.js", GLOB_NOSORT );
	$verzeichnis_glob = arrSort( $verzeichnis_glob, 'sparkline' );			// sort %sparkline% to position 1
	$verzeichnis_glob = arrSort( $verzeichnis_glob, 'min' ); 						// sort %min% to position 1
	$verzeichnis_glob = arrSort( $verzeichnis_glob, 'bootstrap.min' );	// sort %bootstrap% to position 1 
	$verzeichnis_glob = arrSort( $verzeichnis_glob, 'jquery' );					// sort %jquery% to position 1
	foreach($verzeichnis_glob as $key => $file) {
		echo '<script type="text/javascript" src="' . $file . '"></script>'."\n";
	}
}

// http://stackoverflow.com/a/13541857
function hex2str( $hex ) {
  return pack('H*', $hex);
}

function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
}


function logger( $msg = null ) {
	if( $msg === null ) return;
//	require_once( 'class/class.Log.php' );
//	
//	$msg = "[ " . date( DATEFORM ) . " ] ". $msg;
//	$file = realpath('../')."/tmp/biotopi.log";
//	return Log::write( $msg, $file );
	
//	$logger = new Log();
//	return $logger->write( $msg, $file );
}

//function getData( $array = null, $skey = null ) {
//	if( $array === null || ! is_array( $array ) || $skey === null ) return;
//	$erg = false;
//	foreach ( $array as $key => $value ) {
//		if( is_array( $value ) ) {
//			foreach ( $value as $innerkey => $innervalue ) {
////				if ( strpos( $innerkey, $skey ) !== false ) {
////				if( preg_match( "/\b" . $skey . "\b/i", $innerkey ) ) {
//				if ( $innerkey == $skey ) {
//					$erg = $key;
//				}
//			}
//		} 
//		else {
//			if ( $key == $skey ) {
//				$erg = $key;
//			}
//		}
//	}
//	return $erg;
//}

function getLastId( $array = null ) {
	if( $array === null || ! is_array( $array ) ) return;
	$erg = false;
	$last = count( $array ) -1;
	foreach ( $array as $key => $value ) {
		if( $key == $last )	$erg = $key;
	}
	return $erg;
}

function filter_html($html) {
	$html = str_replace('<','&lt;',$html);
	$html = str_replace('>','&gt;',$html);
	return $html;
}

function convertDate( $dateString, $format ) {
	$myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
  return $myDateTime->format($format);
}

function germanDay( $day = null, $fomat = null ) {
	// get Weekday from number 
	// 0 = Sunday
	// 1 = Monday
	// 2 = Tuesday
	// 3 = Wednesday
	// 4 = Thursday
	// 5 = Friday
	// 6 = Saturday
	
	if( $day === null ) $day = date( "w" );
	if( $day < 0 || $day > 6 ) return;
	if( $format === null ) $format = 's'; // s = Short, l = Long, m = Middle
	
	switch( $day ) {
		case 'Su' || 'Sun' || '0':
			$short = 'So';
			$midd = 'Son';
			$long = 'Sonntag';
		break;
		case 'Mo' || 'Mon' || '1':
			$short = 'Mo';
			$midd = 'Mon';
			$long = 'Montag';
		break;
		case 'Tu' || 'Tue' || '2':
			$short = 'Di';
			$midd = 'Die';
			$long = 'Dienstag';
		break;
		case 'We' || 'Wed' || '3':
			$short = 'Mi';
			$midd = 'Mit';
			$long = 'Mittwoch';
		break;
		case 'Th' || 'Thu' || '4':
			$short = 'Do';
			$midd = 'Don';
			$long = 'Donnerstag';
		break;
		case 'Fr' || 'Fri' || '5':
			$short = 'Fr';
			$midd = 'Fre';
			$long = 'Freitag';
		break;
		case 'Sa' || 'Sat' || '6':
			$short = 'Sa';
			$midd = 'Sam';
			$long = 'Samstag';
		break;
		default :
			$short = 'So';
			$midd = 'Son';
			$long = 'Sonntag';
		break;		
	}
	if( $format === 's' ) return $short;
	if( $format === 'm' ) return $midd;
	if( $format === 'l' ) return $long;
}

function germanMonth( $month = null, $format = null ) {
	if( $month === null ) $month = date( 'm' );
	if( $format === null ) $format = 'm';
	$month = date( "F", mktime( 0, 0, 0, $month, 10 ) );
	if( $format === 's' ) return substr( $month, 0, 2 );
	if( $format === 'm' ) return substr( $month, 0, 3 );
	if( $format === 'l' ) return $month;
}

function expireDate() {
	return germanDay() . ", " . date( 'd' ) . " " . germanMonth() . " " . date( 'Y' ) . " " . ( ( date( 'H' ) +1 ) < 10 ? '0' . ( date( 'H' ) +1 ) : ( date( 'H' ) +1 ) ) . ":00:00 GMT";
}

function sanitize( $string ) {
	// simple character replacement
  $in  = array( 'ö',  'ä',  'ü',  'Ö',  'Ä',  'Ü',  'ß'  );
  $out = array( 'oe', 'ae', 'ue', 'Oe', 'Ae', 'Ue', 'sz' );
  return str_replace( $in, $out, $string );
}


function htmlMsg( $name = null, $text = null, $type = null, $timeout = null ) {
  if( $name === null ) return;
  if( $text === null ) return;
  if( $timeout === null ) $timeout = 0;
//  if( $type === null ) $type = 'info';
  switch( $type ) {
  	case 'success':
  		
  		break;
  	case 'info':
  		
  		break;
  	case 'warning':
  		
  		break;
  	case 'danger':
  		
  		break;
  	default :
  		$type = 'info';
  		break;
  }
  return '<div id="alert-' . $type . '" class="alert alert-' . $type . ' fade in" data-time="' . $timeout . '"><button class="close" data-dismiss="alert" aria-label="close">&times;</button><strong>' . $name . '</strong> ' . $text . '</div>';
}

function sendMAIL( $recipient = null, $msg = null, $subject = null ) {
  if( $recipient === null || $msg === null ) return false;
  if( $subject === null || $subject == "" ) $subject = "Message from your RaspberryPi";
  
  $msg = sanitize( $msg );
  
	// http://email.about.com/od/emailprogrammingtips/qt/PHP_Email_SMTP_Authentication.htm
	require_once "Mail.php";
//	require_once "secure.php"; // DEPRECATED should be load from config 
	
	$from = "Raspberry Pi <mvattersen@gmail.com>";
	$to = explode( '@', $recipient )[0] . " <" . $recipient . ">";

	$host = MAILHOST;
	$username = MAILUSER;
	$password = MAILPASS;	
	$protocol = "ssl";
	$port = "465";
	$smtp = $protocol . "://" . $host . ":" . $port;

	$headers = array ('From' => $from,
	 'To' => $to,
	 'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	 array ('host' => $smtp,
		 'auth' => true,
		 'username' => $username,
		 'password' => $password));

	$mail = $smtp->send($to, $headers, $msg);

	if ( PEAR::isError($mail) ) {
		error_log( 'Fail send mail to ' . $recipient . ' msg:' . var_dump( $msg ), 0 );
//		logger( $mail->getMessage() );
		return false;
	} else {
		error_log( "Send E-Mail to " . $recipient, 0 );
		return true;
	}
	
}

function sendSMS( $phone = null, $msg = null ) {
  if( $phone === null || $msg === null ) return false;
  $msg = sanitize( $msg );
  // if uset -text -> max lengt ... 70 chars
  exec( '/bin/echo ' . $msg . ' | sudo /usr/bin/gammu-smsd-inject TEXT ' . $phone . ' -len 400', $msg, $err );
//  print_r( $msg ); 
  if( $err == 0 ) {
  	error_log( "Send SMS to " . $phone, 0 );
    return true; 
  } else {
//  	logger( arr2str( $msg ) );
    return false;
  }
}

function sendLCD( $msg = null ) {
  if( $msg === null ) return false;
  
  $msg = sanitize( $msg );
  
  exec( "/usr/bin/python ". BASEPATH ."/inc/bin/other/lcd.py '".$msg."'", $msg, $err );
  if( $err == 0 ) {
    return true; 
  } else {
//  	error_log( arr2str( $msg ), 0 );
  	logger( arr2str( $msg ) );
    return false;
  }
}

function getCliMsgByDate( $date, $direction = null ) {
	if( $date === null ) return;
	if( $direction === null || $direction === true ) $direction = true; else $direction = false; // true = after given | false = before given
	$erg = null;
	$tmpfile = BASEPATH . '/' . CLIENTMSGFILE;
	require_once( 'class/class.File.php' );
	// read old data
	$tmpdata = json_decode( File::read( $tmpfile ) );
	foreach ( $tmpdata as $tmpkey => $tmpvalue ) {
		foreach ( $tmpvalue as $key => $value ) {
			if( $key == 'date' ) {
				
				$datein = strtotime( $date );
				$datemsg = strtotime( $value );
				$futuredate = strtotime( $date ) +1 * 60 * 60;
				$futuremsg = strtotime( $value ) -1 * 60 * 60;
//				$now = strtotime( 'today' );
					
					
//					echo "<pre>";
//					print_r( date("d.m.Y H:i:s",$datemsg) . " -> " . $datemsg . ' Msg' );
//					echo "<br />";
//					print_r( date("d.m.Y H:i:s",$datein) . " -> " . $datein . ' In' );
//					echo "<br />";
//					print_r( date("d.m.Y H:i:s",$futuremsg) . " -> " . $futuremsg . ' futureMsg' );
//					echo "<br />";
//					print_r( date("d.m.Y H:i:s",$futuredate) . " -> " . $futuredate . ' futureIn' );
//					echo "</pre>";
					
//				echo "<pre>";
//				print_r( $datein . ' Date in<br>' );
//				print_r( $datemsg . ' Date msg<br>' );
//				print_r( $futuredate . ' Date future<br>' );
//				print_r( ( $datemsg - $futuredate ) . ' erg<br>' );
//				echo "</pre>";
				
//				if( isFuture( $value ) ) continue;
//				if( $datemsg <= $futuredate ) { continue; }
				
//				if( ! isToday( $datemsg ) ) continue;
//				if( $futuredate <= $datemsg ) { continue; }
//				if( $futuremsg >= $datein ) { echo "FUTURE<br />"; continue; }
					if( $datemsg > $futuredate ) { continue; }
				
				if( $direction ) {
					// compare dates and get all after date
					// TODO exclude future messages if( msgdate <= now + 1h )
					
					
					if( $datemsg >= $datein ) {
//						print_r( 'msg older than date <br>');
//						if( isFuture( $date ) ) continue;
//						if( $datemsg <= $futuredate ) { continue; }
						$erg[] = $tmpvalue;
					} 
				} else {
					// compare dates and get all before date
					if( $datemsg < $datein ) {
//						print_r( 'msg newer than date <br>');
						$erg[] = $tmpvalue;
					}
				}
			}
		}
	}
	return $erg;
}

function getCliMsgById( $id ) {
	if( $id === null ) return;
	$erg = null;
	$tmpfile = BASEPATH . '/' . CLIENTMSGFILE;
	require_once( 'class/class.File.php' );
	// read old data
	$tmpdata = json_decode( File::read( $tmpfile ) );
	foreach ( $tmpdata as $tmpkey => $tmpvalue ) {
		foreach ( $tmpvalue as $key => $value ) {
			if( $key == 'id' ) {
				if( $value == $id ) $erg = $tmpvalue;
			}
		}
	}
	return $erg;
}

function getCliMsg() {
	$erg = null;
	$tmpfile = BASEPATH . '/' . CLIENTMSGFILE;
	require_once( 'class/class.File.php' );
	// TODO 
	return json_decode( File::read( $tmpfile ) );
}

function saveCliMsg( $msg = null, $title = null, $type = null, $date = null ) {
	if( $msg === null ) return false;
	if( $type === null ) $type = "default";
	if( $title === null || $title === "" ) $title = ucfirst( $type );
	if( $date === null ) $date = date( DATEFORM );
	$tmpfile = BASEPATH . '/' . CLIENTMSGFILE;
	require_once( 'class/class.File.php' );
	// read old data
	$tmpdata = json_decode( File::read( $tmpfile ) );
	// empty array on initial
	$tmpdata = isset( $tmpdata ) && is_array( $tmpdata ) && $tmpdata !== null ? $tmpdata : array();
	// Get last id and set to 0 on initial
	$id = getLastId( $tmpdata );
	$id = $id === false ? 0 : $id +1;
	$uid = uniqid();
	$newdata = array( 'id'		=> $id,
										'uid'		=> $uid,
										'date'	=> $date,
										'type'	=> $type,
										'title' => $title,
										'msg'		=> $msg
	);
	array_push( $tmpdata, $newdata );
	// write data (json encode)
	$erg = File::write( $tmpfile, json_encode( $tmpdata ) );
	if( $erg ) {
    return true; 
  } else {
//  	error_log( arr2str( $msg ), 0 );
    return false;
  }
}



function isToday( $time ) { // midnight second
    return ( strtotime( $time ) === strtotime( 'today' ) );
}

function isPast( $time ) {
    return ( strtotime( $time ) < time() );
}

function isFuture($time) {
    return ( strtotime( $time ) > time() );
}



function inlinelinks($description) {
  $description = preg_replace('#http://(player\.)?vimeo\.com/video/(\d+)#', '[vimeo=$2]', $description); # vimeo id
  $description = preg_replace('~[^\s]*youtube\.com[^\s]*?v=([-\w]+)~','[youtube=$1]', $description); # youtube id
//  $description = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~","<a href=\"\\0\" target=\"_blank\">\\0</a>", $description); # Links klickbar machen
	$description = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",'<a href="\\0" target="_blank">\\0</a>', $description); # Links klickbar machen
  $description = preg_replace('/\[vimeo\=(.+?)]/s','<iframe src="http://player.vimeo.com/video/$1" width="350" height="197" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',$description); # vimeo frame
  $description = preg_replace('/\[youtube\=(.+?)]/s','<iframe width="350" height="197" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',$description); # youtube frame
//  $description = preg_replace( '/\[vimeo\=(.+?)]/s', '<a href=""></a>', $description );
  return $description;
}

function inputHTML( $name = null, $placeholder = null, $type = null, $value = null, $label = null ) {
	if( $name === null ) return;
	if( $value === null ) $value = "";
	if( $label === null ) $label = $name;
	if( $name == "email" ) $type = "email";
	if( $name == "pager" ) $type = "tel";
	if( !isset($type) && ( $type === null || $type === "" ) ) {
		$type = "text"; // default type
	} 
	return '<label for="' . $name . '" class="col-sm-2 form-control-label">' . $label . '</label>' . "\n<div class='col-sm-4'>\n" . '<input class="form-control" id="' . $name . '" type="' . $type . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '" />' . "\n</div>\n";
}

function textareaHTML( $name = null, $placeholder = null, $content = null, $label = null ) {
	if( $name === null ) return;
	if( $content === null ) $content = "";
	if( $label === null ) $label = $name;
	return '<label for="' . $name . '" class="col-sm-2 form-control-label">' . $label . '</label>' . "\n<div class='col-sm-4'>\n" . '<textarea class="form-control" id="' . $name . '" name="' . $name . '" placeholder="' . $placeholder . '" />' . $content . '</textarea>' . "\n</div>\n";
}

function selectHTML( $name = null, $value = null, $label = null, $placeholder = null ) {
	if( $name === null || $value === null ) return;
	if( $label === null ) $label = $name;
	if( $placeholder === null ) $placeholder = false;
	$erg = '<label id="' . $name . '" class="col-sm-2 form-control-label" for="' . $name . '">' . $label . '</label>' . "\n";
	$erg .= '<div class="col-sm-4">' . "\n";
	$erg .= '<select class="selectpicker" name="' . $name . '"';
	if( $placeholder !== false && $placeholder !== null && $placeholder !== "" ) { // Placeholder
		$erg .= ' title="' . $placeholder . '"';
	}
	$erg .= '>' . "\n";
	if( is_array( $value ) ) {
		foreach ($value as $val) {
			$erg .= '<option class="' . $val . '" $value="' . $val . '">' . $val . '</option>'. "\n";
		}
	} else {
		$erg .= '<option>' . $value . '</option>'. "\n";
	}
	$erg .= '</select>'. "\n";
	$erg .= '</div>' . "\n";
	return $erg;
}

function dateHTML( $name = null, $label = null, $value = null, $readonly = false ) {
	if( $name === null ) return;
	if( $value === null ) $value = '';
	if( $label === null ) $label = ucfirst( $name );
	if( $readonly === false ) $readonly = ''; else $readonly = 'readonly=""';
	$date = date( 'd.m.Y H:i' );
	$jsformat = 'dd.mm.yyyy hh:ii';
	return '<label id="' . $name . '" class="col-sm-2 form-control-label" for="' . $name . '">' . $label . '</label>
		<div class="col-sm-4">
			<div class="input-append date" data-date="' . $date . ':00Z">
				<input name="' . $name . '" value="' . $value . '" ' . $readonly . ' class="form-control form_datetime" type="text" size="16"></input>
				<span class="add-on"><i class="icon-remove"></i></span>
    		<span class="add-on"><i class="icon-calendar"></i></span>
			</div>
				
		  <script type="text/javascript">
		      $(".form_datetime").datetimepicker({
		      	format: \'' . $jsformat . '\',
		      	language: \'de\',
		      	startDate: "' . $date . '",
		      	initialDate: "' . $date . '",
		      	autoclose: true,
		      });
		  </script>            
    </div>';
}

function checkboxHTML( $name = null, $value = null, $checked = null, $title = null, $id = null, $event = null ) {
	if( $name === null || $value === null ) return;
	if( $title === null ) { $title = ''; } else { $title = 'title="' . $title . '"'; }
	if( $checked === null || $checked === false ) { $checked = ""; } else { $checked = 'checked'; }
	if( $id === null ) { $id = 'id="'.$name.'"'; } else { $id = 'id="' . $id . '"'; }
	if( $event === null ) { $event = ""; } else { $event = 'onclick="' . $event . '"'; }
	return '<label class="checkbox-inline" ' . $title . '><input type="checkbox" ' . $id . ' name="' . $name . '" value="' . $value . '" ' . $event . ' ' . $checked . '>'. $name . "</label>\n";
}






/*
    Paul's Simple Diff Algorithm v 0.1
    (C) Paul Butler 2007 <http://www.paulbutler.org/>
    May be used and distributed under the zlib/libpng license.
    
    This code is intended for learning purposes; it was written with short
    code taking priority over performance. It could be used in a practical
    application, but there are a few ways it could be optimized.
    
    Given two arrays, the function diff will return an array of the changes.
    I won't describe the format of the array, but it will be obvious
    if you use print_r() on the result of a diff on some test data.
    
    htmlDiff is a wrapper for the diff command, it takes two strings and
    returns the differences in HTML. The tags used are <ins> and <del>,
    which can easily be styled with CSS.  
*/
function diff($old, $new){
    $matrix = array();
    $maxlen = 0;
    foreach($old as $oindex => $ovalue){
        $nkeys = array_keys($new, $ovalue);
        foreach($nkeys as $nindex){
            $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
            if($matrix[$oindex][$nindex] > $maxlen){
                $maxlen = $matrix[$oindex][$nindex];
                $omax = $oindex + 1 - $maxlen;
                $nmax = $nindex + 1 - $maxlen;
            }
        }   
    }
    if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
    return array_merge(
        diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
        array_slice($new, $nmax, $maxlen),
        diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}
function htmlDiff($old, $new){
    $ret = '';
    $diff = diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
    foreach($diff as $k){
        if(is_array($k))
            $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
                (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
        else $ret .= $k . ' ';
    }
    return $ret;
}



?>
