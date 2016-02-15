<!DOCTYPE html>
<?php 
$preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; 
incl('inc/init.php');
?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
<style>
#lcdtext {
	width: 230px;
	height: 110px;
	resize: none;
	font-family: monospace;
	font-size: 18px;
}
</style>
</head>

<body>

<div class="container">
	
	<?php 
		incl('inc/header.php'); 
//		inc/module/lcd.php?text="{datetime} Text"
	?>
	
	<div><h3>Send 2 LCD<small> Daten an das Display senden</small></h3>
		
		<form action="inc/module/lcd.php" method="POST">
			<textarea id="lcdtext" name="text" maxlength="80"></textarea>
			<button type="button" id="submit">Absenden</button>
		</form>
	</div>
	
	<div style="margin-top: 10px;"></div>
	
	<div id="msg"></div>
	
<script type="text/javascript">
function sendAjax () {

	var text = document.getElementById("lcdtext").value; 
	if( text == "" ) { htmlMsg( 'msg', 'info', 'Empty!', 'No input!' ); return }
console.log( text );
	var url_var = "inc/module/lcd.php";
	var find = ["<", "&", "\n"];
	var replacer = ["&lt;", "&am;", "%0A"];
//	text = text.replaceArray(find, replacer);
	
	url_var += "?sid=" + sid;
//	url_var += "&text=" + encodeURIComponent((text + '').replace(/\+/g, '%20'));
	url_var += "&text=" + encodeURIComponent((text + '').replace(/\+/g, '%20'));
//	url_var += "&text=" + text;

//	console.log( url_var  );
	// Send AJAX Request 
	$.ajax({
		url: url_var,
		cache: false
	})
	
	// Parse AJAX Response
	.done(function( html ) {
		var jsonobj = eval("(" + html + ")");
			var name = "lcdtext";
			var resp = jsonobj[0][ 'resp' ];
			
			if( resp == null ) {
//			console.log( resp.toString() );
				htmlMsg( 'msg', 'warning', 'Warn!', 'Display received no input!' );
			} else 
			if( resp == false ) {		
//				var lcdtext = "<font color='#f00'>" + lcdtext +" request fail</font>";
				//		'<div class="alert alert-danger fade in" style="margin-left:15px;"><button class="close" data-dismiss="alert" aria-label="close">&times;</button><strong>Alert!</strong> Unable to open file!</div>'		
				htmlMsg( 'msg', 'danger', 'Fail!', 'Display not response!' );
			} else {
				var lcdtext = resp;
				htmlMsg( 'msg', 'success', 'Success!', 'Display responsed write.' );
			}
//			$( "#" + name ).html( lcdtext );		// Replace DATA 
	})

	.fail(function( jqXHR, textStatus ) {
		$( '#msg' ).html( "<p class='invalid'>MODUL request failed: " + textStatus + "</p>");		// Replace MODUL
	})
	
	window.onload = function () { document.getElementById("lcdtext").value = lcdtext; }; 
	
} 
document.getElementById("submit").onclick = sendAjax;
</script>	
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
