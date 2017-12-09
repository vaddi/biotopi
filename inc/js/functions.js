/************** Arrays ***************/

//var temparray = [ "45", "50", "48", "50", "0", "6", "10", "0", "1", "2" ];
var temparray = [ 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9, 37.9 ];
var tmparray = [ 50, 50, 50, 50, 0, 0, 0, 50, 50, 50, 0, 0, 0, 25, 25, 25, 0, 0, 0, 0 ];
var avg1array = [ 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01 ];
var avg5array = [ 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01 ];
var avg15array = [ 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01, 0.01 ];
var memparray = [ 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75, 75 ];
var netinarray = [ 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4, 88.4 ];
var netoutarray = [ 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2, 73.2 ];
var fileparray = [ 40, 40, 41, 41, 41, 41, 42, 42, 40, 40, 40, 41, 42, 43, 43, 43, 43, 43 ]
var dhttemparray = [ 25.0, 25.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0, 25.0, 25.0, 24.0, 24.0 ];
var dhthumarray = [ 37.0, 37.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0, 37.0, 36.0,35.0, 35.0, 36.0, 36.0 ];
var runtimearray = [ "0.5", "0.8", "0.9", "0.99", "1", "0.9", "0.8", "0.9", "0.8", "0.9" ];
//var uSvarray = [ 0.13, 0.9, 0.15, 0.13, 0.10, 0.11, 0.10, 0.10, 0.10, 0.9, 0.8, 0.14, 0.13, 0.13, 0.13, 0.11, 0.9, 0.10, 0.10, 0.11, 0.13, 0.13, 0.13, 0.10 ];
var intervall = 30000;
var microintervall = 10000;

/************** Array functions ***************/

function shifter( array, value, maximum ) {
	if( maximum == null || maximum == "" ) maximum = array.length;
	for(var i = 0; i < array.length; i++) { 
		if( ( i + 1 ) < array.length ) {
			array[i] = array[i + 1];
		} else {
			array[i] = value;
		}
	}
	return array;
}

