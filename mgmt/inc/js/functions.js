// biotopi functions.js //

/*
  variables
*/
let searchArr = [];

/**
 * Helper function to get the current location url from the browser
 */
function getLoc() {
	let path = window.location.pathname;
	let pathArr = path.split( "/" );
	let loc = pathArr[ pathArr.length - 1 ];
	if( loc == "" ) {
//		path = window.location.href; 
//		loc = path.slice(0, -1) + "/"; // loc = 'http://HOST/PATH/'
		loc = './';
	} else {
		loc = './' + loc;
	}
	return loc;
}

/**
 * Helper function to add css class "active" to the current active Navigation Element
 */
function navigator( navId ) {
	let loc = getLoc();
	$( navId ).find( 'a' ).each( function() {
		$( this ).parent().toggleClass( 'active', $( this ).attr( 'href' ) == loc );
	});
}


/**
 * Helper function to get the current "controller" from the url
 */
var controllerName = ( function() {
  return window.location.pathname.split( "/" ).pop().replace(/.php/g, '');
})();

/**
 * Helper function to capitalize the first character
 */
function capitalize( s ) {
  return s[ 0 ].toUpperCase() + s.slice( 1 );
}

/**
 * Helper function to remove an entry from an array by given Value
 */
function remove( arr, item ) {
	for( let i = arr.length; i--; ) {
	  if( arr[ i ] === item ) {
			arr.splice( i, 1 );
	  }
	}
}

/**
 * Helper function to Show all List Elements
 */
function showAll() {
	$( '#locations > div' ).each( function() {
		$( this ).show();
	});
}

/**
 * Helper function to get amount of open Stores
 */
function refreshOpen() {
	let open = 0;
	$( '#locations > div' ).each( function() {
		if( $( this ).is( ":visible" ) ) open++;
	});
	$( '#counter' ).text( open === 0 ? $( '#totals' ).text() : open );
}

/**
 * Helper function to refresh the search string
 */
function refreshSearch() {
	// show searchArr as String on Top of result list
	let searchstring = searchArr.length === 0 ? 'Alle' : searchArr.toString();
	$( '#searchstrings' ).html( searchstring );
}

/**
 * Helper function to Show/Hide List Elements, depend on have one or more searchterms wich are contained in searchArr
 */
function renderList() {
	let counter = 0;
	$( '#locations > div' ).each( function() {
		$( this ).hide();
		let obj = $( this );
		$( searchArr ).each( function( index, value ) {
			if( obj.attr( 'data-search' ).indexOf( value ) != -1 ) {
				obj.show();
				counter++;
			}
		});
	});
	if( counter === 0 ) showAll();
	// refresh counter
	refreshOpen();
	// refresh search string output
	refreshSearch();
}

/**
 * Helper function to deselect all checkboxes by given Value
 */
function deselectAll() {
	$( 'label > input[type=checkbox]' ).each( function() {
		$( this ).parent().hasClass( 'active' ) ? $( this ).parent().removeClass( 'active' ) : null;
		$( this ).prop( 'checked', false );
	});
	searchArr = [];
	renderList();
}

/**
 * Helper function to deactivate all checkboxes by given Value
 */
function deactivateByVal( value ) {
	$('label > input[type=checkbox]').each( function() {
		if( $( this ).val() === value ) {
			$( this ).parent().hasClass( 'active' ) ? $( this ).parent().removeClass( 'active' ) : null;
			$( this ).prop( 'checked', false );
		}
	});
}

/**
 * Helper function to activate all checkboxes by given Value
 */
function activateByVal( value ) {
	$('label > input[type=checkbox]').each( function() {
		if( $( this ).val() === value ) {
			$( this ).parent().hasClass( 'active' ) ? null : $( this ).parent().addClass( 'active' );
			$( this ).prop( 'checked', true );
		}
	});
}

/**
 * Helper function to get params from url
 */
function GetURLParameter( sParam ) {
  var sPageURL = window.location.search.substring( 1 );
  var sURLVariables = sPageURL.split( '&' );
  for( var i = 0; i < sURLVariables.length; i++ ) {
    var sParameterName = sURLVariables[ i ].split( '=' );
    if( sParameterName[ 0 ] == sParam ) {
      return sParameterName[ 1 ];
    }
  }
}

/**
 * Helper function to converting dates into the correct format 
 */
