<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
<script type="text/javascript">
function delsms( file, folder, el ) {
	var element = $( el ).parent().parent().attr('id');
	var params = 'file=' + file + '&folder=' + folder; 
	getAjax( 'inc/module/rmsms.php', params ,function( data ) {
		var jsonobj = eval("(" + data + ")");
		var data = jsonobj[0][ 'data' ];
		if( data ) {
			$( '#' + element ).slideUp().remove();
//			fadeOutEl( '#' + element );
		}
	});
}

$(document).ready(function() {
  textareaCounter( '#msg', <?= SMSMAX ?> );
});


</script>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<div>
		
		<?php // get smsd status
			$gammustate = colorize( 'gammu-smsd', 'small', '#C00', 'Gammu SMSD not running!' );
			if( isRunning( 'gammu-smsd' ) ) $gammustate = colorize( 'gammu-smsd', 'small' );
		?>
		<h3>SMS <?= $gammustate ?></h3>
		
		<div id="sms_wrap">
		<?php
			
			// write sms form
			echo '<div class="panel panel-default">';
			echo '<div class="panel-heading clickable" onclick="toggler( \'.panel-write\' )"><strong>Schreiben</strong></div>'."\n";
			
			if( isset( $_REQUEST['submit'] ) ) {
				// validation
				$valid = true;
				
				// Onload opened form, to display Messages
				echo '<div class="panel-body panel-write">';

				// if isset Message, get message
				if( isset( $_REQUEST['msg'] ) && ( $_REQUEST['msg'] != null || $_REQUEST['msg'] != "" ) ) {
					$msg = $_REQUEST['msg'];
				}	else {
					echo htmlMsg( "Empty", "No Message given!", "warning", 2500 );
					$valid = false;
				}
				
				if( $valid ) { // message failed
					if( isset( $_REQUEST['phone'] ) && ( $_REQUEST['phone'] != null || $_REQUEST['phone'] != "" ) ) {
						$phone = $_REQUEST['phone'];
						if( sendSMS( $phone, $msg ) ) {
							echo htmlMsg( "Send", "SMS to ".$phone.".", "success" );
						} else {
							echo htmlMsg( "Fail", "Send SMS failed!", "danger" );
						}
					} else {
						echo htmlMsg( "Fail", "Wrong or empty number", "warning" );
						$valid = false;
					}
				}
				
			} else {
				// onload hidden form
				echo '<div class="panel-body panel-write" style="display:none">';
			}
			
			echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">';
			echo '<div class="form-group row smsField">' . "\n" . inputHTML( 'phone', 'Phone number', 'tel', isset( $_REQUEST['phone'] ) ? $_REQUEST['phone'] : '', 'Phone' ) . "</div>\n";
			echo '<div class="form-group row">' . "\n" . textareaHTML( 'msg', 'Message', ( isset( $_REQUEST['msg'] ) ? $_REQUEST['msg'] : '' ), 'Message' );
			echo "</div>\n";
			echo '<div class="form-group row">
		    <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" name="submit" class="btn btn-secondary">Absenden</button>
				</div>
			</div>';
			echo '</form>';
			echo '</div>';
			echo "</div>\n";
			
			
			$path = "/var/spool/gammu/";
			$id = 0;
			
			$verzeichnis_glob = glob( $path . "*" );
			foreach( $verzeichnis_glob as $subdir ) {
				
				$folder = str_replace( $path, '', $subdir );
				$subdirs = glob( $subdir . "/" . "*.{smsbackup,txt}", GLOB_BRACE );
				$amount = count( $subdirs );
				
				// hide empty folders
				if( $amount <= 0 ) continue;
				
				switch ( $folder ) {
					case 'error':
						$paneltext = 'Alle Nachrichten, die nicht versendet werden konnten.';
						$panelclass = 'danger';
					break;
					case 'inbox':
						$paneltext = 'Eingegangene Nachrichten.';
						$panelclass = 'info';
					break;
					case 'outbox':
						$paneltext = 'Nachrichten Warteschlange, noch nicht versendet.';
						$panelclass = 'warning';
					break;
					case 'sent':
						$paneltext = 'Gesendete Nachrichten.';
						$panelclass = 'success';
					break;
					default:
						$paneltext = '';
						$panelclass;
					break;
				}
				
				echo '<div class="panel panel-default ' . ( isset( $panelclass ) && $amount > 0 ? 'panel-' . $panelclass : '' ) . '">'."\n";
				
				// panel heading
				if( $amount > 0 ) {
					echo '<div class="panel-heading clickable" onclick="toggler( \'.panel-' . $folder . '\' )">';
					echo '<strong>' . $folder . '</strong>';
					echo '<span class="label label-default pull-right">' . $amount . ' Element' . ( $amount > 1 ? 'e' : '' ) . '</span>';
					echo "</div>\n";
				} else {
					echo '<div class="panel-heading"><strong>' . $folder . "</strong></div>\n";
				}
				
				// panel body
				echo '<div class="panel-body panel-' . $folder . '" style="display:none">' . $paneltext . "</div>\n";
				
				// panel list
				echo '<ul class="list-group panel-' . $folder . '" style="display:none">';
				foreach ( $subdirs as $key => $file ) {
					
					$filename = str_replace( $subdir . "/", '', $file );
						
					$namearr = explode( '_', $filename );
					$contentraw = file_get_contents( $file );
					
					if( $folder == 'inbox' ) {
						$replace_str = 'IN';
						$content = $contentraw;
					} else {
						$replace_str = 'OUTC';
						$contentraw = substr( $contentraw, 183, -1 );
						$content = explode( 'PDU', $contentraw )[0];
						$content = str_replace( ';','<br />', $content );
					}

					$dateraw = isset( $namearr[0] ) ? str_replace( $replace_str,'',$namearr[0] ) : "";
					$time = isset( $namearr[1] ) ? $namearr[1] : '';
//					$date = gmdate("d.m.Y H:i:s", strtotime( $dateraw . " " .  $time ) );
					$date = date( DATEFORM, filemtime( $file ) );
					$phone = isset( $namearr[3] ) ? $namearr[3] : "";

					// list item
					echo '<li id="item_' . $id . '" class="list-group-item sms-item">';
					echo $phone;
					echo '<div class="pull-right">';
					echo '<span class="controlls"><a class="clickable" onclick="delsms( \'' . urlencode( $filename ) . '\', \'' . $folder . '\', this )"><img src="" alt="löschen" /></a></span>';
					echo ' ' . $date . '</div><br />';
					echo inlinelinks( $content );
					echo '</li>';

					$id++;
				}
				echo '</ul>';
				echo '</div>'; 		// close panel
			}
		?>
		</div>		
				
	</div>
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->



</body>
</html>