function arr2str( array ) {
	var str = "";
	for(var i = 0; i < array.length; i++) { 
		str += array[i] + " ";
	}
	return str;
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

String.prototype.replaceArray = function(find, replace) {
  var replaceString = this;
  for (var i = 0; i < find.length; i++) {
    replaceString = replaceString.replace(find[i], replace[i]);
  }
  return replaceString;
};

/************** Navigavion ***************/

function getLoc() {
	var path = window.location.pathname;
	var pathArr = path.split("/");
	var loc = pathArr[pathArr.length - 1];
	if( loc == "" ) {
		path = window.location.href;
		loc = path.slice(0, -1) + "/";
	}
	return loc;
}

function navigator( navId ) {
	var loc = getLoc();
	$( navId ).find('a').each(function() {
		$(this).parent().toggleClass('active', $(this).attr('href') == loc);
	});
}

/************** Time functions ***************/

function addZero( value ) {
	value = value < 10 ? "0" + value : value;
	return value;
}

function now() {
	var d = new Date();
	var day = addZero( d.getDate() );
	var month = addZero( d.getMonth() );
	var year = d.getFullYear();
	var hour = addZero( d.getHours() );
	var minute = addZero( d.getMinutes() );
	return day + "." + month + "." + year + " " + hour + ":" + minute;
}











// Spinner on all ajax requests
var spinnerid = '#spinner';
$(document).ajaxStart(function() { $(spinnerid).show(); });
$(document).ajaxStop(function() { $(spinnerid).hide(); });

/* simple getAjax function 
 * @param $url			request url
 * @param $param		parameter (dont use ?)
 * @param callback	function on success
 * 
 * Usage:
 * getAjax( 'http://google.com/', 'q=wtf&foo=bah', function( data ) {
 * 	// do stuf with data
 * });
 */
function getAjax( url, param, callback ) {
	var form_data = param;
	var method = "POST";
//	if( method == "GET" ) url += "?" + param;
	
	$.ajax({
		data: form_data,
		url: url,
		method: method,	
		cache: false,
		async: true,
		beforeSend : function() {
			// before send
		},
		success : function(data){
      callback(data);
    },
    error: function (request, status, error) {
      // on error
    },
    complete: function() {
    	// on complete
    }
	})
}

function ds18b20() {
	var device = null;
	var url_var = "inc/module/ds18b20.php"
	$('.ds18b20').each(function(i){
		device = $( this ).attr('id');
		var url_append = "?cid=" + cid + "&device=" + device;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
		
			var jsonobj = eval("(" + html + ")");

				var thisdev = jsonobj[0][ 'device' ];
				var temp = jsonobj[0][ 'temp' ];
				var tempout = "";
//				console.log( thisdev + " " + temp );
				if( temp >= 26 ) {
					tempout = "<font color='#f00'>" + temp + "&nbsp;</font>";
				} else if( temp >= 25 ) {
					tempout = "<font color='#f90'>" + temp + "&nbsp;</font>";
				} else {
					tempout = "<font color='#00f'>" + temp + "</font>";
				}

				$( "#" + thisdev ).html(  tempout );
				
		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>DS18b20 request failed: " + textStatus + "</p>");
			console.log( "<p class='invalid'>DS18b20 request failed: " + textStatus + "</p>" );
		})
		
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
		
		var url_append = "?cid=" + cid + "&alt=" + alt;
		
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
				var pa = jsonobj[0][ 'pa' ];
				var paout = "";
				
				if( pa >= "1013.25" ) {
					paout = "<font color='#f00'>" + pa + "&nbsp;</font>";
				} else if( pa >= "1009.0" ) {
					paout = "<font color='#00f'>" + pa + "</font>";
				} else {
					paout = "<font color='#0f0'>" + pa + "</font>";
				}
				
				// bmpArr = shifter( bmpArr, pa );
				$( "#" + "bmp-spark" ).attr('data-ymax', Math.max.apply(Math, bmpArr) + 1.0 ).attr('data-ymin', Math.min.apply(Math, bmpArr) - 1.0 ).html( arr2str( bmpArr ) );
//				device = $( this ).attr('id');
//				console.log( i + " " + device + " " + tempout );
//				$( "#" + thisdev ).html( "<span style='font-size:100%;'>" + thisdev + "</span> - " + tempout + "°C" );
				sparkline('bmp-spark');
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
		
		var url_append = "?cid=" + cid + "&alt=" + alt;
		
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
			$( '#msg' ).html( "<p class='invalid'>HC-SR04 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}



function dht11() {
	var device = null;
	var url_var = "inc/module/dht11.php"
	$('.dht11').each(function(i){
		device = $( this ).attr('id');
		var url_append = "?cid=" + cid;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
			var jsonobj = eval("(" + html + ")");
			
				var rf =  jsonobj[0][ 'rf' ] ;
				var temp =  jsonobj[0][ 'temp' ] ;

				if( temp != null && temp != NaN ) {
					dhttemparray = shifter( dhttemparray, temp );
					dhthumarray = shifter( dhthumarray, rf );
					
					var distout = "";
//					if( rf >= 60.0 ) {
//						distout = "<font color='#00c'>" + rf + "%</font> <br /> ";
//					} else if( rf >= 20.0 ) {
//						distout = "<font color='#09f'>" + rf + "%</font> <br /> ";
//					} else {
//						distout = "<font color='#c00'>" + rf + "%</font> <br /> ";
//					}
//					
//					if( temp >= 40.0 ) {
//						distout += "<font color='#090'>" + temp + "°C</font>";
//					} else if( temp >= 10.0 ) { 
//						distout += "<font color='#f90'>" + temp + "°C</font>";
//					} else {
//						distout += "<font color='#f00'>" + temp + "°C</font>";
//					}
					distout = rf + "%<br />" + temp + "°C"
				} else {
					distout = "<font color='#f00'>DHT11 request fail</font>";
					$( "#dhttemp-spark" ).html( arr2str( dhttemparray ) );
					$( "#dhthum-spark" ).html( arr2str( dhthumarray ) );
				}
				
				$( "#" + device ).html( distout );
				
		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>DHT11 request failed: " + textStatus + "</p>");
		})
		
		.always(function() {
//			console.log( this );
//			$( "#" + device ).html( tempout + "°C" );
		});
		
//		console.log( "#" + device );
	});
	
}



function system() {
	
	var device = null;
	var url_var = "inc/module/system.php";
	
		var url_append = "?cid=" + cid;
		
		// Send AJAX Request 
		$.ajax({
			url: url_var + url_append,
			cache: false
		})
		
		// Parse AJAX Response
		.done(function( html ) {
			
			var jsonobj = eval("(" + html + ")");
			var keys = Object.keys(jsonobj[0]);
			var memt = 0;
			
			for( key in keys ) {
				
				name = keys[ key ];
				value = jsonobj[ 0 ][ keys[ key ] ];
				
				// progressbar filesystem usage
				if( name == "filep" ) {
					$( "#" + name + "bar" ).css( "width", value  );
					var valueraw = value.slice(0,-1);
					if( valueraw > 95 ) {
						$( "#" + name + "bar" ).addClass( 'progress-bar-danger' );
					} else if( valueraw > 84 ) {
						$( "#" + name + "bar" ).addClass( 'progress-bar-warning' );
					} else {
						$( "#" + name + "bar" ).addClass( 'progress-bar-success' );
					}
					$( "#" + name + "bar").html( value ); 
				} 
				
				// progressbar memory usage
				if( name == "memp" ) {
					var valueraw = value.slice(0,-1);
					$( "#" + name + "bar" ).css( "width", valueraw + "%"  );
					if( valueraw > 95 ) {
						$( "#" + name + "bar" ).addClass( 'progress-bar-danger' );
					} else if( valueraw > 84 ) {
						$( "#" + name + "bar" ).addClass( 'progress-bar-warning' );
					} else {
						$( "#" + name + "bar" ).addClass( 'progress-bar-success' );
					}
					$( "#" + name + "bar" ).html( value ); 
				} 
				
				$( "#" + name ).html( value );
				
				// Sparkline data
				
				$( "#dhthum-spark" ).attr('data-ymax', Math.max.apply(Math, dhthumarray) +1.0 ).attr('data-ymin', Math.min.apply(Math, dhthumarray) -1.0 ).html( arr2str( dhthumarray ) );				
				$( "#dhttemp-spark" ).attr('data-ymax', Math.max.apply(Math, dhttemparray) +1.0 ).attr('data-ymin', Math.min.apply(Math, dhttemparray) -1.0 ).html( arr2str( dhttemparray ) );
				$( ".tableHead" ).html( "last " + temparray.length * ( microintervall / 1000 ) + " Sek." );
				
				if( name == "temp" ) {
					temparray = shifter( temparray, value.slice(0,-2) );
//					$( "#" + name ).parent().children().eq(1).html( text );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, temparray) +4.0 ).attr('data-ymin', Math.min.apply(Math, temparray) -2.0 ).html( arr2str( temparray ) );
//					intvalue = Math.round( temparray[ temparray.length  - 1] );
				}
				
				if( name == "avg1" ) {
					avg1array = shifter( avg1array, value );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, avg1array) +4.0 ).attr('data-ymin', Math.min.apply(Math, avg1array) -2.0 ).html( arr2str( avg1array ) );
				}
				
				if( name == "avg5" ) {
					avg5array = shifter( avg5array, value );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, avg5array) +4.0 ).attr('data-ymin', Math.min.apply(Math, avg5array) -2.0 ).html( arr2str( avg5array ) );
				}
				
				if( name == "avg15" ) {
					avg15array = shifter( avg15array, value );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, avg15array) +4.0 ).attr('data-ymin', Math.min.apply(Math, avg15array) -2.0 ).html( arr2str( avg15array ) );
				}
				
				if( name == "memp" ) {
					memparray = shifter( memparray, value.slice(0,-1) );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, memparray) +4 ).attr('data-ymin', Math.min.apply(Math, memparray) -1 ).html( arr2str( memparray ) );
				}
				
				if( name == "filep" ) {
					fileparray = shifter( fileparray, value.slice(0,-1) );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, fileparray) +4 ).attr('data-ymin', Math.min.apply(Math, fileparray) -1 ).html( arr2str( fileparray ) );
				}
				
				if( name == "netin" ) {
					netinarray = shifter( netinarray, value.slice(0,-4) );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, netinarray) +2.0 ).attr('data-ymin', Math.min.apply(Math, netinarray) -2.0 ).html( arr2str( netinarray ) );
				}
				
				if( name == "netout" ) {
					netoutarray = shifter( netoutarray, value.slice(0,-4) );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, netoutarray) +2.0 ).attr('data-ymin', Math.min.apply(Math, netoutarray) -2.0 ).html( arr2str( netoutarray ) );
				}
				
				if( name == "runtime" ) {
					runtimearray = shifter( runtimearray, value.slice( 0, -5 ) );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, runtimearray) +4.0 ).attr('data-ymin', Math.min.apply(Math, runtimearray) -1.0 ).html( arr2str( runtimearray ) );
				}
				
				if( name == "uSv" ) {
					uSvarray = shifter( uSvarray, value, 24 );
					$( "#" + name + "-spark" ).attr('data-ymax', Math.max.apply(Math, uSvarray) -0.5 ).attr('data-ymin', Math.min.apply(Math, uSvarray) -2.0 ).html( arr2str( uSvarray ) );
				}
				
				if( name == "sparkle" ) {
					tmparray = shifter( tmparray, value );
					$( "#" + name ).attr('data-ymax', Math.max.apply(Math, tmparray) +4 ).attr('data-ymin', Math.min.apply(Math, tmparray) ).html( arr2str( tmparray ) );
				}
				
			}			
		sparkline( 'sparkline' );
		})
	
		.fail(function( jqXHR, textStatus ) {
			$( '#msg' ).html( "<p class='invalid'>System request failed: " + textStatus + "</p>");
		});
		
}

