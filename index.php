<?php

//echo realpath('./');
//echo __DIR__;

// Load config file
$file = __DIR__ . '/inc/config.php';
if (file_exists( $file ) ) require_once $file;

// load install file if exists and show setup the helper
$file = __DIR__ . '/inc/assets/installation/install.php';
if (file_exists( $file ) ) return require_once $file;

// create the API Object
$file = __DIR__ . '/inc/class/API.php';
if (file_exists( $file ) ) {
	require_once $file;
	new API();
} else {
	$result['state'] = false;
	$result['errormsg'] = "Unable to create instance of Class API, abort";
	header("Content-Type: application/json charset=UTF-8");
	echo json_encode( $result );
}

?>
