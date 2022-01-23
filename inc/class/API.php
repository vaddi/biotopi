<?php

class API {

	/**
	 * Attributes
	 */
	private $_db;
	private $_dbfile = '/db/Database.php';
	private $_controllerFolder = '/controllers';
	private $_interfaceFolder = '/interfaces';
	private $_extensionFolder = '/extensions';


	/**
	 * constructor
	 */
	public function __construct() {
		// create Database instance
		if( is_file( __DIR__ . $this->_dbfile ) ) {
			require_once( __DIR__ . $this->_dbfile );
			$this->_db = new Database( DB );
			// call main function
			return $this->handleHttpRequest();
		}
	}


	/**
	 * destructor
	 */
	public function __destruct() {
		// destruct database instance
		if( $this->_db ) $this->_db->close();
	}


	/**
	 * handle the HTTP requests 
	 */
	public function handleHttpRequest() {
		
		// starttime
		$time_start = microtime(true);
		
		// create new Session if the client doesn't have one
		if( session_id() == null ) session_start();

		$result = array();

		try {

			// get version on empty request 
			if( ( $_GET == null && $_POST == null ) /* || ( $_POST == null && $_POST == null ) */ ) {

				// no parameters given, just return the version number
        $initial = 'BiotoPi API ' . 'v1 '; // Helper::getVersion();
				$result['data'] = $initial;
				$result['state'] = true;

				// TODO, $_REQUEST is never empty, use $_POST & $_GET instead!!
			} else if( isset( $_GET ) || isset( $_POST ) /* && $_REQUEST != "" */ ) {

				if( ENV == 'prod' && isset( $_REQUEST['tk'] ) && $_REQUEST['tk'] !== null ) {

					// only allowed requests in productive enviroments with right token
					$tk = base64_decode( urldecode( $_REQUEST['tk'] ) );
					$sitetk = Config::get( 'token' );
					$unpack = unpack( 'H*', $sitetk );
					if( $tk != strtotime( date( 'd.m.Y H:i:00' ) ) . array_shift( $unpack ) ) throw new Exception( "Wrong token! " );
					//						$tk = base64_decode( urldecode( $_REQUEST['tk'] ) );
					//						if( $tk != Config::get( 'token' ) ) throw new Exception( "Wrong token!" ); // , $_SESSION['site'] 

				} else if( ENV == 'prod' && ( ! isset( $_SESSION['eingeloggt'] ) || $_SESSION['eingeloggt'] != 1 ) ) {

          // we need a Users Backend and Frontend
          // Login, Register (contains send E-Mail with tokenized Link)
          // Overview for Admins (we need also Groups for this: Admin and User should be enough) 

					// unauthorized requests redirect or notify?
          // if( Config::get( 'apiredirect' ) ) {
          //   // Redirect to loginpage
            // header ( "Location: ./mgmt" );
          // } else {
						// Returns a Message
            throw new Exception( 'Please login to use this service!' );
          // }

				}

				// get our controller and action names 
				$controllerName = isset( $_REQUEST['controller'] ) ? $_REQUEST['controller'] : null;
				$actionName = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;

				if( $controllerName === null ) throw new Exception( 'No Controller given, aborted!' );
				if( $actionName === null ) throw new Exception( 'No Action given, aborted!' );

				// Controller allways are lowercase only the first Character is Uppercase
				// $controllerName = ucfirst( strtolower( $controllerName ) );
        $controllerName = ucfirst( $controllerName );

				// use PHP reflectionAPI to get result
				$controller = self::createController( $controllerName );
				$response = self::callMethod( $controller, $actionName );

				// return result into data and set succes true
				$result['state'] = true;
				$result['data'] = $response;

			}

		} catch( Exception $e ) {
			// catch any exceptions, set success false and report the problem
			$result['state'] = false;
			$result['errormsg'] = $e->getMessage();
		}

		if( isset($_GET['debug']) && $_GET['debug'] === "1" ) {
			$result['time'] = round( ( microtime(true) - $time_start ), 3 );
			echo "<pre>";
			print_r( $result );
			//var_dump( $result );
			echo "</pre>";
		} else if ( $result ) {
      // if( $result['data'] === $initial ) {
      //   // we are on an initial request
      // }
      // return our json encoded result to the requester
      header( 'Cache-Control: no-cache, must-revalidate' );
			header( "Access-Control-Allow-Origin: *" );
			header( "Content-Type: application/json charset=UTF-8" );
			print_r( json_encode( $result ) );
		} else {
			echo "<pre>";
			var_dump( $result );
			echo "</pre>";
		}

		session_write_close();

		//      // redirect to last destination as form fallback on no js.
		//      if( isset( $_REQUEST['redirect'] )  ) {
		//       	header ( "Location: " . $_REQUEST['redirect'] );
		//      }

	}


	/**
	 * Helper function to set parameters
	 */
	private function setParameter( $action ) {
		$aufrufParameter = Array();
		$refParameter = $action->getParameters();

		foreach( $refParameter as $p => $param ) {
			$parameterName = $param->getName();
			if ( $parameterName == '_db' ) {
				// we have a db request
				$wert = $this->_db;
			} else if ( $parameterName == '_uid' ) {
				// we have a uid in our request, validate them
				if ( $_SESSION && array_key_exists( 'uid', $_SESSION ) ) {
					//						$wert = decrypt( $_SESSION['uid'] );
					$wert = $_SESSION['uid'];
				} else {
					$wert = NULL;
				}
			} else if ( $parameterName[0] != '_' && $_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists($parameterName, $_POST) ) {
				$wert = $_POST[$parameterName];
			} else if ( $parameterName[0] != '_' && array_key_exists( $parameterName, $_GET ) ) {
				$wert = $_GET[$parameterName];
			} else if ( $param->isDefaultValueAvailable() ) {
				$wert = $param->getDefaultValue();
			} else {
				$wert = null;
			}
			$aufrufParameter[$p] = $wert;
		} // end foreach

		return $aufrufParameter;
	}


	/**
	 * Helper function to create a controller
	 */
	public function createController( $controllerName ) {
		$refController 		= $this->instanceOfController( $controllerName );
		$refConstructor 	= $refController->getConstructor();
		if ( ! $refConstructor ) return $refController->newInstance();
		$initParameter 		= $this->setParameter( $refConstructor );
		return $refController->newInstanceArgs( $initParameter );        
	}

	/**
	 * Herlper function to create the Instance of a controller
	 * using autoload to get the nessesary interfaces and extensions
	 */
	public function instanceOfController( $controllerName ) {
		// Load interface & extensions from folders
		spl_autoload_register( function ( $name ) {
				$interfaceClass		= __DIR__ . $this->_interfaceFolder . "/" . $name . ".php";
				if( is_file( $interfaceClass ) ) include $interfaceClass;
				$extensionClass		= __DIR__ . $this->_extensionFolder . "/" . $name . ".php";
				if( is_file( $extensionClass ) ) include $extensionClass;
				});
		$controllerClass	= __DIR__ . $this->_controllerFolder . "/" . $controllerName . ".php";
		if( is_file( $controllerClass ) ) require_once( $controllerClass );
		else throw new Exception( "Unable to load class $controllerClass" );
		// return the new instance
		$refController		= new ReflectionClass( $controllerName );
		return $refController;
	}

	/**
	 * Helper function to get result from a action
	 */
	private function callMethod( $controller, $action ) {
		$refController 		= new ReflectionObject( $controller );
		$refMethode 			= $refController->getMethod( $action );
		$aufrufParameter 	= $this->setParameter( $refMethode );
		return $refMethode->invokeArgs( $controller, $aufrufParameter );        
	}

}

?>