function gammuState() {
	var device = null;
	var rawdevice = null;
	var url_var = "inc/module/sms.php"
	
	var sparks = [];
	$('.gammu-spark').each(function(i){
		sparks.push( $( this ).attr('id').split('-')[0] );
	});
	
	var url_append = "?cid=" + cid;
	url_append = url_append + '&cmd=sentstate'
	// Send AJAX Request 
	$.ajax({
		url: url_var + url_append,
		cache: false
	})
	
	// Parse AJAX Response
	.done(function( html ) {
		var jsonobj = eval("(" + html + ")");
		var keys = jsonobj[0].data;
		
		var gammustate = $( '#gstate' ).attr('style').split( ': ' )[1].slice(1,-1);
		
		if( jsonobj[0].state ) { // only if gammu is running
		
			for( key in keys ) {
				name = key;
				
				values = keys[ key ];
				
				if( name == "failed" ) {
					current = values[ values.length -1 ];
//					failedArr = shifter( failedArr, current );
					failedArr = values;
					title = $( "#" + name + "-spark" ).attr( 'title' ).split( ': ' )[0] + ": " + current;
					$( "#" + name + "-spark" ).attr( 'data-ymax', Math.max.apply( Math, failedArr ) +1 ).attr( 'data-ymin', Math.min.apply( Math, failedArr ) -1 ).attr( 'title', title ).html( arr2str( failedArr ) );
				}
				
				if( name == "received" ) {
					current = values[ values.length -1 ];
//					receivedArr = shifter( receivedArr, current );
					receivedArr = values;
					title = $( "#" + name + "-spark" ).attr( 'title' ).split( ': ' )[0] + ": " + current;
					$( "#" + name + "-spark" ).attr( 'data-ymax', Math.max.apply( Math, receivedArr ) +1 ).attr( 'data-ymin', Math.min.apply( Math, receivedArr ) -1 ).attr( 'title', title ).html( arr2str( receivedArr ) );
				}
				
				if( name == "sent" ) {
					current = values[ values.length -1 ];
//					sentArr = shifter( sentArr, current );
					sentArr = values;
					title = $( "#" + name + "-spark" ).attr( 'title' ).split( ': ' )[0] + ": " + current;
					$( "#" + name + "-spark" ).attr( 'data-ymax', Math.max.apply( Math, sentArr ) +1 ).attr( 'data-ymin', Math.min.apply( Math, sentArr ) -1 ).attr( 'title', title ).html( arr2str( sentArr ) );
				}
				
				if( name == "signal" ) {
					current = values[ values.length -1 ];
//					signalArr = shifter( signalArr, current );
					signalArr = values;
					title = $( "#" + name + "-spark" ).attr( 'title' ).split( ': ' )[0] + ": " + current;
					$( "#" + name + "-spark" ).attr( 'data-ymax', Math.max.apply( Math, signalArr )  ).attr( 'data-ymin', Math.min.apply( Math, signalArr )  ).attr( 'title', title ).html( arr2str( signalArr ) );
				}
				
			}
			
			sparkline( 'sparkline' ); // all data setted, render class sparkline elements
			
			if( gammustate === "C00" ) {
				// Update gammu-smsd state
				$( '#gstate' ).attr({ 'style': 'color: #666;', 'title' : 'TODO, set title data if gammu is up and running' });
			}
			
		} else {
			// gammu not running
			if( gammustate === "666" ) {
				// Update gammu-smsd state
				$( '#gstate' ).attr({ 'style': 'color: #C00;', 'title' : 'Gammu SMSD not running!' });
			}
		}
		
	})
	.fail(function( jqXHR, textStatus ) {
		$( '#msg' ).html( "<p class='invalid'>gammu request failed: " + textStatus + "</p>");
	})
	.always(function() {
		
	});
		
}

