<?php

// Get Data from system

// security level 2 file 
$secLvl = 1;

//require_once( "../config.php" );
require_once( '../functions.php' );
require_once( '../secure.php' );

if( isset( $_GET['command'] ) ) {

	// TODO, Check for login!

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
	$time_start = microtime(true);
 

	$hostname = shell_exec('hostname');
#	$soc_temp = shell_exec( 'sudo /usr/bin/vcgencmd measure_temp | grep -o "[0-9][0-9].[0-9]\+" | tr "\" " "' );
	$soc_temp_raw = shell_exec( 'cat /sys/class/thermal/thermal_zone0/temp' );
	$soc_temp = substr( $soc_temp_raw, 0, 2 ) . "." . substr( $soc_temp_raw, 2, -3 ) . "°C";
	$mem_total	= shell_exec( 'cat /proc/meminfo | grep MemTotal | grep -o "[0-9]\+"' );
	$mem_free	= shell_exec( 'cat /proc/meminfo | grep MemFree | grep -o "[0-9]\+"' );
	$mem_avail	= shell_exec( 'cat /proc/meminfo | grep MemAvailable | grep -o "[0-9]\+"' );
	$mem_freep = round( ( 100 / $mem_total ) * $mem_avail) . "%";
	$loadavgout = shell_exec('cat /proc/loadavg');
	$loadavgArr = explode(" ", $loadavgout);
	//$loadavg = substr( $output, 0, strpos( $output, " " ) ); 
	$schedulingArr = explode("/", $loadavgArr[3]);
	$filesysout = shell_exec('df -h | grep root | tr -s " "');
	$netip = shell_exec( '/sbin/ifconfig eth0 | grep \'inet addr:\' | cut -d: -f2 | awk \'{ print $1}\'' );
	$netin  = shell_exec( '/sbin/ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $2}\' | tr \' \n\' \' \'' );
	$netout = shell_exec( '/sbin/ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $4}\' | tr \'\n\' \' \'' );
	// Recalculate GiB Network Data to MiB if neccessary
	$netout = ( strpos( $netout, 'GiB' ) !== false ) ? $netout = str_replace( ' GiB', '', $netout ) * 1024 . ' MiB' : $netout;
	$netin = ( strpos( $netin, 'GiB' ) !== false ) ? $netin = str_replace( ' GiB', '', $netin ) * 1024 . ' MiB' : $netin;
	$updates = shell_exec( 'sudo /usr/lib/update-notifier/update-motd-updates-available | grep -Eo \'[0-9]{1,3}\' | tr \'\n\' \'/\' | cut -d \'/\' -f1,2' );
//		$updates = shell_exec( 'sudo /usr/lib/update-notifier/apt-check' );
	$updates = isset( $updates ) ? $updates : "0/0";
	$filesysArr = explode( " ", $filesysout );
	$kernel	= shell_exec('uname -r');
	$radiation = radiation( true );
#	$radiation = array();	
	$min = 0;
	$max = 50;
	$sparkle = rand($min,$max);
	
	// cron jobs
//	$ccp = cronState( '/var/log/cron/caro.log' ) ? 'Ok' : 'Failed';
	$bahnjob = cronState( '/var/log/cron/bahn.log', null, 12 ) ? 'Ok' : 'Failed';
	$cds18b20 = cronState( '/var/log/cron/biotopi.log', 'temp_', 3 ) ? 'Ok' : 'Failed';
	$cbmp085 = cronState( '/var/log/cron/biotopi.log', 'pa' ) ? 'Ok' : 'Failed';
	
	// check runtime
	$runtime = round( ( microtime(true) - $time_start ), 3 ) . " Sek.";
	
	
	$retArr[0]['name'] = $hostname;					// Hostname
	$retArr[0]['temp'] = $soc_temp;					// SoC Temperatur
	$retArr[0]['avg1'] = $loadavgArr[0];			// average systemload last 1m
	$retArr[0]['avg5'] = $loadavgArr[1];			// average systemload last 5m
	$retArr[0]['avg15'] = $loadavgArr[2];			// average systemload last 15m
	$retArr[0]['scha'] = $schedulingArr[0];			// number of active tasks
	$retArr[0]['scht'] = $schedulingArr[1];			// number of total tasks
	$retArr[0]['memt'] = $mem_total;				// Total Memory in kB
	$retArr[0]['memf'] = $mem_free;					// Free Memory in kB
	$retArr[0]['mema'] = $mem_avail;				// Available Memory in kB
	$retArr[0]['memp'] = $mem_freep;				// Free memory in %
	$retArr[0]['filet'] = $filesysArr[1];			// Filesystem Total space
	$retArr[0]['fileu'] = $filesysArr[2];			// Filesystem Used space
	$retArr[0]['filef'] = $filesysArr[3];			// Filesystem Free space
	$retArr[0]['filep'] = $filesysArr[4];			// Filesystem Used space %
	$retArr[0]['netip'] = $netip;					// Network ip address
	$retArr[0]['netin'] = $netin;					// Network Device Received
	$retArr[0]['netout'] = $netout;					// Network Device Transmit
	$retArr[0]['updates'] = $updates;				// Amount of Apt-Updates 
	$retArr[0]['bahnjob']	= $bahnjob;				// Cronjob for Bahn check
	$retArr[0]['cds18b20'] = $cds18b20;				// Cronjob for ds18b20 temperature
	$retArr[0]['cbmp085'] = $cbmp085;				// Cronjob for BMP085 Barometric Pressure
	$retArr[0]['radcps'] = $radiation[0]['CPS'];	// Radiation Counts per Second
	$retArr[0]['radcpm'] = $radiation[0]['CPM'];	// Radiation Counts per Minute
	$retArr[0]['uSv'] = $radiation[0]['uSv'];		// Radiation Level uSv/h
	$retArr[0]['radmode'] = $radiation[0]['mode'];	// Radiation detect mode
	
	$retArr[0]['sparkle'] = $sparkle;				// Random Value
	$retArr[0]['runtime'] = $runtime;				// Check runtime
	
	// cleanup linebreaks
	foreach ( $retArr[0] as $key => $value ) {
		$retArr[0][ $key ] = clean( $value );
	}
}

// set json header and print output
//require_once( 'json_header.php' );
print_r( json_encode( $retArr ) );


// Helper functions

function clean( $string ) {
	return str_replace("\n","",$string);
}

?>
