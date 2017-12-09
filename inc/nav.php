<?php

$homelink = 'index';
$exclude = array( 'install', 'old_install', 'template', 'bhd' ); // by mimetype .php 

// get all php files from mainfolder
$verzeichnis_raw = "./";
$suffix = ".php";
$verzeichnis_glob = glob( $verzeichnis_raw . '*' . $suffix );

// check for gammu service
if( ! isRunning( 'gammu-smsd' ) ) {
	array_push( $exclude, 'sms' );
}

natsort( $verzeichnis_glob );

// Sort Homelink to first position in array
$homelink = $verzeichnis_raw . $homelink . $suffix;
if( in_array( $homelink, $verzeichnis_glob ) ) {
	$verzeichnis_glob = array_diff($verzeichnis_glob, array( $homelink ) );
	array_unshift( $verzeichnis_glob, $homelink );
}

// remove excluded 
foreach ($exclude as $key => $value) {
	$link = $verzeichnis_raw . $value . $suffix;
	if( in_array( $link, $verzeichnis_glob ) ) {
		$verzeichnis_glob = array_diff($verzeichnis_glob, array( $link ) );
	}
}

?>
	<nav class="navigation">
	
	<p class="pull-right"><span class="clock"><!-- Time --></span></p>
	
		<ul id="headnav" class="nav nav-tabs">
		<?php	
		foreach($verzeichnis_glob as $key => $file) {
		
			$filename = str_replace( $verzeichnis_raw, '', $file );
			$fileraw = str_replace( $suffix, "", $filename );
			
			if ( $fileraw == $homelink ) {
				$filename = URL;
				$fileraw = "Home";
				
			}
			if( $key > 0 ) {
				echo '          <li role="navigation"><a href="' . $filename . '">' . ucfirst( $fileraw ) . '</a></li>' . "\n";
			}	else {
				echo '  <li role="navigation"><a href="' . $filename . '">' . ucfirst( $fileraw ) . '</a></li>' . "\n";
			}
		} 
		?>
		</ul>
		
		<script type="text/javascript">
			navigator( '#headnav' );
		</script>
	</nav>
