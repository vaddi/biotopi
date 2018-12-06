<?php
	
require_once( 'iDatabase.php' );

class Database_Error implements iDatabase {	
	
  public $error = null;
  
	// close DB connection
	public function close() {
		
	}
	
	// return the database object
	public function getDb() {
		
	}
	
	// prepare query
	public function prepare( $query ) {
		
	}
	
	// prepare query
	public function query( $query ) {
		
	}
	
	// bind parameter 
	public function bind( $param, $value, $type = null ) {
		
	}
	
	// execute statement
	public function execute() {
		
	}
	
	// get result as array
	public function resultset() {
		
	}
		
	// get result as object
	public function resultObj() {
		
	}
	
	// get a single result
	public function single() {
		
	}
	
	// get the amount of rows
	public function rowCount() {
		
	}
	
	// get the last insert id
	public function lastInsertId() {
		
	}
	
	// Transactions
	public function beginTransaction() {
		
	}
	public function endTransaction() {
		
	}
	public function cancelTransaction() {
		
	}
	
	// Debuging
	public function debugDumpParams() {
		
	}
	public function queryString() {
		
	}
	public function errorInfo() {
		
	}
	
}

?>