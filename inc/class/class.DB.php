<?php

// needs installed sqlite3 
// http://www.forum-raspberrypi.de/Thread-tutorial-datenbanken-mit-sqlite

class DB extends SQLite3 {
	
	protected $db;
	protected $data;
	
	function __construct() {
		require_once( '/var/www/inc/config.php' );
    $this->open( DBFILE );
    $this->db = new MyDB();
		if( ! $this->validateDb() ) {
			return 'Database validation in constructor failed!';
		}
  }
	
	public function validateDb() {
		if(!$this->db){
			return false;
		} else {
			return true;
		}
	}
	
	/* Get Data from Database
	 * @param $date = given search string
	 * 
	 */
	public function getData( $data ) {
		$erg = null;
		if( is_array( $data ) ) {
			// iterate over array
			foreach( $data as $key => $value ) {
				$tmp = $this->db->query( "SELECT $key FROM $data" );
				$erg[ $key ] = $tmp->fetchArray();
			}
		} else {
			$erg = $db->query( "SELECT * FROM $data" );
		}
		return $erg;
	}
	
	
	public function createDb() {
		$queries = array(	"CREATE TABLE IF NOT EXISTS roles(
												 id INTEGER PRIMARY KEY AUTO_INCREMENT,
												 name TEXT NOT NULL,
											 );",
											 "INSERT INTO roles (id, name) VALUES (1, 'Administrator');",
											 "INSERT INTO roles (id, name) VALUES (2, 'Redakteur');",
											 "INSERT INTO roles (id, name) VALUES (3, 'Author');",
											 "INSERT INTO roles (id, name) VALUES (4, 'Worker');",
											 "INSERT INTO roles (id, name) VALUES (5, 'Abonnent');",
											"CREATE TABLE IF NOT EXISTS users(
												 id INTEGER PRIMARY KEY AUTOINCREMENT, 
												 name TEXT NOT NULL,
												 passwd TEXT NOT NULL,
												 firstname TEXT NULL,
												 lastname TEXT NULL,
												 email TEXT NULL,
												 bdate DATE NULL,
												 comment TEXT NULL,
												 valid SMALLINT NOT NULL,
												 created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
												 FOREIGN KEY(role) REFERENCES roles(id) );",
											"INSERT INTO users ( name, passwd, email, role ) VALUES ( 'admin', '1nsecur3', 'vaddi@mvattersen.de', 1 );",
											"CREATE TABLE IF NOT EXISTS collectors(
												 id INTEGER PRIMARY KEY AUTO_INCREMENT,
												 name TEXT NOT NULL,
												 active SMALLINT DEFAULT 0,
											 );",
											 "INSERT INTO collectors (id, name, active) VALUES (1, 'dht11', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (2, 'bmp085', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (3, '28-000004d0e3cf', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (4, '28-000004bf4dbe', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (5, '28-000004cbaf9e', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (6, '28-000004bfd99e', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (7, '28-000004cd81ba', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (8, 'smssent', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (9, 'smsreceived', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (10, 'smsfailed', 1);",
											 "INSERT INTO collectors (id, name, active) VALUES (11, 'smssignal', 1);",
											 "CREATE TABLE IF NOT EXISTS data(
												 id INTEGER PRIMARY KEY AUTOINCREMENT, 
												 FOREIGN KEY(collector) REFERENCES collectors(id),
												 value FLOAT NULL,
												 timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP );",
											 "INSTERT INTO data ( collector, value ) VALUES ( 11, 54 );"
											 ;
											 
		);
		$erg = null;
		foreach( $queries as $key => $query ) {
			$tmp = $this->db->exec( $query );
			if(!$tmp){
		    $erg[] = $this->db->lastErrorMsg();
		 	} else {
		    $erg[] = "Record $key created successfully\n";
		 	}
		}
		return $erg;
	}
	
}





?>
