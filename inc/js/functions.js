



function navigator( navId ) {
	
	var path = window.location.pathname;
	var pathArr = path.split("/");
	var loc = pathArr[pathArr.length - 1];
	
	if( loc == "" ) {
		path = window.location.href;
		loc = path.slice(0, -1) + "/";
	}
	
	$( navId ).find('a').each(function() {
		$(this).parent().toggleClass('active', $(this).attr('href') == loc);
	});
	
}


function ds18b20() {
	
	var device = null;
	var url_var = "inc/module/ds18b20.php"
	
	$('.ds18b20').each(function(i){
		device = $( this ).attr('id');
//		console.log( i + "#" + device );
		
		var url_append = "?sid=" + sid + "&device=" + device;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
			
			
			
			var jsonobj = eval("(" + html + ")");
			
//			for( key in jsonobj ) {
				var thisdev = jsonobj[0][ 'device' ];
				var temp = jsonobj[0][ 'temp' ];
				var tempout = "";
				
				if( temp >= 26 ) {
					tempout = "<font color='#f00'>" + temp + "&nbsp;</font>";
				} else if( temp >= 25 ) {
					tempout = "<font color='#f90'>" + temp + "&nbsp;</font>";
				} else {
					tempout = "<font color='#00f'>" + temp + "</font>";
				}
//				device = $( this ).attr('id');
//				console.log( i + " " + device + " " + tempout );
//				$( "#" + thisdev ).html( "<span style='font-size:100%;'>" + thisdev + "</span> - " + tempout + "°C" );
				$( "#" + thisdev ).html(  tempout );
//			} // END for

		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>DS18b20 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}


function bmp085() {
	
	var device = null;
	var url_var = "inc/module/bmp085.php"
	
	$('.bmp085').each(function(i){
		device = $( this ).attr('id');
//		console.log( i + "#" + device );
		var alt = device.split("_")[1];
//		console.log( dev_arr );
		
		var url_append = "?sid=" + sid + "&alt=" + alt;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
			
			
			
			var jsonobj = eval("(" + html + ")");
			
//			for( key in jsonobj ) {
				var alt = jsonobj[0][ 'alt' ];
				var pa = jsonobj[0][ 'pa' ] / 100;
				var paout = "";
				
				if( pa >= "1013.25" ) {
					paout = "<font color='#f00'>" + pa + "&nbsp;</font>";
				} else if( pa >= "1009.0" ) {
					paout = "<font color='#00f'>" + pa + "</font>";
				} else {
					paout = "<font color='#0f0'>" + pa + "</font>";
				}
//				device = $( this ).attr('id');
//				console.log( i + " " + device + " " + tempout );
//				$( "#" + thisdev ).html( "<span style='font-size:100%;'>" + thisdev + "</span> - " + tempout + "°C" );
				$( "#" + device ).html( paout );
//			} // END for

		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>BMP085 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}



function hcsr04() {
	
	var device = null;
	var url_var = "inc/module/hc-sr04.php"
	
	$('.hc-sr04').each(function(i){
		device = $( this ).attr('id');
//		console.log( i + "#" + device );
		var alt = device.split("_")[1];
//		console.log( dev_arr );
		
		var url_append = "?sid=" + sid + "&alt=" + alt;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
			
			
			
			var jsonobj = eval("(" + html + ")");
			
//			for( key in jsonobj ) {
				var name = jsonobj[0][ 'name' ];
				var dist = jsonobj[0][ 'dist' ] / 0.01;
				dist = Math.round(dist * 100) / 100;
				var distout = "";
				
				if( dist >= "1000" ) {
					distout = "<font color='#0f0'>" + dist + "&nbsp;</font>";
				} else if( dist >= "500" ) {
					distout = "<font color='#00f'>" + dist + "</font>";
				} else {
					distout = "<font color='#f00'>" + dist + "</font>";
				}
//				device = $( this ).attr('id');
//				console.log( i + " " + device + " " + tempout );
//				$( "#" + thisdev ).html( "<span style='font-size:100%;'>" + thisdev + "</span> - " + tempout + "°C" );
				$( "#" + device ).html( distout );
//			} // END for

		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>BMP085 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}


function polling( intervall ) {
	
	if( intervall == null ) intervall = 30000; 			// default intervall
	
	// Do Stuff on polling
	ds18b20();
	bmp085();
	hcsr04();
	
	// Repeat polling request 
	if( intervall != 0 ) setTimeout( function(){ polling( intervall ) }, intervall );
	
}


// window.load waits for content loaded
$( window ).load( function() {
	
	
});

// document.ready dont wait for images
$( document ).ready( function() {
	
	polling( 5000 );
	
});




