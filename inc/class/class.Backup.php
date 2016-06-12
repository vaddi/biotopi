<?php

class Backup {
	
	protected $_source = null;
	protected $_destination = null;
	
	public function __construct( $source = null, $destination = null ) {
		if( $source === null || $destination === null ) return;
		$this->_source = $source;
		$this->_destination = $destination;
	}
	
	public function show( $id = null ) {
		if( $id === null ) {
			// return all
			
		} else {
			// return by id
			
		}
	}
	
	public function create() {
		$id = null;
		$zip = new ZipArchive();

		if ( $zip->open( $fileurl, ZIPARCHIVE::CREATE ) !== TRUE ) {
				exit("cannot open <$fileurl>\n");
		}

		// initialize an iterator
		// pass it the directory to be processed
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $page_folder ) );
		// iterate over the directory
		// add each file found to the archive
		foreach ( $iterator as $key => $value ) {
			$zip->addFile( realpath( $key ), $key) or die ( "ERROR: Could not add file: $key" );
		}

		//echo " numfiles: " . $zip->numFiles . "<br />";
		//echo " status:" . $zip->status . "<br /><br />";

		// close and save archive
		$zip->close();
		return $id;
	}
	
	public function delete( $id = null ) {
		if( $id === null ) return;
		
		
	}
	
	public function update( $id = null ) {
		if( $id === null ) return;
		
		
	}
}

