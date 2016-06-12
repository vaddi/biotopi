<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); 
require_once( 'inc/class/class.File.php' );
$gamArr = array(	
	'sent'			=>	'sentstate.dat',
	'received'	=>	'receivedstate.dat',
	'failed'		=>	'failedstate.dat',
	'signal'		=>	'signalstate.dat'
);

// Build Javascript arrays
echo '<script type="text/javascript">';
foreach ( $gamArr as $name => $file ) {
	$tmparr = json_decode( File::read( realpath("inc/") . "/tmp/" . $file ), true );
	$data = "";
	$total = count( $tmparr ) -1;
	if( (int) $tmparr !== 0 ) {
		foreach ( $tmparr as $key => $value ) {	
			$data .= $value; 	
			if( $key < $total ) $data .= ", "; 
		}
	}

//	print_r( $tmparr );
//	print_r( $data );

	echo "\n" . 'var ' . $name . 'Arr = [ ' .  $data  . ' ];';
	
}
echo "\n" . '</script>';

?>


<script type="text/javascript">
function delsms( file, folder, el ) {
	bootbox.confirm("Are you sure?", function(result) {
		if( ! result ) {
			return false;
		} else {
			var params = '';
			if( file === null || file === undefined ) {
				params += 'cmd=delfolder&file=' + null + '&folder=' + folder;
			} else {
				params += 'cmd=delfile&file=' + file + '&folder=' + folder;
			}
			getAjax( 'inc/module/sms.php', params ,function( data ) {
				var jsonobj = eval("(" + data + ")");
				var data = jsonobj[0][ 'data' ];
				if( file === null || file === undefined ) {
					if( data ) {
						// remove panel, folder deleted
						var panel = $(el).parent().parent().attr('class').split(' ');
						fadeOutEl( '.' + panel[2] );
					}
				} else {
					if( data ) {
						// remove element, element deleted
						fadeOutEl( '#' + $( el ).parent().parent().parent().attr('id') );
					}
				}
				
				// Update Counter
				if( $( '#counter_' + folder ).length > 0 ) {
					var counter = $( '#counter_' + folder );
					var amount = parseInt( counter.text().match(/\d+/)[0] ) -1;
					if( amount > 0 ) {
						// update counter
						var string = amount + ' Element' + ( amount > 1 ? 'e' : '');
						$( counter ).text( string );
					} else {
						// no elements, remove panel
						var panel = $(counter).parent().parent().attr('class').split(' ');
						fadeOutEl( '.' + panel[2] );
					}
				}
				
			});
			return true;
		}
	});
}

$(document).ready(function() {
  textareaCounter( '#msg', <?= SMSMAX ?> );
  polling( 30000, "sms.php" ); 
});

// use own polling
function pollFunctions() {
	// Do Stuff on polling
	gammuState();
}

</script>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<div>
		
		<?php // get smsd status
