<?php


// path to config file
//define( 'CONFIGBASE', realpath('./') );
//require_once( CONFIGBASE . '../inc/config.php' );

class Config {
	
	private static $_table = 'config';
	private static $_dbfile = '/inc/class/db/Database.php';
	
	public function __construct() {
		// empty constructor ;)
	}
	
	/**
	 * Get a special value (search by key in config)
	 */
	public static function get( $key = null ) {
		if( $key === null ) return false;
		
		// create database
		require_once( CONFIGBASE . self::$_dbfile );
		$db = new Database( DB );
		
		// set table 
		$table = self::$_table;
		
		// search for name in entries
		$db->query( "SELECT * FROM $table WHERE key = :key" );
		$db->bind( ':key', $key );
		$db->execute();
		
		if( $db->rowCount( $table ) > 0 ) {
			$all = $db->resultset()[0];
			if( $all !== null && in_array( $key, $all ) ) {
				// return the value for the entry
				return $all[ 'value' ];
			}
		}
		return false;
	}
	
}

?>