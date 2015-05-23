<?php

interface Modules {

    public function getStatus(  );
    public function setStatus( $value );
    
    public function getGPIO(  );
    public function setGPIO( $value );
    
    public function getActive(  );
    public function setActive( $value );
    
}

?>