//			$gammustate = colorize( 'gammu-smsd', 'small', '#C00', 'Gammu SMSD not running!' );
//			if( isRunning( 'gammu-smsd' ) ) $gammustate = colorize( 'gammu-smsd', 'small' );
			$gammustate = colorize( 'gammu-smsd', 'small id="gstate"', '#C00', 'Gammu SMSD not running!' );
      if( isRunning( 'gammu-smsd' ) ) {
      	$gammuRawData = gammuData(); // empty=all, IMEI, Sent, Received, Failed, NetworkSignal
      	if( is_array( $gammuRawData ) ) {
      		$gammudata = "";
      		foreach( $gammuRawData as $key => $value ) {
		        $gammudata .= $key . ": " . $value . "\n";
		      }
      	} else {
      		$gammudata = $gammuRawData;
      	}
        $gammustate = colorize( 'gammu-smsd', 'small id="gstate"' , null, $gammudata );
      }
		?>
		<h3>SMS <?= $gammustate ?>
		<small id="signal-spark" class="sparkline gammu-spark" data-width="100px" size="2.5" color="#090 #FF0 #F00"  style="float:right" title="Signal: <?= gammuData( 'NetworkSignal' ); ?>"></small>
		<small id="sent-spark" class="sparkline gammu-spark" data-width="100px" size="2.5" color="#F00 #D0E9C7 #3C763D" style="float:right;" title="Sent: <?= gammuData( 'Sent' ); ?>"></small>
		<small id="received-spark" class="sparkline gammu-spark" data-width="100px" size="2.5" color="#F00 #C6E4F3 #51708F" style="float:right;" title="Received: <?= gammuData( 'Received' ); ?>"></small>
		<small id="failed-spark" class="sparkline gammu-spark" data-width="100px" size="2.5" color="#F00 #EBCCCC #B34442" style="float:right;" title="Failed: <?= gammuData( 'Failed' ); ?>"></small>
		</h3>
		
		<div id="sms_wrap">
		<?php
			
			// write sms form
			echo '<div class="panel panel-default">';
			echo '<div class="panel-heading clickable" onclick="toggler( \'.panel-write\' )"><strong>';
			echo 'Schreiben';
			echo '</strong></div>'."\n";
			
			$recipients = array(
				'Maik' => '015115872477',
        'Peter' => '017635716685',
        'Martin' => '017632872865',
        'Dominik' => '016091983333',
        'Adrianna' => '01636014887',
        'Tobi' => '015757801180',
        'Erik' => '01786042964',
        'Hannes' => '015153729696',
        'Christian' => null
      );
			
			if( isset( $_REQUEST['submit'] ) ) {
				// validation
				$valid = true;
				
				// Onload opened form, to display Message sentstatus
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
			
      echo '<div class="form-group row smsField">';
      echo inputHTML( 'phone', 'Phone number', 'tel', isset( $_REQUEST['phone'] ) ? $_REQUEST['phone'] : '', 'Phone' ) . "\n";
//			$recipient = isset( $_REQUEST['phone'] ) ? getByValue( $recipients, $_REQUEST['phone'] ) : null;
// 			echo selectHTML( 'phone', $recipients, 'Empfänger', $recipient );
      echo "</div>\n";
      
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
			
			
//			// Backup 
//			$defaultName	= date( 'd.m.Y_H:i:s' ) . '_backup.zip';
//			$backupSource = array( '/var/www/inc/tmp' );
//			$backupDest		= '/var/www/inc/backups';
//			
//			// sms backup form
//			echo '<div class="panel panel-default panel-warning">';
//			echo '<div class="panel-heading clickable" onclick="toggler( \'.panel-backup\' )"><strong>';
//			echo 'Backups';
//			echo '</strong></div>'."\n";
//			
//			if( isset( $_REQUEST['submit_backup'] ) ) {
//				// validation
//				$valid = true;
//				
//				// Submit Action
//				
//				
//  			// Onload opened form, to display Backups after reload
//				echo '<div class="panel-body panel-backup">';
//				
//			} else {
//				// onload hidden form
//				echo '<div class="panel-body panel-backup" style="display:none">';
//			}
//			
//			// panel body
//			echo "<p>Erstellen und Wiederherstellen von SMS-Backups</p>\n";
//			
//			echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">';
//			
//			// Backup auflistung
//			$verzeichnis_glob = glob( $backupDest . "/*" );
//			$total = count( $verzeichnis_glob );
//			if( $total > 0 ) {
//				echo '<ul>';
//				foreach( $verzeichnis_glob as $file ) {
//					
//					echo '<li>' . $file . '</li>';
//					
//				}
//				echo '</ul>';
//			} else {
//				// No Backups
//				
//			}
//			
//			echo "<hr>";			
//			
//			echo '<div class="form-group row">
//		    <div class="col-sm-offset-10 col-sm-10">
//					<button type="submit" name="submit_backup" class="btn btn-secondary">Erstellen</button>
//				</div>
//			</div>';
//			
//			echo '</form>';
//			echo '</div>';
//			echo "</div>\n";
			
			
			// SMS Ordner auflistung
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
					echo '<span id="counter_' . $folder . '" class="label label-default pull-right">' . $amount . ' Element' . ( $amount > 1 ? 'e' : '' ) . '</span>';
					echo "</div>\n";
				} else {
					echo '<div class="panel-heading"><strong>' . $folder . "</strong></div>\n";
				}
				
				// panel body
//				echo '<div class="panel-body panel-' . $folder . '" style="display:none">' . $paneltext . "</div>\n";
				
				// panel body
        echo '<div class="panel-body panel-' . $folder . '" style="display:none">' . $paneltext;
        if( $amount > 1 ) echo '<span id="' . $folder . '_clear" class="label label-default pull-right clickable" onclick="delsms( null, \'' . $folder . '\', this )">Delete All</span>';
        echo "</div>\n";
				
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
					echo $date . ' ';
					echo '<span class="controlls"><a class="clickable" onclick="delsms( \'' . urlencode( $filename ) . '\', \'' . $folder . '\', this )"><img src="inc/img/icons/hex_delete.png" width="18" style="margin-top:-4px;" alt="löschen" /></a></span>';
					echo '</div><br />';
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