/************** polling functions ***************/

function micropollFunctions() {
	system();
}

function pollFunctions() {
	// Do Stuff on polling
	ds18b20();
	bmp085();
	hcsr04();
	dht11();
}

function micropolling( intervall, page ) {
	if( intervall == null ) intervall = 10000; 			// default intervall 1 minute
	var loc = getLoc();
	if( page != null || page != "" ) {
		if( loc == page ) micropollFunctions();
	} else {
		micropollFunctions();
	}
	if( intervall != 0 ) setTimeout( function(){ micropolling( intervall, page ) }, intervall );
}


function polling( intervall, page ) {
	if( intervall == null ) intervall = 60000; 			// default intervall 1 minute
	var loc = getLoc();
	if( page != null || page != "" ) {
		// if page isset, do only on this page
		if( loc == page ) pollFunctions();
	} else {
		// if page is unsetted, do polling on all sites
		pollFunctions();
	}
	// Repeat polling request 
	if( intervall != 0 ) setTimeout( function(){ polling( intervall, page ) }, intervall );
}






// add timeout function to classes (TODO, works only once)
window.setTimeout( function() {
	fadeOutEl( ".alert-info" );
  fadeOutEl( ".alert-success" );
  fadeOutEl( ".alert-warning" );
}, 5000);

