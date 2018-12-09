<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div class="row">
		<div class="col-sm-12">
      <h3>About</h3>
			<p>Heimautomation für Terrarien/Aquarien</p>
			<div class="col-sm-2 pull-right">	
				<a href="inc/img/waterdragon.jpg" title="Meine Wasseragamendame">
					<img src="inc/img/waterdragon.jpg" alt="Wasseragame" class="img-thumbnail" />
				</a>
				<div style="margin:0 0 32px;text-align: center;">		
					&copy;<a href="https://500px.com/rolandrebholz" target="_blank">Roland Rebholz Fotografie</a>
				</div>
			</div>
			<p>Um meiner Wasseragame ein möglichst heimisches Umfeld bieten zu können, habe ich ihr eine Heimautomation für ihr Terrarium gebaut.</p>
		</div>

		<div class="col-sm-12">
			<?php

#				echo "<pre>";
#				print_r( radiation( true ) ); // current µSv
##				print_r( radiation() ); 			// last 24 hourly µSv
#				echo "</pre>";

			?>
		</div>

		<div class="col-sm-12">
			<h3>Verwendete Hardware:</h3>
			<ul>
				<li>2004 LCD Display (mit i2c Ansteuerung)</li>
				<li>RaspberryPi b+</li>
				<li>DS18b20 Temperatur Sensoren (One Wire)</li>
				<li>HC-SR04 Berührungslose Füllstands Ermittlung</li>
				<li>DHT11 Luftfeuchte und Temperatur Sensor</li>
				<li>Bodenfeuchtigkeitssensoren</li>
				<li>BMP085 Barometer</li>
				<li>USB Kamera</li>
				<li>MCP3008 10 Kanal A/D Wandler (SPI)</li>
				<li>8faches SSD-Relais (über 74hc595 Schieberegister) zur Schaltung von:<br>
					<ul style="list-style: roman;">
						<li>Lüfter</li>
						<li>Beregnungsanlage</li>
						<li>Aquarien-Heizstab</li>
						<li>HQL Lampe</li>
						<li>UVA/UVB Lampe</li>
						<li>Energy-Spar Lampe</li>
						<li>Neonlicht im Terrarium</li>
						<li>Neonlicht im Aquarium</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="col-sm-12">
			<h3>Funktionen:</h3>
			<ul>
				<li>Webserver zur Ausgabe und Konfiguration</li>
				<li>Status/Warn/Alarm Mail versand bei Über oder unterschreiten von Messwerten</li>
				<li>Steuerung von Lampen und Beregnungsanlage (oder anderer 220V Komponenten)</li>
				<li>Selbstdimmendes LCD Display ()</li>
				<li>Daten werden in MySQL Datenbank (Lokal oder Remote) gespeichert</li>
				<li>Luft, Wasser und Bodentemperatur sowie Relative Luftfeuchte können als Wertematrix hinterlegt werden um bestimmte Regionen nachbilden zu können.</li>
			</ul>
		</div>
    
    <div class="col-sm-12" style="margin-bottom:50px;">
      <h3>Other:</h3>
			<ul>
				<li><a href="../inc/assets/sql/?sqlite=" target="_blank">Adminer SQL Editor</a></li>
				<li><a href="../inc/assets/installation/" target="_blank">Installation Relevant</a></li>
        <li><a href="../inc/assets/documentation/Framework.md" target="_blank">Documentation</a></li>
			</ul>
    </div>
    
	</div><!-- END row-->
  
	<?php require_once( 'inc/tpl/footer.php' ); ?>

</div><!-- END container-->

</body>
</html>