<?php

class File {

	protected static $file = null;
	public static $format = "json"; // json / xml / raw
	
//	public function __construct(  ) {
//		if( $format === null ) return;
//		if( file_exsists( $file ) ) {
//			$this->file = $file;
//		} else {
//			exit;
//		}
//	}	
	
//	// for write 
//	protected function encodeData( $data = null ) {
//		if( $data === null ) return false;
//		switch( $this->format ) {
//			case "json" :
//				$erg = json_encode( $data );
//				break;
//			case "xml" :
//				$erg = $data; // TODO 
//				break;
//			default :
//				$erg = $data;
//				break;
//		}
//		return $erg;
//	}
//	
//	// for read
//	protected function decodeData( $data ) {
//		if( $data === null ) return false;
//		switch( $this->format ) {
//			case "json" :
//				$erg = json_decode( $data );
//				break;
//			case "xml" :
//				$erg = $data; // TODO
//				break;
//			default :
//				$erg = $data;
//				break;
//		}
//		return $erg;
//	}
	
	public function read( $file = null ) {
		if( $file === null ) return;
		
		$data = "";
		$func_file = fopen($file, "r"); 
		// die('Error ' . get_class( __CLASS__ ) . "\nUnable to read from file " . $file . '!');
		if( !$func_file ) {
			$data = self::write( $file, 0 ); // write will create the file
			$func_file = fopen($file, "r"); 
			while( !feof($func_file) ) {
				$data .= fgets($func_file);
			}
		} else {
			while( !feof($func_file) ) {
				$data .= fgets($func_file);
			}
		}
		
		return isset( $data ) ? $data : false;
	}
	
	public function write( $file = null, $data = null ) {
		if( $file === null ) return;
		if( $data === null ) return;
		
		// TODO, validate data
		$func_file = fopen($file, "w") or die("Error \nUnable write to file " . $file . '!');
		$status = fwrite( $func_file, $data );
		fclose($func_file);
		return $status != false ? true : false;
	}
	
}

