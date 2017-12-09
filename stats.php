<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php 
incl('inc/head.php');
require_once( '/var/www/inc/class/class.File.php' );
// bmp085
$tmparr = json_decode( File::read( realpath("inc/") . "/tmp/" . BMP085FILE ), true );
if( $tmparr !== null ) {
	$bmpdata = "";
	$total = count( $tmparr ) -1;
	foreach ($tmparr as $key => $value) {
		$bmpdata .= $value;
		if( $key < $total ) $bmpdata .= ", ";
	}
	echo '<script type="text/javascript">';
	echo 'var bmpArr = [ ' .  $bmpdata  . ' ];';
	echo '</script>';
} else {
	echo '<script type="text/javascript">';
	echo 'var bmpArr = [ 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 ];';
	echo '</script>';
}

// radiation
$tmparr = radiation();
if( $tmparr !== null ) {
	$uSvarray = "";
	$total = count( $tmparr ) -1;
	foreach ( $tmparr as $key => $value ) {
		$uSvarray .= $value['uSv'];
		if( $key < $total ) $uSvarray .= ", ";
	}
	echo '<script type="text/javascript">';
	echo 'var uSvarray = [ ' .  $uSvarray  . ' ];';
	echo '</script>';
} else {
	echo '<script type="text/javascript">';
	echo 'var uSvarray = [ 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 ];';
	echo '</script>';
}
?>
</head>

<body>

<?php echo file_get_contents( 'inc/svg/svg-defs.svg', FILE_USE_INCLUDE_PATH ); ?>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<div class="col-sm-12 row">
	
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<h4><span class="icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-temp"></use></svg></span> Temperatur</h4>
			</div>
			<div class="panel-body">
				<p>Anzeigen der <b>ds18b20</b> Temperatur Sensoren. Daten können via AJAX anhand der CSS id und class Eigenschaften aus dem C-Programm abgefragt werden.<br />
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
	</div>
	
	<div class="col-sm-12 row">
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
						<th>Altitude <span style="font-weight:bold;float:right">last 24h.</span></th>
						<th>hPa</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>70m <span id="bmp-spark" class="system bmp-spark" data-width="100px" size="2.5" color="#09F #0FF #F00" style="float:right"></span></td>
						<td id="bmp085_70" class="bmp085"></td>
					</tr>
					
				</tbody>
			</table>
		</div><!-- END .panel -->
	</div>

<?php if($radiation !== null) : ?>	
	<div class="col-sm-12 row">
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<h4><span class="icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-radiation"></use></svg></span> Ionisierte Teilchen</h4>
			</div>
			<div class="panel-body">
				<p>Anzeigen der durchschnitlichen Anzahl von <b>ionisierten Teilchen</b>.<br />
					<br />GPIO Port: <b>-</b>
					<br />Protokoll: <b>usb/rs232 FTDI</b>
				</p>
			</div>

			<!-- Table -->
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th><span style="font-weight:bold;float:right">last 24h.</span></th>
						<th>Wert</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>Counts per Second</td>
						<td id="radcps" class="radcps"></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Counts per Minute</td>
						<td id="radcpm" class="radcpm"></td>
					</tr>
					<tr>
						<td>3</td>
						<td>uSv/h <span id="uSv-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span></td>
						<td id="uSv" class="uSv"></td>
					</tr>
					<tr>
						<td>4</td>
						<td>Geigercounter mode</td>
						<td id="radmode" class="radmode"></td>
					</tr>
				</tbody>
			</table>
		</div><!-- END .panel -->
	</div>
<?php endif; ?>
	<div class="col-sm-12 row">
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<h4><span class="icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-rlfeuchte"></use></svg></span> Relative Luftfeuchte</h4>
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
						<th>#</th>
						<th><span class="tableHead" style="float:right"></span></th>
						<th>value</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td> <div style="float:right"><span id="dhthum-spark" class="system sparkline" data-width="100px" size="2.5" color="#0FF #09F #F00"></span><br /><span id="dhttemp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto"></span></div></td>
						<td id="dht11" class="dht11"></td>
					</tr>
					
				</tbody>
			</table>
		</div><!-- END .panel -->
	</div>
	
	<div class="col-sm-12 row">
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
						<th> <span class="tableHead" style="float:right"></span></th>
						<th>mm</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>...</td>
						<td id="hc-sr04_1" class="hc-sr04"></td>
					</tr>
					
				</tbody>
			</table>
		</div><!-- END .panel -->
	</div>
	
	<div class="col-sm-12 row">
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
<?php