/************** window load ***************/

// window.load waits for content loaded
$( window ).load( function() {
	$(spinnerid).hide();
});


/************** document ready ***************/

// document.ready dont wait for images
$( document ).ready( function() {
	
	// add close btn function
	$(".close").click(function(){
		fadeOutEl( ".alert" );
  });
	fadeoutAlerts();
	
//	for(var i = 0; i <= 8; i++) {
//		$("[name='r" + i + "']").attr({'data-size': 'mini', 'data-on-color': 'danger'}).bootstrapSwitch();
//	}
	polling( intervall, "stats.php" );
	micropolling( microintervall, "stats.php")
//	system();
	
});

/************** HTML outputs ***************/

function htmlMsg( id, type, name, text ) {
	if( id === null ) return;
	if( type === null ) return;
  if( name === null ) return;
  if( text === null ) return;
  var typeVal = [ 'success', 'info', 'warning', 'danger' ];
  if( inArray( type, typeVal ) ) {
		$( '#' + id ).html( '<div class="alert alert-' + type + ' fade in" style="width:320px;"><button class="close" data-dismiss="alert" aria-label="close">&times;</button><strong>' + name + '</strong> ' + text + '</div>' );
		if( type == 'success' || type == 'info' || type == 'warning' ) 
			window.setTimeout( function() { fadeOutEl( ".alert-" + type ); }, 5000);
  }
}

