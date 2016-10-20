<!DOCTYPE html>
<?php 
$preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; 
// Check for installer file to help install some neccessary stuff
$fload = 'install.php'; 
if (file_exists($fload)) return include $fload;

//$db = new SQLite3(DB_FILENAME);

//

//$db->exec("INSERT INTO users(name, creates, since) VALUES ('vaddi','10:00:00',1)");

//$erg = $db->query("SELECT * FROM users");   
//print_r($erg->fetchArray());

?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php');
	
	if( cronState( '/var/log/cron/biotopi.log', 'temp_' ) ) {
		print_r( 'cron running' );
	} 
	
	
		?>
	<div>
		
		<div class="col-sm-12 row">
			<h3>Heimautomation für Terrarien/Aquarien</h3>

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
		
		<div class="col-sm-12 row">
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

		<div class="col-sm-12 row" style="margin-bottom:50px;">
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
		
	</div>	

	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
