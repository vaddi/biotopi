<!DOCTYPE html>

<?php 
$preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; 
incl('inc/init.php');
?>

<html lang="<?= APPLANG ?>">

<head>
<?php incl('inc/head.php'); ?>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<?php incl('inc/nav.php'); ?>
	
	<p><h3>C-Software <small>Die kleinen Helfer</small></h3>
		<ul>
			<li>BMP085 Barometer (<a href="inc/bin/sources/bmp085.c">bmp085.c</a>) <a href="https://ninedof.wordpress.com/2013/07/16/rpi-hc-sr04-ultrasonic-sensor-mini-project/">ninedof.wordpress.com</a></li>
			<li>DS18b20 Temperatursensor (<a href="inc/bin/sources/ds18b20.c">ds18b20.c</a>)  </li>
			<li>HC-SR04 Füllstands ermittlung (<a href="inc/bin/sources/hc-sr04.c">hc-sr04.c</a>) </li>
			<li>DHT11 Luftfeuchte und Temperatur Sensor</li>
			
			<li>2004 LCD Display (mit i2c Ansteuerung)</li>
			<li>Bodenfeuchtigkeitssensoren</li>
			<li>USB Kamera</li>
			<li>SSD-Relais</li>
		</ul>
	</p>
	
	<p><h3>Andere <small>Web und GPIOs</small></h3>
		<ul>
			<li>Bootstrap <a href="http://getbootstrap.com/">Bootstrap</a></li>
			<li>WiringPi <a href="http://wiringpi.com/">WiringPi</a></li>
		</ul>
	</p>

	<?php incl('inc/footer.php'); ?>
	
	<div style="margin-bottom:60px;"></div>

</div><!-- END .containter -->

</body>
</html>
