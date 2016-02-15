<?php

require_once('class.Modules.php');

class Dispatcher {
	
//	private $db;

  public function __construct() {
		// 										 "URL"			"DBUSER" "DBPASS" "DBNAME"
//    $this->db = new mysqli('test', 'dbuser', 'unsicher', 'buch');
//    $this->db->set_charset("utf8");
  }

  public function __destruct() {
//    if ($this->db) $this->db->close();
  }
    
	public function httpRequestBehandeln() {
		session_start();
		// TODO
		$_SESSION['benutzer'] = Array(
                   'id' => 1,
                   'name' => "admin"
                );
//		print_r($_SESSION);
		$dienstName = $_GET['$modul'];
		$operationsName = $_GET['$operation'];
		$dienst = self::dienstErstellen( $dienstName );
		$ergebnis = self::methodeAufrufen( $dienst, $operationsName );
		if( $ergebnis !== NULL ) {
			header('Content-type: text/plain; charset=UTF-8');
			echo json_encode( $ergebnis );
		}
		session_write_close();
	} // END httpRequestBehandeln
	
	public function dienstErstellen($dienstName) {
    require_once("module/modul.$dienstName.php");
    $refDienst = new ReflectionClass($dienstName);
    $refConstructor = $refDienst->getConstructor();
    if (!$refConstructor) return $refDienst->newInstance();
    $initParameter = self::parameterSetzen($refConstructor);
    return $refDienst->newInstanceArgs($initParameter);        
  }

  private function parameterSetzen($operation) {
    $aufrufParameter = Array();
    $refParameter = $operation->getParameters();
    foreach($refParameter as $p => $param) {
      $parameterName = $param->getName();
            if ($parameterName == '_db') {
//                $wert = $this->db;
            } else if ($parameterName == '_benutzerId') {
                if ($_SESSION && array_key_exists('benutzer', $_SESSION)) {
                    $wert = $_SESSION['benutzer']['id'];
//                    print_r( $_SESSION['benutzer']['id'] );
                } else {
                    $wert = NULL;
                }
            } else if ($parameterName[0] != '_' && $_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists($parameterName, $_POST)) {
                $wert = $_POST[$parameterName];
            } else if ($parameterName[0] != '_' && array_key_exists($parameterName, $_GET)) {
                $wert = $_GET[$parameterName];
            } else if ($param->isDefaultValueAvailable()) {
                $wert = $param->getDefaultValue();
            } else {
                $wert = null;
            }
            $aufrufParameter[$p] = $wert;
    }
    return $aufrufParameter;
  }
	
	private function methodeAufrufen($dienst, $operation) {
    $refDienst = new ReflectionObject($dienst);
    $refMethode = $refDienst->getMethod($operation);
    $aufrufParameter = self::parameterSetzen($refMethode);
    return $refMethode->invokeArgs($dienst, $aufrufParameter);        
  }
  
	
} // END class Dispatcher


?>
