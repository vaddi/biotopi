<?php

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/iDatabase.php' );
require_once( __DIR__ . '/Database_Error.php' );
require_once( __DIR__ . '/Database_MySQL.php' );
require_once( __DIR__ . '/Database_SQLite.php' );

class Database implements iDatabase {
	
	public $connected = false;
	public $query = null;
	public $type = null;
	#
	private $_db = null;	// Database Object
	private $_stmt;				// Database Statements
	
	// constructor
	public function __construct( $class = null ) {
		if( $class === null ) return;
		$className = __CLASS__ . "_" . $class;
		if( class_exists( $className ) ) {
			try {
				// TODO Build connectionstring for each DB type
				if( self::missing_php_libs() ) exit;
				// see here for more https://stackoverflow.com/a/18236124/5208166
				if( $class === "MySQL" ) {
					$this->_db = new $className( MYSQL_USER, MYSQL_PASS, MYSQL_NAME, MYSQL_HOST, MYSQL_PORT );
				} else if( $class === 'SQLite' ) {
					$this->_db = new $className( SQLITE_TYPE, SQLITE_FILE );
				}
	
				if( ! is_object( $this->_db ) ) {
					// No Object, use Error Class
					$eclass = __CLASS__ . '_Error';
					$this->_db = new $eclass();
					$this->_db->error = $error = 'Error:  ' . __FILE__ . ' in line ' . __LINE__ . '. Instance of class ' . $className . ' is no Object.' . "\n";
					//error_log( TIMESTAMP . $error, 3, LOG );
				}
			} catch (Exception $e) {
				echo 'Error in DB constructor: ',  $e->getMessage(), "\n";
			}
		} else {
			// Class not found, use Error Class
			$eclass = __CLASS__ . '_Error';
			$this->_db = new $eclass();
			$this->_db->error = $error = 'Error:  ' . __FILE__ . ' in line ' . __LINE__ . '. Cannot create instance of class "' . $className . "\", class not exsist.\n";
			//error_log( TIMESTAMP . $error, 3, LOG );
		}
		$this->type = $class;
		if( $this->_db->error !== null ) {
			echo $this->_db->error;
			return;
		}
		if( $this->connection() ) {
			$this->connected = true;
		}
	}
	
	// check for missing php libs
	private function missing_php_libs() {
		$libs = array( 
			'pdo_mysql' => 'php-mysql',
			'pdo_sqlite' => 'php-sqlite3 or php5-sqlite' 
		);
		foreach( $libs as $key => $lib ) {
		  if( ! extension_loaded( $key ) ) {
		    echo "Failed to load " . $key . "</br>";
		    echo "Please install missing (Example on an Ubuntu 16.04): <br />";
		    echo "sudo apt install " . $lib . "<br />";
		    return true;
		  }
		}
		return false;
	}

	// close DB connection
	public function close() {
		if( $this->_db ) $this->_db = null;
		$this->connected = false;
	}
	
	// verifying database connection
	public function connection() {
		if( $this->_db === null ) {
			if ( SQLITE_TYPE === 'MEMORY' ) {
				echo "SQLite type: <strong>" . SQLITE_TYPE . "</strong> is not full implemented. <br />See <a href='https://www.sqlite.org/inmemorydb.html'>https://www.sqlite.org/inmemorydb.html</a> for example. <br /><br />";
			}
			echo "No Database connection available! <br />";
			return false;
		} else {
			return $this->_db->connection();
		}
	}
	
	// return the database object
	public function getDb() {
		return $this->_db;
	}
	
