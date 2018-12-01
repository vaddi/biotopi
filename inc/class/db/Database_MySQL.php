<?php
	
require_once( __DIR__ . '/iDatabase.php' );

class Database_MySQL extends PDO implements iDatabase {	
	
	public $connected = false;

	protected $_dbtype = 'mysql';
	protected $_dbhost = null;
	protected $_dbport = 3306;

  private $_db = null;	// Database Object
	private $_stmt;				// Database Statements
	
	// constructor
	/**
	 * Set user and passwort is neccessary, other params are optional
	 * @param	$dbuser (string)					db username
	 * @param	$dbpass (string)					db userpassword
	 * @param	$dbname (string)		(opt)	db to use
	 * @param	$dbhost (string)		(opt)	db host
	 * @param	$dbport (string)		(opt)	db port (null = default 3306)
	 */
	public function __construct( $dbuser = '', $dbpswd = '', $dbname = null, $dbhost = null, $dbport = null ) {
		if( $dbhost === null ) $this->_dbhost = 'localhost'; else $this->_dbhost = $dbhost;
		if( $dbport === null ) $this->_dbport = ''; else $this->_dbport = (int) $dbport; 
		if( $dbname === null ) $dbname = ''; else $dbname = "dbname=" . $dbname . "";
		try {
			# Create PDO object
			$dbportString = $dbport === null ? '' : ";port=" . $this_dbport;
		  $this->_db = new PDO( $this->_dbtype . ":host=" . $this->_dbhost . $dbportString . ";" . $dbname , $dbuser, $dbpswd );
		  // Set PDO Otpions
		  $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
//			$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//		  $this->_db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ );
			$this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		  $this->_db->setAttribute( PDO::MYSQL_ATTR_INIT_COMMAND,'SET NAMES UTF8' );
			parent::__construct( $this->_dbtype . ":host=" . $this->_dbhost . $dbportString . ";" . $dbname , $dbuser, $dbpswd );
		  if( $this->connection() ) {
		  	$this->connected = true;
		  }
		} catch ( PDOException $e ) {
			// on fail, return exception message
			$this->connected = false;
//			print_r( $e->getMessage() );						// print out exceptions
		  throw new Exception( $e->getMessage() );	// log exceptions
		}
	}
	
	// close DB connection
	public function close() {
		if( $this->_db ) $this->_db = null;
		$this->connected = false;
	}
	
	// verifying database connection
	public function connection() {
		$result = 0;
		// verify connection
		$result = strtoupper( $this->_dbtype ) . "::" . $this->_db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
		return $result;
	}
	
	// return the database object
	public function getDb() {
		return $this->_db;
	}
	
	// prepare query
	public function query( $query ) {
		try {
			$this->_stmt = $this->_db->prepare( $query );
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}
	
	// bind parameter 
	public function bind( $param, $value, $type = null ) {
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
    } catch( PDOException $e ) {
    	throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
    }
	}
	
	// execute statement
	public function execute() {
		try {
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
	public function rowCount() {
		$result = 0;
		try {
			if( $this->_stmt !== null ) {
				$result = $this->_stmt->rowCount();
			}
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// get the last insert id
	public function lastInsertId( $table = null ) {
		$result = 0;
		try {
			$result = parent::lastInsertId( $table );
		} catch( PDOException $e ) {
			throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
    return $result;
	}
	
	// Transactions
	public function beginTransaction() {
		return parent::beginTransaction();
	}
	
	public function endTransaction() {
		return parent::commit();
	}
	
	public function cancelTransaction() {
		return parent::rollBack();
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
				$result = $this->_stmt->queryString;
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
