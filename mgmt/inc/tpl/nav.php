<?php

$path = "./";
$mask = "*";
$prefix = ".php";

// read all php files by glob
$files = glob( $path . $mask . $prefix);

// resorting 
rsort( $files );

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  
  <a class="navbar-brand" href="./"><img src="inc/img/favicon.ico" alt="BiotoPi" height="26px" /></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
      <?php foreach( $files as $key => $value ) : ?>
        <?php
          $name = ucfirst( str_replace( array( $path, '.php' ), array( '', '' ), $value ) );
          if( $name === 'Index' ) continue; // exclude Index
        ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?= $value ?>"><?= $name ?></a>
        </li>
      <?php endforeach; ?>

      
<!--      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Controllers
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="controllers/protocols.php">Protocols</a>
          <a class="dropdown-item" href="controllers/protocoltypes.php">Protocoltypes</a>
          <a class="dropdown-item" href="controllers/daemontypes.php">Daemontypes</a>
          <a class="dropdown-item" href="controllers/devicetypes.php">Devicetypes</a>
          <a class="dropdown-item" href="controllers/subtypes.php">Subtypes</a>
          <a class="dropdown-item" href="controllers/jobs.php">Jobs</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="controllers/config.php">Config</a>
          <a class="dropdown-item" href="controllers/system.php">System</a>
        </div>
      </li>

     <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->

    </ul>
		
<!--    <form class="form-inline my-2 my-lg-0">
      <input id="searchstrings" class="navbar-search form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
		
		<script type="text/javascript">
			$( '.navbar-search' ).on( 'keyup', function() {
				var v = $(this).val();
				if( v.length < 3 ) return;
				highlight( v );
			});
		</script> -->
				
  </div>
</nav>