	// prepare query
	public function query( $query ) {
		try {
			switch( $this->type ) {
				// depends on Database type (query or prepend)
				case 'MySQL':
					$this->_stmt = $this->_db->prepare( $query );
					break;
				case 'SQLite':
          // Replace Special DB Functions for SQLite
          // https://www.sqlite.org/lang_datefunc.html
          //$query = str_replace( 'NOW()', "DATETIME('" . date( 'Y-m-d H:i:s' ) . "')", $query );
          $query = str_replace( 'NOW()', "STRFTIME( '%Y-%m-%d %H:%M:%S', 'now' )", $query );
					$this->_stmt = $this->_db->query( $query );
					break; 
			}
			if( is_bool( $this->_stmt ) && $this->_stmt === false ) 
				throw new Exception( "Error in Query: " . $query );
			// TODO remove this, only used in function lastInsertId
			$this->query = (string) $query;
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}
	
	// bind parameter 
	public function bind( $param, $value, $type = null ) {
		if( $this->_stmt === null || ( is_bool( $this->_stmt ) && $this->_stmt === false ) ) throw new Exception( "Error in statement, unable to call bind. Does Database and Table exists?" ); 
    try {
    	if( is_null( $type ) ) {
		    switch( true ) {
		      case is_int( $value ):
		        $type = PDO::PARAM_INT;
		        break;
		      case is_bool( $value ):
		        $type = PDO::PARAM_BOOL;
		        break;
		      case is_null( $value ):
		        $type = PDO::PARAM_NULL;
		        break;
		      default:
		        $type = PDO::PARAM_STR;
		    }
		  }
		  $this->_stmt->bindValue( $param, $value, $type );
			//$this->_stmt->bindParam( $param, $value, $type );
			//$this->_db->bind( $param, $value, $type );
    } catch( PDOException $e ) {
    	throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
    }
	}
	
	// execute statement
	public function execute() {
		try {
			if( $this->_stmt === null || ( is_bool( $this->_stmt ) && $this->_stmt === false ) ) throw new Exception( "Error in statement, unable to call execute. Does Database and Table exists?" ); 
			$result = $this->_stmt->execute();
			return $result;
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}
	
	// get result as array
	public function resultset() {
		$result = null;
		try {
			$result = $this->_stmt->fetchAll( PDO::FETCH_ASSOC );
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
		
	// get result as object
	public function resultObj() {
    $result = null;
		try {
			$result = $this->_stmt->fetchAll( PDO::FETCH_OBJ );
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// get a single result
	public function single() {
		$result = null;
		try {
			$result = $this->_stmt->fetch( PDO::FETCH_ASSOC );
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// get the amount of rows
	public function rowCount( $table = null ) {
		$result = null;
		try {
			if( $table === null ) throw new PDOException( 'No Table given, cannot read from unknown Table.' );
			$query = "SELECT COUNT(*) as count FROM " . $table;
			$this->_db->query( $query );
			$this->_db->execute();
			$result = $this->_db->resultset()[0]['count'];
			//$result = $this->_db->rowCount();
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// get the last insert id
	public function lastInsertId( $table = null ) {
		$result = null;
		try {
			if( $table === null ) throw new PDOException( 'No Table given, cannot read from unknown Table.' );
			if( $this->_stmt !== null ) {
				// TODO use queryString instead of $this->query
				if( strpos( 'INSERT INTO', $this->query ) === false ) {
					$result = (int) $this->_db->lastInsertId( $table );
				}
			}
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// Transactions
	public function beginTransaction() {
		return $this->_db->beginTransaction();
	}
	
	public function endTransaction() {
		return $this->_db->commit();
	}
	
	public function cancelTransaction() {
		return $this->_db->rollBack();
	}
	
	// Debuging
	public function debugDumpParams() {
		$result = null;
		try {
			if( $this->_stmt !== null ) {
				$result = $this->_stmt->debugDumpParams();
			}
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	public function queryString() {
    $result = null;
		try {
			if( $this->_stmt !== null ) {
				$result = $this->_db->queryString();
			}
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	public function errorInfo() {
    $result = null;
		try {
			if( $this->_stmt !== null ) {
				$result = $this->_stmt->errorInfo();
			}
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
}

?>
