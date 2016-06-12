<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<p><h3>C-Software <small>Die kleinen Helfer</small></h3>
		<ul>
			<li>BMP085 Barometer (<a href="inc/bin/sources/bmp085.c">bmp085.c</a>) </li>
			<li>DS18b20 Temperatursensor (<a href="inc/bin/sources/ds18b20.c">ds18b20.c</a>)  </li>
			<li>HC-SR04 Füllstands ermittlung (<a href="inc/bin/sources/hc-sr04.c">hc-sr04.c</a>) <a href="https://ninedof.wordpress.com/2013/07/16/rpi-hc-sr04-ultrasonic-sensor-mini-project/">ninedof.wordpress.com</a></li>
			<li>DHT11 Luftfeuchte und Temperatur Sensor</li>
			
			<li>2004 LCD Display (mit i2c Ansteuerung)</li>
			<li>Bodenfeuchtigkeitssensoren</li>
			<li>USB Kamera</li>
			<li>SSD-Relais</li>
		</ul>
	</p>
	
	<p><h3>Links <small>nützliche Links</small></h3>
			<ul>
				<li class="lihi">Elektronische Komponenten</li>
				<li><a href="http://www.amazon.de/gp/product/B00LPESRUK">Raspberry Pi Model B+</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00K67YAY4">DHT11 Temperatur und relative Luftfeuchte Sensor-Modul</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00AX540LW">BMP085 Barometric Digital Pressure Sensor</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00HTTZO9C">2004 20x4 Character LCD Modul</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00NL9XNN8">UV Detection Sensor Module </a></li>
				<li><a href="http://www.amazon.de/gp/product/B005T6BA4K">MCP3008-I/P Microchip, A/D-Wandler</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00SYEPLI0">8 Channel Solid State Relay</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00ENA0M7E">Wasserdurchfluss Sensor</a></li>
				<li class="lihi">Zubehör</li>
				<li><a href="https://www.reichelt.de/?ARTICLE=160286&PROVID=2788&wt_mc=amc141526782519998&gclid=CNTugpKsm8sCFbMK0wodMOwJKQ">Hutschienengehäuse für den Raspberry Pi</a></li>
				<li><a href="www.amazon.de/gp/product/B001PPEPY6">Lüftergitter 120mm</a></li>
				<li><a href="http://www.amazon.de/gp/product/B0038OIGN8">Lüfter 230 V 120 x 120 x 25 mm 230 V AC</a></li>
				<li><a href="http://www.amazon.de/gp/product/B008YW0AWY">eSmart Germany Pflanzenlampe Faye</a></li>
				<li><a href="http://www.amazon.de/gp/product/B013UDL5V6">SanDisk Ultra Android microSDHC 16GB</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00N234VVG">Industrie Standard USB Netzteil 5V/2,5A</a></li>
				<li><a href="http://www.amazon.de/gp/product/B00MOUI7KE">Breadboard Adapter für Raspberry Pi B+</a></li>
				<li class="lihi">Bootstrap Erweiterungen</li>
				<li><a href="http://holdirbootstrap.de/">Bootstrap (deutsch)</a></li>
				<li><a href="http://www.bootstrap-switch.org/">Bootstrap-switch</a></li>
				<li><a href="https://silviomoreto.github.io/bootstrap-select/">Bootstrap-select</a></li>
				<li><a href="http://www.malot.fr/bootstrap-datetimepicker/">Bootstrap-datetimepicker</a></li>
				<li><a href="http://bootboxjs.com/">Bootbox</a></li>
				<li><a href="http://stackoverflow.com/a/17955149/5208166">Creating a daemon in Linux</a></li>
				<li><a href="http://www.netzmafia.de/skripten/unix/linux-daemon-howto.html">Linux Daemon Writing HOWTO</a></li>
				<li><a href="http://openbook.rheinwerk-verlag.de/linux_unix_programmierung/Kap07-000.htm#Xxx999234">Linux-UNIX-Programmierung</a></li>
				<li class="lihi">Andere</li>
				<li><a href=""></a></li>
				<li><a href=""></a></li>
			</ul>
		</p>
	
	<p><h3>Andere <small>Web und GPIOs</small></h3>
		<ul>
			<li>Bootstrap <a href="http://getbootstrap.com/">Bootstrap</a></li>
			<li>WiringPi <a href="http://wiringpi.com/">WiringPi</a></li>
		</ul>
	</p>

	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