// fadeout and remove class or id
function fadeOutEl( elclass ) {
  $( elclass ).fadeTo( 500, 0 ).slideUp( 500, function() {
		$( elclass ).remove();
  });
}

// toggler function for sms page
function toggler( id ) {
	var ele = jQuery( id ); 
	ele.slideToggle('fast');
}

/* Textarea Counter
 * A tiny function to add a character counter under textareas. Call by CSS-Class or ID.
 * Usage: textareaCounter( '#msg', 160 ); // simple 
 * 
 * @param int			id		The id of the textbox (id or class with "#" or "." notation)
 * @param int length		The maximum length of textarea content
 * @param int 	warn		Wen reach this, the Counternumber will turn color to yellow
 */
function textareaCounter( id, length, warn ) {
	if( length === undefined ) length = 80;
	if( warn === undefined ) warn = length / 100 * 15;
	
	var idPrefix = '_feedback';
	var amountClass = 'amount';
	
	// on reload, get old data and subtract from amount
  if( $( id ).val().length > 0 ) temp_length = ( length - $( id ).val().length );
  	else temp_length = length; 
  
  // create div if not exist
  if( $( id + idPrefix ).length <= 0 ) {
  	$( id ).after( '<div id="' + id.substring(1, id.length) + idPrefix + '" class="pull-right" style="color:#aaa;"><span class="' + amountClass + '">' + temp_length + '</span> Zeichen verbleibend</div>' );
  	$( id ).attr({'maxlength':length});
  }
	
  $( id ).keyup(function() {
  	var temp_length = $( id ).attr( 'maxlength' );
    var text_length = $( id ).val().length;
    var text_remaining = temp_length - text_length;
		
		$( id + idPrefix ).html( '<span class="' + amountClass + '">' + text_remaining + '</span>' + ' Zeichen verbleibend');
		
		if( text_remaining <= 0 ) {
			$( id + idPrefix + ' .' + amountClass ).css({'color':'#F00'});
		} else if( text_remaining < warn ) {
			$( id + idPrefix + ' .' + amountClass ).css({'color':'#aa0'});
		} else {
			$( id + idPrefix + ' .' + amountClass ).css({'color':'#aaa'});
		}
  });
}


function allRel() {
	$('.relais input').each(function(i,input){
		console.log( input );
	});
}

$("form").submit(function(event) {
    // prevent submit
    event.preventDefault();
//    console.log( 'submit function' );
    var form = $(this);
    var action = form.attr("action"), 
        method = form.attr("method").toUpperCase(),
        data   = form.serialize(); 
    
    $.ajax({
        url : action,
        type : method,
        data : data
    }).done(function (data) {
        // on succsess
        console.log( data );
    }).fail(function() {
        // on error
//        alert("Fehler!");
    }).always(function() {
        // always
//        alert("Beendet!");
    });
    
});

function fadeoutAlerts() {
	var alerts = [ 'info', 'success', 'warning', 'danger' ];
	$( '.alert' ).each( function( i ){
		var timeout = $(this).data("time");
		var id = $(this).attr('id');
		if( timeout > 0 ) {
			for(var i = 0; i < alerts.length; i++) {
				// $( alerts[ i ] )
//				console.log( $( '.alert .' + id ) );
			}
//		console.log( id );
//			window.setTimeout( function() {
//				fadeOutEl( id );
//			}, timeout);
		}
	});
	
//	for(var i = 0; i < alerts.length; i++) {	
////		console.log( $( '.alert .alert-' + alerts[ i ] ) );
////		$( '.alert .alert-' + alerts[ i ] ).each( function() {
//		$( '.alert' ).each( function( i ){
//			
//			var timeout = $(this).data("time");
//			var id = $(this).attr('id');
//			if( timeout > 0 ) {
////				console.log( this );
////				window.setTimeout( function() {
////					fadeOutEl( id );
////				}, timeout);
//			}
//		});
//	}
}