$system = array(	
	array( 'name' => 'Hostname', 'span' => '', 'id' => 'name', 'class' => '' ),
	array( 'name' => 'Temperatur', 'span' => '<span id="temp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'temp', 'class' => '' ),
	array( 'name' => 'Auslastung 1m', 'span' => '<span id="avg1-spark" class="system sparkline" data-width="100px" size="2" color="auto" style="float:right"></span>', 'id' => 'avg1', 'class' => '' ),
	array( 'name' => 'Auslastung 5m', 'span' => '<span id="avg5-spark" class="system sparkline" data-width="100px" size="2" color="auto" style="float:right"></span>', 'id' => 'avg5', 'class' => '' ),
	array( 'name' => 'Auslastung 15m', 'span' => '<span id="avg15-spark" class="system sparkline" data-width="100px" size="2" color="auto" style="float:right"></span>', 'id' => 'avg15', 'class' => '' ),
	array( 'name' => 'Aktive Tasks', 'span' => '', 'id' => 'scha', 'class' => '' ),
	array( 'name' => 'Gesamt Tasks', 'span' => '', 'id' => 'scht', 'class' => '' ),
	array( 'name' => 'Gesamter Speicher', 'span' => '', 'id' => 'memt', 'class' => '' ),
	array( 'name' => 'Freier Speicher', 'span' => '', 'id' => 'memf', 'class' => '' ),
	array( 'name' => 'Verwendeter Speicher', 'span' => '', 'id' => 'mema', 'class' => '' ),
	array( 'name' => 'Prozentual belegter Speicher', 'span' => '<span id="memp-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'memp', 'class' => '' ),
	array( 'name' => 'Gesamtes Dateisystem', 'span' => '', 'id' => 'filet', 'class' => '' ),
	array( 'name' => 'Benutzes Dateisystem', 'span' => '', 'id' => 'fileu', 'class' => '' ),
	array( 'name' => 'Freies Dateisystem', 'span' => '', 'id' => 'filef', 'class' => '' ),
	array( 'name' => 'Prozentual belegtes Dateisystem', 'span' => '<span id="filep-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'filep', 'class' => '' ),
	array( 'name' => 'Netzwerk Device IP Adresse', 'span' => '', 'id' => 'netip', 'class' => '' ),
	array( 'name' => 'Netzwerk Device Empfangen', 'span' => '<span id="netin-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'netin', 'class' => '' ),
	array( 'name' => 'Netzwerk Device Gesendet', 'span' => '<span id="netout-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'netout', 'class' => '' ),
	array( 'name' => 'Apt updates (NonSecurity/Security)', 'span' => '', 'id' => 'updates', 'class' => '' ),
	array( 'name' => 'Bahn checker (Cronjob Status)', 'span' => '', 'id' => 'bahnjob', 'class' => '' ),
	array( 'name' => 'DS18b20 Temperatur (Cronjob Status)', 'span' => '', 'id' => 'cds18b20', 'class' => '' ),
	array( 'name' => 'BMP085 Barometric Pressure (Cronjob Status)', 'span' => '', 'id' => 'cbmp085', 'class' => '' ),
//	array( 'name' => '', 'span' => '', 'id' => '', 'class' => '' ),
	array( 'name' => 'Check Runtime', 'span' => '<span id="runtime-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'runtime', 'class' => '' ),
//	array( 'name' => 'Radioaktivität in uSv/h', 'span' => '<span id="uSv-spark" class="system sparkline" data-width="100px" size="2.5" color="auto" style="float:right"></span>', 'id' => 'uSv', 'class' => '' ),
);

foreach( $system as $id => $system_arr ) {

	echo '<tr>'."\n";
	echo '<td>' . ( $id +1 ) . '</td>'."\n";
	$curr_id = '';
	foreach ( $system_arr as $key => $value ) {
	
		if( $key == 'name' ) echo '<td>' . $value;
		
		if( $key == 'span' ) {
			if( $value != null || $value != "" ) {
				echo ' ' . $value;
			}
			echo "</td>\n";
		}
		
		if( $key == 'id' && $value != '' ) $curr_id = $value;
		
		if( $key == 'class' ) {
			if( $value != null && $value != '' ) {
				echo '<td id="' . $curr_id . '" class="system ' . $value . '">' . "</td>\n";
			} else {
				echo '<td id="' . $curr_id . '" class="system"></td>' . "\n";
			}
		}
		
	}
	echo '</tr>'."\n";
}

?>
				</tbody>
			</table>
		
		</div><!-- END .panel -->
	</div>
	
	<div class="col-sm-12 row">
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
	</div>
	
	<div class="col-sm-12 row">
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
	</div>
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->


</body>
</html>
