<!DOCTYPE html>
<?php 
$preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; 
//incl('inc/init.php');
?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
<script type="text/javascript">
//$(document).ready(function() {
  var mailmax = <?= MAILMAX ?>;
  var climax = <?= CLIMAX ?>; 
  var lcdmax = <?= LCDMAX ?>;
  var smsmax = <?= SMSMAX ?>;
//});
</script>
</head>

<body>

<div class="container">

	<?php incl('inc/header.php'); ?>
	
	<div>
	
		<h3>Send Message<small> (LCD, Mail, SMS) from RaspberryPi</small></h3>
		
		<?php 
			
			if( isset( $_REQUEST['submit'] ) ) {
				
				$valid = true;
				
				// if isset Message, get message
				if( isset( $_REQUEST['msg'] ) && ( $_REQUEST['msg'] != null || $_REQUEST['msg'] != "" ) ) {
					$msg = $_REQUEST['msg'];
				}	else {
					echo htmlMsg( "Empty", "No Message given!", "info" );
					$valid = false;
				}

				// if isset email, get mail address and send message
				if( isset( $_REQUEST['MAIL'] ) ) {
					if( $valid ) { // message failed
						if( isset( $_REQUEST['email'] ) && ( $_REQUEST['email'] != null || $_REQUEST['email'] != "" ) ) {
							$email = $_REQUEST['email'];
							$subject = isset( $_REQUEST['subject'] ) ? $_REQUEST['subject'] : '';
//							echo "send mail to " . $email;
							if( sendMAIL( $email, $msg, $subject ) ) {
								echo htmlMsg( "Send", "E-Mail to ".$email.".", "success" );
							} else {
								echo htmlMsg( "Fail", "Send E-Mail failed!", "danger" );
							}
						} else {
							$valid = false;
						}
					}
				} 
				
				// if isset phone, get phone number and send message
				if( isset( $_REQUEST['SMS'] ) ) {
					if( $valid ) { // message failed
						if( isset( $_REQUEST['phone'] ) && ( $_REQUEST['phone'] != null || $_REQUEST['phone'] != "" ) ) {
							$phone = $_REQUEST['phone'];
//							echo "send sms to " . $phone;
							if( sendSMS( $phone, $msg ) ) {
								echo htmlMsg( "Send", "SMS to ".$phone.".", "success" );
							} else {
								echo htmlMsg( "Fail", "Send SMS failed!", "danger" );
							}
						} else {
							$valid = false;
						}
					}
				} 

				// if isset CLI, save message for Client requests
				if( isset( $_REQUEST['CLI'] ) ) {
					if( $valid ) { // message failed
						if( isset( $_REQUEST['clitype'] ) && ( $_REQUEST['clitype'] != null || $_REQUEST['clitype'] != "" ) ) {
							$type = $_REQUEST['clitype'];
							$title = isset( $_REQUEST['title'] ) ? $_REQUEST['title'] : ucfirst( $type );
							$date = isset( $_REQUEST['date'] ) ? $_REQUEST['date'] : date( DATEFORM );
							if( saveCliMsg( $msg, $title, $type, $date ) ) {
								echo htmlMsg( "Save", "Message for Client requests.", "success" );
							} else {
								echo htmlMsg( "Fail", "Save Message for Client requests failed!", "danger" );
							}
							
						} else {
							$valid = false;
						}
					}
				}
				
				// if isset LCD, send message to LCD
				if( isset( $_REQUEST['LCD'] ) ) {
					if( $valid ) { // message failed
//						echo "send msg to LCD";
						if( sendLCD( $msg ) ) {
							echo htmlMsg( "Send", "Message to LCD", "success" );
						} else {
							echo htmlMsg( "Fail", "send to LCD failed!", "danger" );
						}
					}
				}
				
				// Set LCD as default if nothing is selected
				if( ! isset($_REQUEST['LCD']) && ! isset( $_REQUEST['MAIL'] ) && ! isset( $_REQUEST['SMS'] ) && ! isset( $_REQUEST['CLI'] ) ) $_REQUEST['LCD'] = true;
				
			} else {
				// Initial checkbox states
				$_REQUEST['LCD'] 	= true;
				$_REQUEST['MAIL'] = null;
				$_REQUEST['SMS'] 	= null;
				$_REQUEST['CLI'] 	= null;
				
			}

