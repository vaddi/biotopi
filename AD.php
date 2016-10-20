<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php 

incl('inc/head.php'); 

// Setup devices (id = position in array)
$devices = array(	
									array(	'name' => 'Potiometer' ),
									array(	'name' => 'Regensensor' ),
									array(	'name' => 'Temperatur Widerstand' ),
									array(	'name' => 'Lichtsensor Transistor' ),
									array(	'name' => 'Bodenfeuchte Sensor' ),
									array(	'name' => 'UV Sensor' )
);

?>
<script type="text/javascript">

function rainState( value ) {
	value = map( value, 0, 1024, 0, 3);
	var erg = null;
	switch( value ) {
		case 0:
		  erg = "flood";
		  break;
		case 1:
		  erg = "storm";
		  break;
		case 2:
		  erg = "rain";
		  break;
		default:
		  erg = "dry";
	}
	return erg;
}

function lightState( value ) {
	if( value >= 820 ) {
		return "hot sunny day";
	} else if( value >= 615 && value < 820 ) {
		return "sunny day";
	} else if( value >= 410 && value < 615 ) {
		return "day";
	} else if( value >= 250 && value < 410 ) {
		return "dusk";
	} else if( value >= 0 && value < 250 ) {
		return "night";
	}
}

// http://www.cutedigi.com/blog/use-uv-sensor-with-arduino/
// @return (int) UV-Index Value (0-11)
function uvState( value ) {
	if( value >= 240 && value < 221 ) {
		return 11;
	} else if( value >= 200 && value < 221 ) {
		return 10;
	} else if( value >= 180 && value < 200 ) {
		return 9;
	} else if( value >= 162 && value < 180 ) {
		return 8;
	} else if( value >= 142 && value < 162 ) {
		return 7;
	} else if( value >= 124 && value < 142 ) {
		return 6;
	} else if( value >= 103 && value < 124 ) {
		return 5;
	} else if( value >= 83 && value < 103 ) {
		return 4;
	} else if( value >= 65 && value < 83 ) {
		return 3;
	} else if( value >= 46 && value < 65 ) {
		return 2;
	} else if( value >= 10 && value < 46 ) {
		return 1;
	} else if( value >= 0 && value < 10 ) {
		return 0;
	}
}

// @return (string) very dry, dry, ok, wet, very wet
function moistureState( value ) {
	if( value >= 820 ) {
		return "very dry";
	} else if( value >= 615 && value < 820 ) {
		return "dry";
	} else if( value >= 410 && value < 615 ) {
		return "ok";
	} else if( value >= 250 && value < 410 ) {
		return "wet";
	} else if( value >= 0 && value < 250 ) {
		return "very wet";
	}
}

function map( x, in_min, in_max, out_min, out_max ) {
	erg = ( x - in_min ) * ( out_max - out_min ) / ( in_max - in_min ) + out_min;
	return Math.round( erg * 100 ) / 100;
}


function ADpolling( intervall, page ) {
	if( intervall == null ) intervall = 10000; 			// default intervall 1 minute
	var loc = getLoc();
	if( page != null || page != "" ) {
		if( loc == page ) mcp3008();
	} else {
//		mcp3008();
	}
	if( intervall != 0 ) setTimeout( function(){ ADpolling( intervall, page ) }, intervall );
}

function mcp3008() {
	
	var device = null;
	var url_var = "inc/module/mcp3008.php"
	
	$('.mcp3008').each(function(i){
		device = $( this ).attr('id');
		var data = device.split("_")[1];
		var url_append = "?id=" + data;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done( function( html ) {
			var jsonobj = eval("(" + html + ")");
			var ident = jsonobj[0]['id'];
			var value = jsonobj[0]['value'];
			
			if( ident == 0 ) {
				// potiometer
				
			} else if( ident == 1 ) {
				// regensensor
				value = rainState( value );
			} else if( ident == 2 ) {
				// temp
				value = map( value, 0, 1024, 0, 190 ) + "°C";
			} else if( ident == 3 ) {
				// licht
				value = lightState( value );
			} else if( ident == 4 ) {
				// feuchte
				value = moistureState( value );
			} else if( ident == 5 ) {
				// uv sensor
//				value = '<a href="http://www.cutedigi.com/blog/wp-content/uploads/2014/06/500px-UV_index.png">' + uvState( value ) + '</a>';
				value = uvState( value ) + "/11";
			} 
			
			$( "#mcp3008_" + ident ).html( value );
//			console.log( $( "#mcp3008_" + ident ).attr('id') );
		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>MCP3008 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}
$( document ).ready( function() {
	ADpolling( 5000, 'AD.php' );
});
</script>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<div class="col-sm-12 row">
	
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<h4><span class="glyphicon glyphicon-dashboard" aria-hidden="false"></span> Analog/Digital Converter<small> mcp3008</small></h4>
			</div>
			<div class="panel-body">
				<p>Anzeigen der <b>mcp3008</b> Daten.<br />
					<br />GPIO Port: <b>--</b>
					<br />Protokoll: <b>raw</b>
				</p>
			</div>

			<!-- Table -->
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Device ID</th>
						<th>Device Name</th>
						<th>Wert</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ( $devices as $rowId => $row ) {
						foreach ( $row as $key => $value ) {
							echo "<tr>";
							echo "<td>" . ( $rowId +1 ) . "</td>";
							echo "<td>" . $rowId . "</td>";
							echo "<td>" .  $value . "</td>";
							echo "<td id='mcp3008_" . $rowId . "' class='mcp3008'></td>";
							echo "</tr>";
						}
					}
				?>
				</tbody>
			</table>
		</div><!-- END .panel -->
		
	</div>
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
