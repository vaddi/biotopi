<?php

/**
 * The Daemons for the BiotoPi Project
 *
 * Just call this class methods as GET or POST request:
 * http(s)://APP/?controller=daemons&action=read
 * http(s)://APP/?controller=daemons&action=read&id=1end
 * http(s)://APP/?controller=daemons&action=create&name=hum0&type=1&threshold=100&protocol=1
 * http(s)://APP/?controller=daemons&action=read&id=2
 * http(s)://APP/?controller=daemons&action=delete&id=2
 * http(s)://APP/?controller=daemons&action=update&id=1&name=hum1
 */

class Daemons extends Base implements CRUD {
	
	/**
	 * Attributes
	 */
	private $_order = 'id'; // Default 'order by' clause element
	private $_params;				// Parameters container
	private $_db;						// Database object container
	private $_dbTable;			// Used DB-Table (empty will use the Classname as Tablename)
	private $_log = '/inc/log/BiotoPiDaemon.log';
	private $_path = '/inc/bin/';
	private $_bin = 'Daemon';
	
	/**
	 * constructor
	 * set all possible params, others will not pass
	 */
	public function __construct( $_db, $id, $name, $device, $type, $active, $start, $end, $created, $cmd ) {
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
			$this->_params->device = ( isset( $device ) && $device !== null ) ? $device : null;
			$this->_params->type = ( isset( $type ) && $type !== null ) ? $type : null;
      $this->_params->active = ( isset( $active ) && $active !== null ) ? $active : null;
			$this->_params->start = ( isset( $start ) && $start !== null ) ? $start : null;
			$this->_params->end = ( isset( $end ) && $end !== null ) ? $end : null;
			$this->_params->created = ( isset( $created ) && $created !== null ) ? $created : null;
			$this->_params->cmd = ( isset( $cmd ) && $cmd !== null ) ? $cmd : null;
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
			$this->_params->device = null === $this->_params->device ? $new['device'] : $this->_params->device;
			$this->_params->type = null === $this->_params->type ? $new['type'] : $this->_params->type;
      $this->_params->active = null === $this->_params->active ? $new['active'] : $this->_params->active;
			$this->_params->start = null === $this->_params->start ? $new['start'] : $this->_params->start;
			$this->_params->end = null === $this->_params->end ? $new['end'] : $this->_params->end;
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
				$this->_db->query( "UPDATE $this->_dbTable SET name = :name, device = :device, type = :type, active = :active, start = :start, end = :end, updated = :updated WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id ); 
				$this->_db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
				$lastid = $this->_params->id;
			} else {
				// insert a new entry (define neccessary params)
				$this->_validateParam( 'name' );
				$this->_validateParam( 'type' );
				$this->_validateParam( 'device' );
				$this->_db->query( "INSERT INTO $this->_dbTable ( name, device, type, active, start, end, created ) VALUES ( :name, :device, :type, :active, :start, :end, :created )" );
				$this->_db->bind( ':created', date( 'Y-m-d H:i:s' ) );
			}
			// update and insert shared bindings
			$this->_db->bind( ':name', $this->_params->name );
			$this->_db->bind( ':device', $this->_params->device );
			$this->_db->bind( ':type', $this->_params->type );
      $this->_db->bind( ':active', $this->_params->active );
			$this->_db->bind( ':start', $this->_params->start );
			$this->_db->bind( ':end', $this->_params->end );
			$this->_db->execute();
			if( null === $this->_params->id ) {
				// on create, we need to get the right last id
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
				// or throw a exception
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
	
	//
	// fontend helper methods
	//
	
	/**
	 * Specialized method to read Entry/ies from the frontend
	 **/
	public function active() {
		$result = false;
		try {
			$this->_db->query( "SELECT * FROM $this->_dbTable WHERE end >= NOW() AND start <= NOW() ORDER BY updated DESC;" );
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
	// Daemon control
	//

	public function daemon() {
		$this->_validateParam( 'cmd' );
		$path = realpath( './' ) . $this->_path;
		$bin = $this->_bin;
		$msg = null;
		$err = 0;
    
		switch( $this->_params->cmd ) {
			case 'clean':
				exec( 'cd ' . $path . ' && ./' . $bin . ' clean', $msg, $err );
				break;
			case 'status':
				exec( 'cd ' . $path . ' && ./' . $bin . ' status', $msg, $err );
				break;
			case 'start':
				exec( 'cd ' . $path . ' && ./' . $bin . ' start', $msg, $err );
				break;
			case 'stop':
				exec( 'cd ' . $path . ' && ./' . $bin . ' stop', $msg, $err );
				break;
			case 'restart':
				exec( 'cd ' . $path . ' && ./' . $bin . ' stop', $msg, $err );
				sleep( 1 );
				exec( 'cd ' . $path . ' && ./' . $bin . ' start', $msg, $err );
				break;
			case 'log':
				$log = realpath( './' ) . $this->_log;
				exec( 'tail ' . $log, $msg, $err );
				break;
			default:
				exec( 'cd ' . $path . ' && ./' . $bin , $msg, $err );
				$msg[0] = substr( $msg[0], 17, -1);
				break;
		}
		if( $err === 0 ) {
			return $msg;
		}
		return false;
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