function convertDate( inputString ) {
  if( inputString == undefined ) inputString = '0000-00-00 00:00:00';
  if( inputString == '0000-00-00 00:00:00' ) return '00.00.0000, 00:00';
  let dateString = inputString
    , reggie = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/
    , [, year, month, day, hours, minutes, seconds] = reggie.exec( dateString )
    , dateResult = day + "." + month-1 + "." + year + ", " + hours + ":" + minutes; // + ":" + seconds
  return dateResult;
}

/**
 * Helper function to ajax call and get json response from the biotopi API
 */
function apiJson( controller, action, id ) {
  let url = "../?controller=" + controller + "&action=" + action;
  if( id != undefined ) { url += '&id=' + id }
  return $.parseJSON( $.ajax({
    url: url,
    type: "GET",
    async: false,
    success: function( response ) {
      // catch API Error messages
      if( typeof( response ) === 'string' ) {
        $( '#content' ).html( 'API Message: <br />' + response );
      } else if( response.state === false ) {
        console.log( response.errormsg );
        showMsg( 'danger', 'Failed', error );
      }
    },
    error: function( error ) {
      // catch Ajax call errors
      console.log( error );
      showMsg( 'danger', 'Failed', error );
    }
  }).responseText);
}

/**
 * Helper function to render a Modal into the Page
 */
function showModal( text, title ) {
	if( text === undefined || text === '' ) return;
	if( title === undefined || title === '' ) var title = 'Infotext';
	let id = 'pageModal';
	
	// Additional info
	var additional = "";
	additional += '<br /><br />';
	additional += 'Aktuelle Version: <a href="/releases" target="_blank">0.0</a><br />';
	additional += 'Fragen und Anregungen: <a href="https://github.com/vaddi/luncher/wiki" target="_blank">wiki</a><br />';
	additional += 'Probleme berichten: <a href="https://github.com/vaddi/luncher/issues" target="_blank">issues</a><br /><br />';
	additional += 'PHP <a href="http://packages.ubuntu.com/de/trusty/php5" target="_blank">0.0</a><br />';
	additional += 'Bootstrap <a href="https://github.com/twbs/bootstrap/releases/latest" target="_blank">v3.3.7</a><br />';
	additional += 'jQuery <a href="https://github.com/jquery/jquery/releases" target="_blank">3.1.1</a>';
	text = text + additional;
	
	if( $( '#' + id ).length <= 0 ) {
		let modal = '<div id="' + id + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="width: 100%;">';
		modal += '<div class="modal-dialog modal-dialog-breiter" role="document">';
		modal += '	<div class="modal-content">';
		modal += '		<div class="modal-header">';
		modal += '			<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		modal += '				<span aria-hidden="true">&times;</span>';
		modal += '			</button>';
		modal += '			<h4 class="modal-title">' + title + '</h4>';
		modal += '		</div>';
		modal += '		<div class="modal-body">';
		modal += text;
		modal += '		</div>';
		modal += '		</div>';
		modal += '	</div>';
		modal += '</div>';
		$( '#content' ).append( modal );
	} else {
		$( '#pageModal' ).find( '.modal-header h4' ).text( title );
		$( '#pageModal' ).find( '.modal-body' ).html( text );
	}
	$( '#pageModal' ).modal( 'show' );
}

/*
  Document Ready
*/
$(document).ready( function() {
	
	// onclick event to fill value into searchArr
	$( 'label > input[type=checkbox]').on( 'change', function () {
		if( $( this ).is( ':checked' ) ) {
			// ad to searchArr if not allready in there
			if( searchArr.indexOf( $( this ).val() ) === -1 ) searchArr.push( $( this ).val() );
			// mark all active wich have the same value
			activateByVal( $( this ).val() );
		} else {
			remove( searchArr, $( this ).val() );
			deactivateByVal( $( this ).val() );
		}
		renderList();
	});
	
	// add target _blank to each external link witch hasn't allready a target attribute
	$( 'a' ).each( function() {
		if( this.target === '' && this.href != undefined && this.href.indexOf( 'http' ) < 0 ) {
			$( this ).attr( 'target', '_blank' );
		}
	});	
	
	// Add initial counter value
	$( '#counter' ).text( $( '#totals' ).text() );
	
  // notification examples
  // showMsg( 'info', 'Infotitle', 'A little bit of Infotext', 3000 );
  // showMsg( 'success', 'Succestitle', 'Successtext' );
  // showMsg( 'warning', 'Warning', 'Warningtext', 4000 );
  // showMsg( 'danger', 'Danger', 'Dangertext' );
        
});

