<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>

<script type="text/javascript">
<!--
function ChangeValue(id) {

  var dec;
  var bin;
  var hex;

  // Get text from textbox
  value = document.getElementById(id).value;

  if (value!="") {

    switch(id)
    {
    case 'bin':
      // User supplied a binary number
      dec = parseInt(value, 2);
      if (isNaN(dec)) {dec=1;value='0000000000000001';}
      bin = value;
      hex = dec.toString(16);
      break;
    case 'dec':
      // User supplied a decimal number
      dec = parseInt(value, 10);
      if (isNaN(dec)) dec=1;
      if (value>255) value=65536;
      if (value<1) value=1;
      bin = dec.toString(2);
      hex = dec.toString(16);
      break;
    case 'hex':
      // User supplied a hexidecimal number
      dec = parseInt(value, 16);
      if (isNaN(dec)) {dec=1;value='1';}
      bin = dec.toString(2);
      hex = value;
      break;
    default:

    }

    // Update all text boxes
    document.getElementById('bin').value = bin;
    document.getElementById('dec').value = dec;
    document.getElementById('hex').value = hex.toUpperCase();

  }

}
// -->
</script>
<style>
#calculator {}
.label {color:#000; font-size:100%; font-weight:normal;}
.values {margin:5px;}
.values input {font-size:100%}
</style>
</head>

<body>

<div class="container">

	<?php incl('inc/header.php'); ?>

	<div><h3>BHD Convertor<small> Binär Hexadezimal und Dezimal Konverter</small></h3>
		<div id="calculator">
		<div class="label">Binary</div><div class="values"><input id="bin" type="text" value="1111111111111111" onkeyup="ChangeValue(this.id);" maxlength="16" size="18"/></div>
		<div class="label">Decimal</div><div class="values"><input id="dec" type="text" value="65535" onkeyup="ChangeValue(this.id);" maxlength="5" size="18" /></div>
		<div class="label">Hexadecimal</div><div class="values"><input id="hex" type="text" value="FFFF" onkeyup="ChangeValue(this.id);" maxlength="4"size="18"/></div>
		</div>
	</div>

	<p>Quelle: <a href="http://mattsbits.co.uk/webtools/bhd_convertor/">mattsbits.co.uk</a></p>

	<?php incl('inc/footer.php'); ?>

</div><!-- END .containter -->

</body>
</html>