//			$maximum = 0;
//			if( isset( $_REQUEST['MAIL'] ) && $_REQUEST['MAIL'] !== null ) $maximum = MAILMAX;
//			if( isset( $_REQUEST['CLI'] ) && $_REQUEST['CLI'] !== null ) $maximum = CLIMAX;
//			if( isset( $_REQUEST['SMS'] ) && $_REQUEST['SMS'] !== null ) $maximum = SMSMAX;
//			if( isset( $_REQUEST['LCD'] ) && $_REQUEST['LCD'] !== null ) $maximum = LCDMAX;
			
		?>
	
		<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
			<?php
				
				// Checkboxes
				echo '<div class="form-group row">';
				echo '<div class="col-sm-2"><label>Ziel</label></div>';
				echo '<div id="selectors" class="col-sm-10">'."\n";
				echo checkboxHTML( 'LCD', 'lcd', isset( $_REQUEST['LCD'] ) ? true : null, "Send a Message to the LCD Display", 'lcd', 'boxEvent( this.id )' );
				echo checkboxHTML( 'MAIL', 'mail', isset( $_REQUEST['MAIL'] ) ? true : null, "Send a Message to an E-Mail Recipient", 'mail', 'boxEvent( this.id )' );
				echo checkboxHTML( 'SMS', 'sms', isset( $_REQUEST['SMS'] ) ? true : null, "Send a Message to a Phone via SMS", 'sms', 'boxEvent( this.id )' );
				echo checkboxHTML( 'CLI', 'clients', isset( $_REQUEST['CLI'] ) ? true : null, "Save Message for client requests", 'clients', 'boxEvent( this.id )' );
				echo '</div>';
				echo "</div>\n";
				
				// Clients
				echo '<div class="form-group row clientsField">' . "\n" . inputHTML( 'title', 'Clients Title', 'title', isset( $_REQUEST['title'] ) ? $_REQUEST['title'] : '', 'Title' ) . "</div>\n";
				echo '<div class="form-group row clientsField">' . "\n" . selectHTML( 'clitype', array( 'info', 'success', 'warning', 'danger' ), 'Type', 'Bitte einen Typ auswählen.' ) . "</div>\n";
				echo '<div class="form-group row clientsField">' . "\n" . dateHTML( 'date', 'Date', isset( $_REQUEST['date'] ) ? $_REQUEST['date'] : date( DATEFORM ) ) . "</div>\n";
				// Email
				echo '<div class="form-group row emailField">' . "\n" . inputHTML( 'email', 'E-Mail Address', 'email', isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : '', 'E-Mail' ) . "</div>\n";
				echo '<div class="form-group row emailField">' . "\n" . inputHTML( 'subject', 'E-Mail Subject', null, isset( $_REQUEST['subject'] ) ? $_REQUEST['subject'] : '', 'Subject' ) . "</div>\n";
				// SMS
				echo '<div class="form-group row smsField">' . "\n" . inputHTML( 'phone', 'Phone number', 'tel', isset( $_REQUEST['phone'] ) ? $_REQUEST['phone'] : '', 'Phone' ) . "</div>\n";
				// Message
				echo '<div class="form-group row">' . "\n" . textareaHTML( 'msg', 'Message', ( isset( $_REQUEST['msg'] ) ? $_REQUEST['msg'] : '' ), 'Message' ) . "</div>\n";
				
			?>
			<div class="form-group row">
		    <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" name="submit" class="btn btn-secondary">Absenden</button>
				</div>
			</div>
		</form>
	</div>
	
	<script type="text/javascript">

$( document ).ready( function() {
	textareaCounter( '#msg', getMax( '#selectors' ) ); // initial call
	
	<?php echo isset( $_REQUEST['MAIL'] ) ? '$( ".emailField" ).show();' : '$( ".emailField" ).hide();'; ?>
	<?php echo isset( $_REQUEST['SMS'] ) ? '$( ".smsField" ).show();' : '$( ".smsField" ).hide();'; ?>
	<?php echo isset( $_REQUEST['CLI'] ) ? '$( ".clientsField" ).show();' : '$( ".clientsField" ).hide();'; ?>
});

function getMax( id ) {
	var lowest = 0;
	var tempArr = [];
	$( id + ' input:checked' ).each(function(i){
		if( $( this )[0].checked ) {
			tempArr.push( $( this ).val() );
		}
	});
	
	if ( $.inArray('mail', tempArr ) != -1 ) lowest = mailmax;
	if ( $.inArray('clients', tempArr ) != -1 ) lowest = climax;
	if ( $.inArray('sms', tempArr ) != -1 ) lowest = smsmax;
	if ( $.inArray('lcd', tempArr ) != -1 ) lowest = lcdmax;

	return lowest == 0 ? lcdmax: lowest;
}

function boxEvent( id ) {
	if( id == "lcd" ) {
		// lcd needs only the message field ;)
	}
	if( id == "mail" ) { 
		/* Show or hide mail address input */
		$( '.emailField' ).animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
	}
	if( id == "sms" ) { 
		/* Show or hide phone number input */ 
		$( '.smsField' ).animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
	}
	if( id == "clients" ) { 
		/* Show or hide phone number input */ 
		$( '.clientsField' ).animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
	}
	// get new max value and subtract already given chars
	maxlength = getMax( '#selectors' );
  text_remaining = maxlength - $( '#msg' ).val().length;
  // update remaining colors
  if( text_remaining <= 0 ) {
		$( '#msg_feedback' ).children().css({'color':'#F00'});
	} else if( text_remaining < maxlength / 100 * 15 ) {
		$( '#msg_feedback' ).children().css({'color':'#aa0'});
	} else {
		$( '#msg_feedback' ).children().css({'color':'#aaa'});
	}
  // add new values to fields
	$( '#msg_feedback' ).children().html( text_remaining );
	$( '#msg' ).attr({ 'maxlength': maxlength });
}
	</script>

	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
