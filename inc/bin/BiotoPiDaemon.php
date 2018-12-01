#!/usr/bin/php
<?php

// Based on http://collaboradev.com/2011/03/31/php-daemons-tutorial/
// Refractoring to run on Debian/Ubuntu System: mvattersen

//Set the ticks
declare(ticks = 1)

	$LOG = realpath('./') . "/../log/Daemon.log";

	function displayUsage(){
		global $LOG;

		echo "\n";
		echo "Process for demonstrating a PHP daemon.\n";
		echo "\n";
		echo "Usage:\n";
		echo "Daemon.php [options]\n";
		echo "\n";
		echo "options:\n";
		echo "--help display this help message\n";
		echo "--log=<filename> The location of the log file (default '$LOG')\n";
		echo "\n";
	}

// configure command line arguments
if( $argc > 0 ) {
	foreach( $argv as $arg ) {
		$args = explode( '=', $arg );
		switch( $args[0] ) {
			case '--help':
				return displayUsage();
			case '--log':
				$LOG = $args[1];
				break;
		}
	}
}

$date = date( 'Y-m-d H:i:s' );


function updateRunning( $db = null, $id = null, $run = null ) {
	if( $db === null || $id === null || $run === null ) return false;
	if( $run > 1 ) $run = 1;
	if( $run < 0 ) $run = 0;
	$tableDaemons = "daemons";
	$db->query( "UPDATE $tableDaemons SET running = :running, updated = :updated WHERE id = :id" );
	$db->bind( ':running', $run );
	$db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
	$db->bind( ':id', $id );
	return (bool) $db->execute();
}

function updateData( $db = null, $id = null, $data = null ) {
	if( $db === null || $id === null ) return false;
	$tableDevices = "devices";
	$db->query( "UPDATE $tableDevices SET data = :data, updated = :updated WHERE id = :id" );
	$db->bind( ':data', $data );
	$db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
	$db->bind( ':id', $id );
	return (bool) $db->execute();
}

//fork the process to work in a daemonized environment
// file_put_contents( $LOG, $DATE . " Starting BiotoPi Daemon\n", FILE_APPEND );

// on osx you need to add pdntl. brew install pcntl
if( ! function_exists( 'pcntl_fork' ) ) die( 'PCNTL functions not available on this PHP installation' );
$pid = pcntl_fork();
if( $pid == -1 ) {
	file_put_contents( $LOG, $date . " [Error] could not daemonize process\n", FILE_APPEND );
	return 1; //error
} else if( $pid ) {
	return 0; //success
} else {
	//the main process
	
	// do stuff
	$table = "jobs_v";
	require_once( __DIR__ . '/../config.php' );
	require_once( __DIR__ . '/../class/db/Database.php' );
	$db = new Database( DB );
	
	while( true ) {
		// new date
		$date = date( 'Y-m-d H:i:s' );
		$result = null;

		// handle DB disconnected
		if( ! $db->connected ) {
			$msg = "Fatal - Cannot connect to Database";
			file_put_contents( $LOG, $date . " " . $msg . "\n", FILE_APPEND );
			die( $msg );
		}
		
		$db->query( "SELECT * FROM $table" );
		$db->execute();
		if( $db->rowCount( $table ) > 0 ) {

			// get all jobs and run theyre scripts
			$jobs = $db->resultset();
			foreach ($jobs as $id => $job) {
				
				$device = null;
				$daemon = null;
				$bin = null;
				$pins = null;
				$params = null;
				$name = null;
				$type = null;
				$typevalue = null;
				$running = null;
				$msg = null;
				$err = 0;
				
				// is device running and end time reached?
				$device = $job['device'];
				$daemon = $job['daemon'];
				$bin = $job['exec'];
				$pins = $job['pins'];
				$params = $job['params'];
				$name = $job['name'];
				$type = $job['dtype'];
				$typevalue = $job['dtypevalue'];
				// typevalue 60 (minutly) -> ( start - now ) > typevalue
				$running = (int) $job['running'];
				
				/*switch( $typevalue ) {
					case 1:
						// once
						break;
					case 2:
						// secondly
						break;
					case 3:
						// minutly
						break;
					case 4:
						// hourly
						break;
					case 5:
						// daily
						break;
					case 6:
						// weekly
						break;
					default:
						// default
						break;
				}*/
				
				if( $running == 1 ) {
					// check for over end date 
					if( strtotime( $job['end'] ) <= strtotime( date( 'Y-m-d H:i:s' ) ) ) {
						// has device a special turn of?
						
						// switch device off
						//exec( 'sudo ./' . $bin, $msg, $err );
						$msg[0] = "no exec";
						//if( $err === 0 ) {
							// set running to 0
							$drunning = updateRunning( $db, $daemon, 0 );
							if( $drunning ) {
								$result = "stop " . $name . " -> " . $msg[0];
							}
						//}
					}
				} else {
					// check for over start and before end 
					if( strtotime( $job['start'] ) <= strtotime( date( 'Y-m-d H:i:s' ) ) && strtotime( $job['end'] ) > strtotime( date( 'Y-m-d H:i:s' ) ) ) {
						// switch device on (run script)
						exec( 'cd ' . __DIR__ . ' && sudo ./' . $bin . " " . $pins . " " . $params, $msg, $err );
						if( $err === 0 ) {
							// update device data
							$setData = updateData( $db, $device, $msg[0] );
							
							// only if not once
							if( $typevalue != 0 ) {
								if( $setData ) {
									// update deamon running
									$drunning = updateRunning( $db, $daemon, 1 );
									if( $drunning ) {
										$result = "start " . $name . " -> " . $msg[0];
									}
								}
							} else {
								// run in intervall between times
								//$result = "once " . $name . " -> " . $msg[0];
							}
						} else {
							$result = "Error - Unable to exec " . __DIR__ . "/" . $bin;
						}
					}
				}
				$running = 0;
			} // end foreach
		} // end rows
		
		// print into log
		if( $result !== null ) {
			file_put_contents( $LOG, $date . " " . $result . " Running...\n", FILE_APPEND );
		}
		//file_put_contents( $LOG, $date . " Running...\n", FILE_APPEND );
		// wait a second, run only once a second
		sleep( 1 );
	} // end while
}

?>
