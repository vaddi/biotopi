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
	
	<?php // incl('inc/nav.php'); ?>
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-fire" aria-hidden="false"></span> Temperatur</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der <b>ds18b20</b> Temperatur Sensoren. Daten können via AJAX anhand der CSS id und class Eigenschaften aus dem C-Programm abgefragt werden. <br />
		  	<br />GPIO Port: <b>17</b>
		  	<br />Protokoll: <b>oneWire</b>
		  </p>
		</div>

		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Device ID</th>
					<th>Temperatur</th>
		  	</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>28-000004d0e3cf</td>
					<td id="28-000004d0e3cf" class="ds18b20"></td>
		  	</tr>
		  	<tr>
		  		<td>2</td>
					<td>28-000004bf4dbe</td>
					<td id="28-000004bf4dbe" class="ds18b20"></td>
		  	</tr>
		  	<tr>
		  		<td>3</td>
					<td>28-000004cbaf9e</td>
					<td id="28-000004cbaf9e" class="ds18b20"></td>
		  	</tr>
		  	<tr>
		  		<td>4</td>
					<td>28-000004bfd99e</td>
					<td id="28-000004bfd99e" class="ds18b20"></td>
		  	</tr>
		  	<tr>
		  		<td>5</td>
					<td>28-000004cd81ba</td>
					<td id="28-000004cd81ba" class="ds18b20"></td>
		  	</tr>
			</tbody>
		</table>
	</div><!-- END .panel -->
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-cloud" aria-hidden="false"></span> Luftdruck</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der <b>bmp085</b> Luftdruck Sensors. Daten können via AJAX anhand der CSS id und class Eigenschaften aus dem C-Programm abgefragt werden. <br />
				<br />GPIO Port: <b>2, 3</b>
				<br />Protokoll: <b>i2c</b>
		  </p>
		</div>

		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Altitude</th>
					<th>hPa</th>
		  	</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>70m</td>
					<td id="bmp085_70" class="bmp085"></td>
		  	</tr>
		  	
			</tbody>
		</table>
	</div><!-- END .panel -->
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-tint" aria-hidden="false"></span> Relative Luftfeuchte</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der <b>dht11</b> Abstandssensor Werte. Daten können via AJAX anhand der CSS id und class Eigenschaften aus dem C-Programm abgefragt werden. <br />
		  	<br />GPIO Port: <b>7</b>
		  	<br />Protokoll: <b>raw</b>
		  </p>
		</div>

		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th># <span class="tableHead" style="float:right"></span></th>
					<th>value</th>
		  	</tr>
			</thead>
			<tbody>
				<tr>
					<td>1 <div style="float:right"><span id="dhthum-spark" class="system sparkline" data-width="100px" size="2.5" color="#0FF #09F #F00"></span><br /><span id="dhttemp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto"></span></div></td>
					<td id="dht11" class="dht11"></td>
		  	</tr>
		  	
			</tbody>
		</table>
	</div><!-- END .panel -->
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-tint" aria-hidden="false"></span> Füllstand</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der <b>hc-sr04</b> Abstandssensor Werte. Daten können via AJAX anhand der CSS id und class Eigenschaften aus dem C-Programm abgefragt werden. <br />
		  	<br />GPIO Ports: <b>18, 23</b>
		  	<br />Protokoll: <b>raw</b>
		  </p>
		</div>

		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>l</th>
		  	</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td id="hc-sr04_1" class="hc-sr04"></td>
		  	</tr>
		  	
			</tbody>
		</table>
	</div><!-- END .panel -->
	
	
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-dashboard" aria-hidden="false"></span> System</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der Systemwerte. Daten können via AJAX anhand der CSS id und class Eigenschaften abgefragt werden.
		  </p>
		</div>

		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>name <span class="tableHead" style="float:right"></span></th>
					<th>value</th>
		  	</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>Hostname</td>
					<td id="name" class="system"></td>
		  	</tr>
				<tr>
					<td>2</td>
					<td>Temperatur <span id="temp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span></td>
					<td id="temp" class="system"></td>
		  	</tr>
				<tr>
					<td>3</td>
					<td>Auslastung 1m <span id="avg1-spark" class="system sparkline" data-width="100px" size="2" color="auto" style="float:right"></span></td>
					<td id="avg1" class="system"></td>
		  	</tr>
				<tr>
					<td>4</td>
					<td>Auslastung 5m <span id="avg5-spark" class="system sparkline" data-width="100px" size="2" color="auto" style="float:right"></span></td>
					<td id="avg5" class="system"></td>
		  	</tr>
				<tr>
					<td>5</td>
					<td>Aktive Tasks</td>
					<td id="scha" class="system"></td>
		  	</tr>
				<tr>
					<td>6</td>
					<td>Gesamt Tasks</td>
					<td id="scht" class="system"></td>
		  	</tr>
				<tr>
					<td>7</td>
					<td>Gesamter Speicher</td>
					<td id="memt" class="system"></td>
		  	</tr>
				<tr>
					<td>8</td>
					<td>Freier Speicher</td>
					<td id="memf" class="system"></td>
		  	</tr>
					<td>9</td>
					<td>Verwendeter Speicher</td>
					<td id="mema" class="system"></td>
		  	</tr>
		  	<tr>
					<td>9</td>
					<td>Prozentual belegter Speicher <span id="memp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span></td>
					<td id="memp" class="system"></td>
		  	</tr>
				<tr>
				<tr>
					<td>10</td>
					<td>Gesamtes Dateisystem</td>
					<td id="filet" class="system"></td>
		  	</tr>
				<tr>
					<td>11</td>
					<td>Benutzes Dateisystem</td>
					<td id="fileu" class="system"></td>
		  	</tr>
				<tr>
					<td>12</td>
					<td>Freies Dateisystem</td>
					<td id="filef" class="system"></td>
		  	</tr>
				<tr>
					<td>13</td>
					<td>Prozentual belegtes Dateisystem <span id="filep-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span></td>
					<td id="filep" class="system"></td>
		  	</tr>
				<tr>
					<td>14</td>
					<td>Netzwerk Device Empfangen <span id="netin-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></td>
					<td id="netin" class="system"></td>
		  	</tr>
		  	<tr>
					<td>15</td>
					<td>Netzwerk Device Gesendet <span id="netout-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></td>
					<td id="netout" class="system"></td>
		  	</tr>
		  	<tr>
					<td>16</td>
					<td>Apt updates (NonSecurity/Security)</td>
					<td id="updates" class="system"></td>
		  	</tr>
		  	<tr>
					<td>17</td>
					<td>Check Runtime <span id="runtime-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></td>
					<td id="runtime" class="system"></td>
		  	</tr>
			</tbody>
		</table>
		
	</div><!-- END .panel -->
	
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-hdd" aria-hidden="false"> Dateisystem</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen der Systemwerte. Daten können via AJAX anhand der CSS id und class Eigenschaften abgefragt werden.
		  </p>
		  
		</div>
		
		<!-- Table -->
		<table class="table">
			<tbody>
				<tr>
					<td style="width:100px;"><span class="glyphicon glyphicon-folder-open" aria-hidden="false"> /</td>
					<td>
						<div class="progress">
							<div id="filepbar" class="progress-bar system" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100.0" style="min-width: 2em; width: 5%;">
								0%
							</div>
						</div>
					</td>
					
				</tr>
				<tr>
					<td style="width:100px;"><span class="glyphicon glyphicon-inbox" aria-hidden="false"> ram</td>
					<td>
						<div class="progress">
							<div id="mempbar" class="progress-bar system" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100.0" style="min-width: 2em; width: 5%;">
								0%
							</div>
						</div>
					</td>
					
				</tr>
			</tbody>
		</table>
		
	</div><!-- END .panel -->
	
	
	
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h4><span class="glyphicon glyphicon-fire" aria-hidden="false"> Irgendwas</h4>
		</div>
		<div class="panel-body">
		  <p>Anzeigen von irgendeinem Text. Random Inine Sparklines <span id="sparkle" class="system sparkline" data-width="100px" size="2.5" color="auto"></span> generated from ajax request.</p>
		</div>
		<!-- #F00 #090 #00F -->
	</div><!-- END .panel -->
	
	
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->


</body>
</html>
