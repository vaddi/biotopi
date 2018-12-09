<?php

/**
 * The Daemons for the BiotoPi Project
 *
 * Just call this class methods as GET or POST request:
 * http(s)://APP/?controller=daemons&action=read
 * http(s)://APP/?controller=daemons&action=read&id=1
 * http(s)://APP/?controller=daemons&action=create&name=hum0&type=1&threshold=100&protocol=1
 * http(s)://APP/?controller=daemons&action=read&id=2
 * http(s)://APP/?controller=daemons&action=delete&id=2
 * http(s)://APP/?controller=daemons&action=update&id=1&name=hum1
 */

class Jobs_V {
	
	/**
	 * Attributes
	 */
	private $_order = 'id'; // Default 'order by' clause element
	private $_params;				// Parameters container
	private $_db;						// Database object container
	private $_dbTable;			// Used DB-Table (empty will use the Classname as Tablename)
	
	/**
	 * constructor
	 * set all possible params, others will not pass
	 */
	public function __construct( $_db, $daemon, $device, $name, $dtype, $start, $end, $updated, $exec, $pins, $params ) {
		try {
			// first param is allways the DB object
			$this->_db = $_db;
			// if $_dbTable is empty use Classname as Tablename
			if( empty( $this->_dbTable ) ) $this->_dbTable = strtolower( __CLASS__ );
			// get params if they are some into a new object
			$this->_params = new stdClass();
			// for each database field set a value
			$this->_params->daemon = ( isset( $daemon ) && $daemon !== null ) ? $daemon : null;
			$this->_params->device = ( isset( $device ) && $device !== null ) ? $device : null;
      $this->_params->name = ( isset( $name ) && $name !== null ) ? $name : null;
			$this->_params->dtype = ( isset( $dtype ) && $dtype !== null ) ? $dtype : null;
			$this->_params->start = ( isset( $start ) && $start !== null ) ? $start : null;
			$this->_params->end = ( isset( $end ) && $end !== null ) ? $end : null;
			$this->_params->updated = ( isset( $updated ) && $updated !== null ) ? $updated : null;
			$this->_params->exec = ( isset( $exec ) && $exec !== null ) ? $exec : null;
      $this->_params->pins = ( isset( $pins ) && $pins !== null ) ? $pins : null;
      $this->_params->params = ( isset( $params ) && $params !== null ) ? $params : null;
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}	

	
	
	//
	// fontend helper methods
	//
	
	/**
	 * Specialized method to read Entry/ies from the frontend
	 **/
	public function read() {
		$result = false;
		try {
			$this->_db->query( "SELECT * FROM $this->_dbTable" );
			$this->_db->execute();
			if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
				$result = $this->_db->resultset();
			} else {
				$result = null;
			}
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return $result;
	}
	
	/**
   * Specialized method to read Entry/ies from the frontend
   **/
  public function current() {
    $result = false;
    try {
      $this->_db->query( "SELECT * FROM $this->_dbTable WHERE end >= NOW() AND start <= NOW()" );
      $this->_db->execute();
      if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
        $result = $this->_db->resultset();
      } else {
        $result = null;
      }
    } catch( Exception $e ) {
      if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
        else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
    }
    return $result;
  }
	
	/**
	 * Specialized method to validate a entry by given id
	 */
	public function validateEntry() {
		$result = false;
		try {
			$this->_validateParam( 'id' );
			$this->_db->query( "SELECT * FROM $this->_dbTable WHERE id = :id;" );
			$this->_db->bind( ':id', $this->_params->id );
			$this->_db->execute();
			if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
				$result = true;
			}
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return $result;
	}


	
	//
	// parameter validation
	//
	
	
	/**
	 * Helper method to validate a parameter (set them as required)
	 * throws exception if parameter has no data or doesnt exists in the request
	 */
	private function _validateParam( $paramName = null ) {
		if( $paramName === null ) return false;
		foreach ( $this->_params as $key => $value ) {
			if( ! isset( $this->_params->$paramName ) && $this->_params->$paramName === null ) {
				throw new Exception( "Missing required Parameter: $paramName" );
			}
		}
	}
	
}

?>
