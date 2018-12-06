<?php
  
// Form for Devices  

$form_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$devices = null;

?>

<form action="<?= $form_url ?>" method="post">
  
  <fieldset>
    <input type="text" name="name" />
  </fieldset>
    
</form>