<?php

/**
 * The Devices for the BiotoPi Project
 *
 * Just call this class methods as GET or POST request:
 * http(s)://APP/?controller=protocols&action=read
 * http(s)://APP/?controller=protocols&action=read&id=1
 * http(s)://APP/?controller=protocols&action=create&name=hum0&type=1&threshold=100&protocol=1
 * http(s)://APP/?controller=protocols&action=read&id=2
 * http(s)://APP/?controller=protocols&action=delete&id=2
 * http(s)://APP/?controller=protocols&action=update&id=1&name=hum1
 */

class Protocols implements CRUD {
	
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
	public function __construct( $_db, $id, $name, $type ) {
		try {
			// first param is allways the DB object
			$this->_db = $_db;
			// if $_dbTable is empty use Classname as Tablename
			if( empty( $this->_dbTable ) ) $this->_dbTable = strtolower( __CLASS__ );
			// get params if they are some into a new object
			$this->_params = new stdClass();
			// for each database field set a value
			$this->_params->id = ( isset( $id ) && $id !== null ) ? $id : null;
			$this->_params->name = ( isset( $name ) && $name !== null ) ? $name : null;
			$this->_params->type = ( isset( $type ) && $type !== null ) ? $type : null;
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}	


	//
	// CRUD methods
	//


	/**
	 * create a Entry
	 */
	public function create() {
		return $this->_save();
	}	


	/**
	 * read Entry/ies
	 */
	public function read() {
		$result = false;
		try {
			if( isset( $this->_params->id ) && null !== $this->_params->id  ) {
				// get entry by id
				$this->_db->query( "SELECT * FROM $this->_dbTable WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id );
			} else {
				// get all entries
				$this->_db->query( "SELECT * FROM $this->_dbTable ORDER BY $this->_order DESC;" );
      }
			// run Database stuff_db->queryString()	
			$this->_db->execute();
			// get our result
			if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
				$result = $this->_db->resultset();
			} else {
				// return null if no entry found
				$result = null;
			}
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return $result;
	}


	/**
	 * update Entry
	 */
	public function update() {
		$result = false;
		try {
			// this params needs to be setted, so validate them
			$this->_validateParam( 'id' );
			// read all old parameters from entry
			$new = (array) $this->read()[0];
			// get new newdata
			if( $this->_params->id === null ) $this->_params->id = $new['id']; // update the same id
			$this->_params->name = null === $this->_params->name ? $new['name'] : $this->_params->name;
			$this->_params->type = null === $this->_params->type ? $new['type'] : $this->_params->type;
			$result = $this->_save();
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return isset( $result[0] ) ? $result[0] : $result;
	}


	/**
	 * delete Entry
	 * @param		id				integer		table.id
	 */
	public function delete() {
		$result = false;
		try {
			// this params needs to be setted, so validate them
			$this->_validateParam( 'id' );
			// run Database stuff	
			$this->_db->query( "DELETE FROM $this->_dbTable WHERE id = :id" );
			$this->_db->bind( ':id', $this->_params->id );
			$this->_db->execute();
			// get our result
			if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
				$result = true;
			} else {
				$result = false;
			}
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return $result;
	}


	//
	// Other methods
	//
	
	
	/**
	 * private save method
	 */
	private function _save() {
		$result = false;
		try {
			if( null !== $this->_params->id ) {
				// update a entry
				$this->_validateParam( 'id' );
				$this->_db->query( "UPDATE $this->_dbTable SET name = :name, type = : type, updated = :updated WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id );
				$this->_db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
				$lastid = $this->_params->id;
			} else {
				// insert a new entry (define neccessary params)
				$this->_validateParam( 'name' );
				$this->_validateParam( 'type' );
				$this->_db->query( "INSERT INTO $this->_dbTable ( name, type, created ) VALUES ( :name, :type, :created )" );
				$this->_db->bind( ':created', date( 'Y-m-d H:i:s' ) );
			}
			// update and insert uses the same bindings
			$this->_db->bind( ':name', $this->_params->name );
			$this->_db->bind( ':type', $this->_params->type );
			$this->_db->execute();
			if( null === $this->_params->id ) {
				// on create, get last id
				$this->_db->query( "SELECT MAX(id) FROM $this->_dbTable" );
				$this->_db->execute();
				$lastid = (int) $this->_db->resultset()[0]['MAX(id)'];
			}
			
			if( $lastid !== 0 ) {
				// get the right entry back
				$this->_db->query( "SELECT * FROM $this->_dbTable WHERE id = :lastid;" );
				$this->_db->bind( ':lastid', $lastid );
				$this->_db->execute();
				$result = $this->_db->resultset();
				if( count( $result ) === 0 ) throw new Exception( "ID $lastid not exists in Table $this->_dbTable" );
			} else {
				$result = false;
			}
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
		return ( is_array ( $result ) && isset( $result[0] ) ) ? $result[0] : $result;
	}
	
	
	/**
	 * Specialized method to read Entry/ies from the frontend
	 */
	public function get() {
		$result = false;
		try {
			// get all entries
			$this->_db->query( "SELECT * FROM $this->_dbTable ORDER BY updated DESC;" );
			
			// run Database stuff	
			$this->_db->execute();
			// get our result
			if( $this->_db->rowCount( $this->_dbTable ) > 0 ) {
				$result = $this->_db->resultset();
			} else {
				// return null if no entry found
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
	// Helder functions
	//
	
	
	/**
	 * Helper method to validate a parameter (set them as required)
	 * throws exception if parameter has no data or doesnt exists in the request
	 */
	private function _validateParam( $paramName = null ) {
		if( $paramName === null ) return false;
		foreach ( $this->_params as $key => $value ) {
			if( ! isset( $this->_params->$paramName ) && $this->_params->$paramName === null ) {
				throw new Exception( "Missing Parameter: $paramName" );
			}
		}
	}
	
}

?>
