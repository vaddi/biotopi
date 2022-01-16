<?php

/**
 * The Devices for the BiotoPi Project
 *
 * Just call this class methods as GET or POST request:
 * http(s)://APP/?controller=devices&action=read
 * http(s)://APP/?controller=devices&action=read&id=1
 * http(s)://APP/?controller=devices&action=create&name=hum0&type=1&threshold=100&protocol=1
 * http(s)://APP/?controller=devices&action=read&id=2
 * http(s)://APP/?controller=devices&action=delete&id=2
 * http(s)://APP/?controller=devices&action=update&id=1&name=hum1
 */

class Devices implements CRUD {
	
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
	public function __construct( $_db, $id, $name, $js, $html, $status, $type, $threshold, $protocol, $data, $function, $params, $pins, $exec ) {
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
			$this->_params->js = ( isset( $js ) && $js !== null ) ? $js : null;
			$this->_params->html = ( isset( $html ) && $html !== null ) ? $html : null;
			$this->_params->status = ( isset( $status ) && $status !== null ) ? $status : null;
			$this->_params->type = ( isset( $type ) && $type !== null ) ? $type : null;
			$this->_params->threshold = ( isset( $threshold ) && $threshold !== null ) ? $threshold : null;
			$this->_params->protocol = ( isset( $protocol ) && $protocol !== null ) ? $protocol : null;
			$this->_params->data = ( isset( $data ) && $data !== null ) ? $data : null;
			$this->_params->function = ( isset( $function ) && $function !== null ) ? $function : null;
			$this->_params->params = ( isset( $params ) && $params !== null ) ? $params : null;
			$this->_params->pins = ( isset( $pins ) && $pins !== null ) ? $pins : null;
			$this->_params->exec = ( isset( $exec ) && $exec !== null ) ? $exec : null;
			// $this->_params->created = ( isset( $created ) && $created !== null ) ? $created : null;
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
			$this->_params->js = null === $this->_params->js ? $new['js'] : $this->_params->js;
			$this->_params->html = null === $this->_params->html ? $new['html'] : $this->_params->html;
			$this->_params->status = null === $this->_params->status ? $new['status'] : $this->_params->status;
			$this->_params->type = null === $this->_params->type ? $new['type'] : $this->_params->type;
			$this->_params->threshold = null === $this->_params->threshold ? $new['threshold'] : $this->_params->threshold;
			$this->_params->protocol = null === $this->_params->protocol ? $new['protocol'] : $this->_params->protocol;
			$this->_params->data = null === $this->_params->data ? $new['data'] : $this->_params->data;
			$this->_params->function = null === $this->_params->function ? $new['function'] : $this->_params->function;
			$this->_params->params = null === $this->_params->params ? $new['params'] : $this->_params->params;
			$this->_params->pins = null === $this->_params->pins ? $new['pins'] : $this->_params->pins;
			$this->_params->exec = null === $this->_params->exec ? $new['exec'] : $this->_params->exec;
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
      $element = $this->read();
      if( count( $element ) == 0 ) { // no element found
        $result = false;
      } else {
  			// remove DB request
  			$this->_db->query( "DELETE FROM $this->_dbTable WHERE id = :id" );
  			$this->_db->bind( ':id', $this->_params->id );
  			$this->_db->execute();
        // validate removing
        $element = $this->read();
        if( count( $element ) > 0 ) {
          $result = false;
        } else {
          $result = true;
        }
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
				$this->_db->query( "UPDATE $this->_dbTable SET name = :name, js = :js, html = :html, status = :status, type = :type, threshold = :threshold, protocol = :protocol, data = :data, function = :function, params = :params, pins = :pins, exec = :exec, updated = :updated WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id );
				$this->_db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
				$lastid = $this->_params->id;
			} else {
				// insert a new entry (define neccessary params)
				$this->_validateParam( 'name' );
				$this->_validateParam( 'type' );
				$this->_db->query( "INSERT INTO $this->_dbTable ( name, js, html, status, type, threshold, protocol, data, function, params, pins, exec, created ) VALUES ( :name, :js, :html, :status, :type, :threshold, :protocol, :data, :function, :params, :pins, :exec, :created )" );
				$this->_db->bind( ':created', date( 'Y-m-d H:i:s' ) );
			}
			// update and insert uses the same bindings
			$this->_db->bind( ':name', $this->_params->name );
			$this->_db->bind( ':js', $this->_params->js );
			$this->_db->bind( ':html', $this->_params->html );
			$this->_db->bind( ':status', $this->_params->status );
			$this->_db->bind( ':type', $this->_params->type );
			$this->_db->bind( ':threshold', $this->_params->threshold );
			$this->_db->bind( ':protocol', $this->_params->protocol );
			$this->_db->bind( ':function', $this->_params->function );
			$this->_db->bind( ':params', $this->_params->params );
			$this->_db->bind( ':pins', $this->_params->pins );
			$this->_db->bind( ':exec', $this->_params->exec );
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
			$this->_db->query( "SELECT * FROM $this->_dbTable WHERE end >= NOW() AND start <= NOW() ORDER BY updated DESC;" );
			
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
