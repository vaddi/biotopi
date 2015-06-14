<?php

//if( isset( $_GET['sid'] ) ) $client_sid = $_GET['sid'];

//if( empty( session_id() ) ) session_start();
//$this_sid = session_id();

//if( $this_sid != $client_sid ) {
//	// noID or wrongID, redirect to mainindex
//	echo "<meta http-equiv='refresh' content='0; url=./' />";
//} else {


if( isset( $_GET['command'] ) ) {
	
	// Check for login!
	
	switch ( $_GET['command'] )
	{
		case 'reboot':
			$retArr[0]['reboot'] = exec('sudo /sbin/shutdown -r now');
		break;
		case 'shutdown':
			$retArr[0]['shutdown'] = exec('sudo /sbin/shutdown -h now');
		break;
		default:
			$retArr[0][$_GET['command']] = "not implemented now!";
		break;
	}
	
} else {
	
	// No command, just return sysinfos
	
	$hostname = shell_exec('hostname ');
	$soc_temp = shell_exec( 'sudo vcgencmd measure_temp | grep -o "[0-9][0-9].[0-9]\+"' );
	$mem_total	= shell_exec( 'cat /proc/meminfo | grep MemTotal | grep -o "[0-9]\+"' );
	$mem_free	= shell_exec( 'cat /proc/meminfo | grep MemFree | grep -o "[0-9]\+"' );
	$mem_avail	= shell_exec( 'cat /proc/meminfo | grep MemAvailable | grep -o "[0-9]\+"' );
	$loadavgout = shell_exec('cat /proc/loadavg');
	$loadavgArr = explode(" ", $loadavgout);
	//$loadavg = substr( $output, 0, strpos( $output, " " ) ); 
	$schedulingArr = explode("/", $loadavgArr[3]);
	$filesysout = shell_exec('df -h | grep rootfs | tr -s " "');
	$netin = shell_exec( 'sudo ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $2}\'' );
	$netout = shell_exec( 'sudo ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $4}\'' );
	$filesysArr = explode( " ", $filesysout );
	$kernel	= shell_exec('uname -r');


	$retArr[0]['name'] = $hostname;			// Hostname
	$retArr[0]['temp'] = $soc_temp;			// SoC Temperatur
	$retArr[0]['avg1'] = $loadavgArr[0];				// average systemload last 1m
	$retArr[0]['avg5'] = $loadavgArr[1];				// average systemload last 5m
	$retArr[0]['avg15'] = $loadavgArr[2];				// average systemload last 15m
	$retArr[0]['scha'] = $schedulingArr[0];			// number of active tasks
	$retArr[0]['scht'] = $schedulingArr[1];			// number of total tasks
	$retArr[0]['memt'] = $mem_total;			// Total Memory in kB
	$retArr[0]['memf'] = $mem_free;			// Free Memory in kB
	$retArr[0]['mema'] = $mem_avail;			// Available Memory in kB
	$retArr[0]['filet'] = $filesysArr[1];				// Filesystem Total space
	$retArr[0]['fileu'] = $filesysArr[2];				// Filesystem Used space
	$retArr[0]['filef'] = $filesysArr[3];				// Filesystem Free space
	$retArr[0]['filep'] = $filesysArr[4];				// Filesystem Used space %
	$retArr[0]['netin'] = $netin;				// Network Device Received
	$retArr[0]['netout'] = $netout;			// Network Device Transmit
	
	// cleanup linebreaks
	foreach ( $retArr[0] as $key => $value ) {
		$retArr[0][ $key ] = clean( $value );
	}
}


// Return the filled Array back in json encoding
print_r( json_encode( $retArr ) );

//echo "<pre>";
//print_r( $retArr );
//echo "</pre>";

//} // END else

function clean( $string ) {
	return str_replace("\n","",$string);
}

?>
