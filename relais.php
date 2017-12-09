<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
</head>

<body>

<div class="container">

	<?php
		incl('inc/header.php');
	?>

	<div><h3>Switch Relais<small> Daten an die Relais (byte 2 ShiftRegister) senden</small></h3>

		<div id="msg"></div>

		<form id="relais_form" action="inc/module/relais.php" method="POST">

			<?php
				// useful stuff:

				// convert int 2 bin
				// string decbin( int $number )

				// convert bin 2 int
				// number bindec( string $binary_string )

				// data file
				$file = 'inc/tmp/relais_new.dat';
				// Get old data from file
				require_once( 'inc/class/class.File.php' );
				$filedata = File::read( $file ) ; // json_decode(

//				echo "<pre>";
//				print_r( $filedata );
//				echo "</pre>";

				// 8bit Register addresses
//				$register = array( 1, 2, 4, 8, 16, 32, 64, 128 );

				// 16bit Register addresses
				$register = array( 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768 );

				// TODO get current bits from filedata
				$value = $filedata; // dummy value

				// reminder, here we store the current value
					echo '<input type="hidden" id="current" value="' . $value . '" />';

				// AMOUNT OF RELAIS TO SWITCH
				$amount = count( $register ); // how many bits? Per ShiftRegister = one Byte (8bit|16bit|32bit)

				// iterate over all registers (reverse)
				for( $i = $amount -1; $i >= 0; $i-- ) {
					$id = $i +1;
					$state = false;

					echo '<div class="form-group row">';

					echo '<div class="col-sm-2"><label>Relais ' . $id . '</label></div>';
					echo '<div class="col-sm-10">';

					$byte = $value;

					if( ( $byte & $register[ $i ] ) == 0 ) {
						// Off
						$state = null;
					} else {
						// On
						$state = "checked";
						$byte -= $register[ $i ];
					}

					echo checkboxHTML( "rel_" . $id, $register[ $i ], $state, '| | |', null, 'rel_' . $id, "relEvent( '#' + this.id )" ) . "\n";

					echo '</div>';


					// we need on and off times for each relais.
					// if possible, add and removable fields, but allways both
					// validation of dates: start cannot before end (state = off)
					//



					// Datetime fields
//					echo dateHTML( 'startdate_'. $id, null, isset( $_REQUEST['startdate_'. $id] ) ? $_REQUEST['startdate_'. $id] : date( DATEFORM ), true, 'Startdate for Relais ' . $id ) . "\n";
//
//					echo dateHTML( 'enddate_'. $id,  null, isset( $_REQUEST['enddate_'. $id] ) ? $_REQUEST['enddate_'. $id] : date( DATEFORM ), true, 'Startdate for Relais ' . $id ) . "\n";


					echo "</div>\n";

				} // end for

				// special

				echo '<div class="form-group row">';
				echo '<div class="col-sm-2"><label>All Relais</label></div>';
				echo '<div class="col-sm-10">';
				echo checkboxHTML( "rel_all", '255', null, 'All', null, 'rel_all', "relEvent( '#' + this.id )" );
				echo checkboxHTML( "rel_none", '0', null, 'None', null, 'rel_none', "relEvent( '#' + this.id )" );
				echo '</div>';
				echo '</div>'."\n";

			?>
		</form>
	</div>

	<div style="margin-top: 10px;">See <a href="./bhd.php">BHD</a> to calculating Binary, Hex and Decimal.</div>

<script type="text/javascript">

//$.fn.bootstrapSwitch.defaults.size = 'normal';
$.fn.bootstrapSwitch.defaults.size = 'mini';

$.fn.bootstrapSwitch.defaults.onColor = 'success';

$("[name^='rel_']").bootstrapSwitch();

$("[name^='rel_']").on('switchChange.bootstrapSwitch', function(event, state) {
//  console.log(this); // DOM element
//  console.log(event); // jQuery event
//  console.log(state); // true | false
	relEvent( '#' + this.id );
//	console.log(state);
});

function relEvent( id ) {
//console.log( 'event ' + id );
	let state = $( id )[0].checked;
	let value = $( id ).val();
	let curr = parseInt( $('#current').val() );
	let erg = 0;
  let register = <?= count($register) ?>;
	if( state ) {
		// add relais
		erg = curr + parseInt( value );
	} else {
		// remove ralais
		erg = curr - parseInt( value );
	}
	// special (none, all)
	if( id == '#rel_none' ) erg = 0;
	if( id == '#rel_all' ) erg = Math.pow(2, register) - 1;
	$.get( "inc/module/relais.php?cid=" + cid + "&cmd=set&relais=" + erg, function( data ) {
		let jsonobj = eval("(" + data + ")");
		let resp = jsonobj[0][ 'resp' ];
		if( resp ) {
			// set ok
			// update hidden value (reminder)
			$('#current').val( erg );
			// special ( none, all )
			if( id == '#rel_none' || id == '#rel_all' ) {
//				$( id ).attr('checked', false); // unset self
//				$( id ).prop( "checked", false );
				// update all rel checkboxes
				if( id == '#rel_none' ) {
					$('input:checkbox').each(function( i,input ){
//						if( i <= 7 ) $( this ).attr('checked', false );
//						if( i <= 7 ) $( this ).prop( 'checked', false );
						if( i <= (register - 1) ) $( '#' + this.id ).bootstrapSwitch('state', false);
					});
				}
				if( id == '#rel_all' ) {
					$('input:checkbox').each(function( i,input ){
//						if( i <= 7 ) $( this ).attr( 'checked', true );
//						if( i <= 7 ) $( this ).prop( 'checked', true );
						if( i <= ( register -1 ) ) $( '#' + this.id ).bootstrapSwitch('state', true);
					});
				}
				setTimeout(function(){ $( id ).bootstrapSwitch('state', false); }, 400);
			}
		} else {
			// set fail
//			htmlMsg( 'msg', 'danger', 'Fail!', 'Register dont write!' );
		}
	});
}
</script>

	<?php incl('inc/footer.php'); ?>

</div><!-- END .containter -->

</body>
</html>
