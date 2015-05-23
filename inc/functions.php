<?php

// Simple Functions Collection

// PHP File includer
function incl($file) {
	if (file_exists($file)) {
		return include $file;
	} else {
		 return $file . " doesn't exists! \n";
		if( DEBUG > 0 )	exit;
	}
}

function filter_html($html) {
	$html = str_replace('<','&lt;',$html);
	$html = str_replace('>','&gt;',$html);
	return $html;
}


function genVidThumb( $video = null, $interval = null ) {
	
	$relPath = "../";
	
	if( file_exists( $relPath . $video ) ) {
		$ext = ".jpg";								// Output file extension
	 	$size = "320x220";						// Screenshot size
	 	$avconv = '/usr/bin/avconv';	// Path to avconv
	 	
		//where to save the image
		$videoname = basename( $video );
		$path = str_replace( $videoname, "", $video );
		$image = "media/img/" . substr( $videoname, 0, -4) . $ext;
		
		//what time to take screenshot from video, here it will take screenshot after 5 second from it being started.
		if ( $interval == null ) $interval = 10;

		//avconv command
		$cmd = "$avconv -i \"$relPath"."$video\" -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size \"$relPath"."$image\" 2>&1";

		//PHP built in function execute an external program    
		exec($cmd);
		
		return ( file_exists( $relPath . $image ) ? true : false );
	}
}

function convertDate( $dateString, $format ) {
	$myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
  return $myDateTime->format($format);
}

?>
