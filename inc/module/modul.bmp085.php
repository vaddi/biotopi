<?php

class BMP085 implements Modules {
	
	private $id = null;
	private $alt = null;
	private $value = null;
	private $adresse = "0x77";
	
	private $gpio = null;
	private $status = null;
	private $active = null;
	
	public function __construct(  ) {
		$this->id = get_class( $this );

	}
	
	//
	//  INTEFACE
	//
	
	public function getStatus(  ) {
		return $this->status;
	}
  public function setStatus( $value ) {
		$this->status = $value;
	}
  
  public function getGPIO(  ) {
		return $this->gpio;
	}
  public function setGPIO( $value ) {
		$this->gpio = $value;
	}
  
  public function getActive(  ) {
		return $this->active;
	}
  public function setActive( $value ) {
		$this->active = $value;
	}
	
	
	//
	//  INTERFACE
	//
	
	public function getPa( $alt ) {
		$retArr[0]['pa'] = $this->getPascal( $alt );
		return $retArr;
	}
	
	public function getAlt() {
		return $_GET['alt'];
	}
	
	public function alle() {
		
		$retArr[0]['id'] = $this->id;
		$retArr[0]['status'] = $this->getStatus();
		$retArr[0]['gpio'] = $this->getGPIO();
		$retArr[0]['active'] = $this->getActive();
		$alt = $this->getAlt();
		$retArr[0]['alt'] = $alt;
		$retArr[0]['pa'] = $this->getPa( $alt );
		
		return $retArr;
		
	}
	
	public function auflisten() {
		if( isset( $_GET['alt'] ) ) {
			$alt = $_GET['alt'];
			$retArr[0]['alt'] = $alt;
			$retArr[0]['pa'] = $this->getPascal( $alt );
			
			return $retArr;
		} 
	}
	
	
	private function getPascal( $alt = null ) {
		if( $alt != null ) {
			$absolutPath = realpath("../");
			$altitude = shell_exec("sudo $absolutPath/inc/bin/bmp085 $alt" );
			if( $altitude != null ) {
				return $altitude;
			} else {
				return false;
			}
		} 
	}
	
}

?>
