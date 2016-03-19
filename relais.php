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
		
		<form id="relais_form" action="inc/module/relais.php" method="POST">
			
			<?php
				// Get old data
				require_once( 'inc/class/class.File.php' );
				$value = (int) File::read( "inc/tmp/relais.dat" );
				$register = array( 1, 2, 4, 8, 16, 32, 64, 128 );
				
				echo '<div class="form-group row">';
				echo '<div class="col-sm-2"><label>Relais</label></div>';
				echo '<div class="col-sm-10">';
				
				// reminder, here we store the current value				
				echo '<input type="hidden" id="current" value="' . $value . '" />';

				$byte = $value;
				
				// AMOUNT OF RELAIS TO SWITCH
				$amount = count( $register ); // how many bits? Per ShiftRegister = one Byte (8bit|16bit|32bit)				
				
				for( $i = $amount -1; $i >= 0; $i-- ) {
					$state = false;
					
					if( ( $byte & $register[ $i ] ) == 0 ) {
						// Off
						$state = null;
					} else {
						// On
						$state = "checked";
						$byte -= $register[ $i ];
					}
					echo checkboxHTML( "r" . ($i +1), $register[ $i ], $state, 'rel_'. ($i +1), null, "relEvent( '#' + this.id )" );
				}
				
				echo '</div>';
				echo "</div>\n";
				
				// special
				
				echo '<div class="form-group row">';
				echo '<div class="col-sm-2"><label>Special</label></div>';
				echo '<div class="col-sm-10">';
				echo checkboxHTML( "all", '255', null, 'rel_all', 'rel_all', "relEvent( '#' + this.id )" );
				echo checkboxHTML( "none", '0', null, 'rel_none', 'rel_none', "relEvent( '#' + this.id )" );
				echo '</div>';
				echo '</div>'."\n";
				
			?>
		</form>
	</div>
	
	<div style="margin-top: 10px;"></div>
	
	<div id="msg"></div>
	
<script type="text/javascript">
function relEvent( id ) {
	var state = $( id )[0].checked;
	var value = $( id ).val();
	var curr = parseInt( $('#current').val() );
	var erg = 0;
	if( state ) {
		// add relais 
		erg = curr + parseInt( value );
	} else {
		// remove ralais
		erg = curr - parseInt( value );
	}
	// special (none, all)
	if( id == '#rel_none' ) erg = 0;
	if( id == '#rel_all' ) erg = 255;
	
	$.get( "inc/module/relais.php?cid=" + cid + "&cmd=set&relais=" + erg, function( data ) {
		var jsonobj = eval("(" + data + ")");
		var resp = jsonobj[0][ 'resp' ];
//		console.log( erg );
		if( resp ) {
			// set ok
			// update hidden value (reminder)
			$('#current').val( erg );
			// special ( none, all )
			if( id == '#rel_none' || id == '#rel_all' ) {
				$( id ).attr('checked', false); // unset self
				// update all rel checkboxes
				if( id == '#rel_none' ) {
					$('input:checkbox').each(function( i,input ){
						if( i <= 7 ) $( this ).attr('checked', false );
					});
				}
				if( id == '#rel_all' ) {
					$('input:checkbox').each(function( i,input ){
						if( i <= 7 ) $( this ).prop( 'checked', true );
					});
				}
			}
		} else {
			// set fail
			htmlMsg( 'msg', 'danger', 'Fail!', 'Register dont write!' );
		}
	});
}
</script>	
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
