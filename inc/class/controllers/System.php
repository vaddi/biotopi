<?php

/**
 * The Daemons for the BiotoPi Project
 *
 * Just call this class methods as GET or POST request:
 * http(s)://APP/?controller=systems&action=read
 * http(s)://APP/?controller=systems&action=read&id=1
 * http(s)://APP/?controller=systems&action=create&name=hum0&type=1&threshold=100&protocol=1
 * http(s)://APP/?controller=systems&action=read&id=2
 * http(s)://APP/?controller=systems&action=delete&id=2
 * http(s)://APP/?controller=systems&action=update&id=1&name=hum1
 */

class System extends Base {
	
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
	public function __construct( $_db, $id, $name, $value ) {
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
			$this->_params->value = ( isset( $value ) && $value !== null ) ? $value : null;
		} catch( Exception $e ) {
			if( ENV == 'prod' ) throw new Exception( $e->getMessage() );
				else throw new Exception( __CLASS__ . '::' . __FUNCTION__ . ' throw ' . $e->getMessage() );
		}
	}	

	/**
	 * create a Entry
	 */
	public function create() {
		return $this->_save();
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
      if( isset( $this->_params->id ) && null !== $this->_params->id  ) {
				// get entry by id
				$this->_db->query( "SELECT * FROM $this->_dbTable WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id );
			} else {
				// get all entries
				$this->_db->query( "SELECT * FROM $this->_dbTable" );
      }
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
      $this->_params->value = null === $this->_params->value ? $new['value'] : $this->_params->value;
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
				$this->_db->query( "UPDATE $this->_dbTable SET name = :name, value = :value, updated = :updated WHERE id = :id;" );
				$this->_db->bind( ':id', $this->_params->id );
				$this->_db->bind( ':updated', date( 'Y-m-d H:i:s' ) );
				$lastid = $this->_params->id;
			} else {
				// insert a new entry (define neccessary params)
				$this->_validateParam( 'name' );
				$this->_db->query( "INSERT INTO $this->_dbTable ( name, value, created ) VALUES ( :name, :value, :created )" );
				$this->_db->bind( ':created', date( 'Y-m-d H:i:s' ) );
			}
			// update and insert uses the same bindings
			$this->_db->bind( ':name', $this->_params->name );
      $this->_db->bind( ':value', $this->_params->value );
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
	// System data 
	//
	
	public function getAll() {
		$data['host'] = self::getHost();
		$data['mem'] = self::getMem();
		$data['load'] = self::getLoad();
		$data['fs'] = self::getFs();
		$data['net'] = self::getNet();
    $data['git'] = self::gitAll();
		return $data;
	}
	
	public function getHost() {
		$host['name'] = shell_exec('hostname | tr -d "\n"');
		$host['kernel'] = shell_exec('uname -r | tr -d "\n"');
    $host['cputemp'] = self::getCpuTemp();
    $host['updates'] = self::getUpdates();
    $host['files'] = self::totalFiles();
    $host['env'] = self::getEnv();
    $host['uptime'] = self::getUptime();
		return $host;
	}


	public function getCpuTemp() {
		$soc_temp_raw = shell_exec( 'cat /sys/class/thermal/thermal_zone0/temp' );
		return substr( $soc_temp_raw, 0, 2 ) . "." . substr( $soc_temp_raw, 2, -3 );
	}


	public function getMem() {
		$mem['total'] = shell_exec( 'cat /proc/meminfo | grep MemTotal | grep -o "[0-9]\+" | tr -d "\n"' );			// Total Memory in kB
		$mem['free'] = shell_exec( 'cat /proc/meminfo | grep MemFree | grep -o "[0-9]\+" | tr -d "\n"' );				// Free Memory in kB
		$mem['avail'] = shell_exec( 'cat /proc/meminfo | grep MemAvailable | grep -o "[0-9]\+" | tr -d "\n"' );	// Available Memory in kB
		$mem['percent'] = isset( $mem['total'] ) && isset( $mem['avail'] ) ? round( ( 100 / $mem['total'] ) * $mem['avail'] ) : "" . "%"; // Free memory in %
		return $mem;
	}

	public function getUptime() {
		$uptime= shell_exec('uptime | cut -f1 -d"," | tr -d "\n"');
		return $uptime;
	}

	public function getLoad() {
		$loadavgout = shell_exec('cat /proc/loadavg');
    if( $loadavgout === null ) return 'unavailable';
		$loadavgArr = explode(" ", $loadavgout);
		$schedulingArr = explode("/", $loadavgArr[3]);
		$avg['avg1'] = $loadavgArr[0];			// average systemload last 1m
		$avg['avg5'] = $loadavgArr[1];			// average systemload last 5m
		$avg['avg15'] = $loadavgArr[2];			// average systemload last 15m
		$avg['active'] = $schedulingArr[0];	// number of active tasks
		$avg['total'] = $schedulingArr[1];	// number of total tasks
		return $avg;
	}


	public function getFs() {
		$filesysout = shell_exec('df -h | grep root | tr -s " "');
    if( $filesysout === null ) return 'unavailable';
		$filesysArr = explode( " ", $filesysout );
		$fs['total'] = $filesysArr[1];		// Filesystem Total space
		$fs['used'] = $filesysArr[2];			// Filesystem Used space
		$fs['free'] = $filesysArr[3];			// Filesystem Free space
		$fs['percent'] = $filesysArr[4];	// Filesystem Used space %
		return $fs;
	}


	public function getNet() {
		$net['ip'] = shell_exec( '/sbin/ifconfig eth0 | grep \'inet addr:\' | cut -d: -f2 | awk \'{ print $1}\' | tr -d "\n"' );
		$netin = shell_exec( '/sbin/ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $2}\' | tr -d "\n"' );
		$net['in'] = ( strpos( $netin, 'GiB' ) !== false ) ? $netin = str_replace( ' GiB', '', $netin ) * 1024 . ' MiB' : $netin;
		$netout = shell_exec( '/sbin/ifconfig | grep -m 1 "RX bytes" | awk -F "[()]" \'{print $4}\' | tr -d "\n"' );
		$net['out'] = ( strpos( $netout, 'GiB' ) !== false ) ? $netout = str_replace( ' GiB', '', $netout ) * 1024 . ' MiB' : $netout;
		return $net;
	}


	public function getUpdates() {
		// has long runtime!!!
		//$updates = shell_exec( 'sudo /usr/lib/update-notifier/update-motd-updates-available | grep -Eo \'[0-9]{1,3}\' | tr \'\n\' \'/\' | cut -d \'/\' -f1,2' );
		// apt-get install update-notifier-common (for apt-check)
		//$updates = shell_exec( 'sudo /usr/lib/update-notifier/apt-check' );
//		$updates = shell_exec( "LANG=C apt-get upgrade -s | grep -P '^\d+ upgraded' | cut -d \" \" -f1 | tr -d \"\n\"" );
//		return isset( $updates ) ? $updates : "0";
		return 0;
	}

  /**
   * Git wrapper
   */
  public static function gitAll() {
    return array(
      'last'  =>  self::gitLast(),
      'remote'  =>  self::gitRemote(),
      'commits' =>  self::gitCommits(),
      'tag' =>  self::gitTag()
    );
  }

  /**
   * Git Last
   */
  public static function gitLast() {
    return Base::gitLast();
  }

  /**
   * Git Remote
   */
  public static function gitRemote() {
    return Base::gitRemote();
  }

  /**
   * Git Commits
   */
  public static function gitCommits() {
    return Base::gitCommits();
  }

  /**
   * Git Version
   */
  public static function gitTag() {
    return Base::gitTag();
  }

  /**
   * App size
   */
  public static function appSize() {
    return Base::appSize();
  }

  /**
   * Total Files
   */
  public static function totalFiles() {
    return Base::totalFiles();
  }

  /**
   * Get current enviroment
   */
  public static function getEnv() {
    return Base::getEnv();
  }

	/**
	 * System Reboot
	 */
  public function restart() {
		return shell_exec( 'sudo /sbin/shutdown -r now' );
  }

	/**
	 * System shutdown
	 */
  public function shutdown() {
    return shell_exec( 'sudo /sbin/shutdown -h now' );
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
