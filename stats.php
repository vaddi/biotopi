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
		  	
			</tbody>
		</table>
	</div>
	
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
	</div>
	
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
	</div>
	
	
	<?php incl('inc/footer.php'); ?>
	
	<div style="margin-bottom:60px;"></div>

</div><!-- END .containter -->


</body>
</html>
