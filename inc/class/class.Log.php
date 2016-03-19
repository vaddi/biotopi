<?php

class Log {
	
	protected $lines = 3; // default lines output
	protected $logfile = "/var/log/apache2/biotopi.log";
	
	public function read( $lines = null, $file = null ) {
		if( $file === null ) $file = $this->logfile;
		if( $lines === null ) $lines = $this->lines;
		$erg = exec( 'sudo /usr/bin/tail -n ' . $lines . ' ' . $file, $msg, $err );
	//  print_r( $msg );
		return isset( $msg ) ? $msg : $erg;
	}

	public function write( $data = null, $file = null ) {
		if( $data === null ) return;
		if( $file === null ) $file = $this->logfile;
//		$erg = false;
		$erg = exec( 'sudo /bin/echo ' . $data . ' >> ' . $file . ' 2>&1', $msg, $err );
//		print_r( $msg );  
	  return $erg;
	}
	
}
