/**
 * Bootstrap Notification Rendering
 * https://github.com/vaddi/bnr.git
 */

// // notification examples
// $(document).ready( function() {
//   showMsg( 'info', 'Infotitle', 'A little bit of Infotext', 3000 );
//   showMsg( 'success', 'Succestitle', 'Successtext' );
//   showMsg( 'warning', 'Warning', 'Warningtext', 4000 );
//   showMsg( 'danger', 'Danger', 'Dangertext' );
// });

// we can only use notifications.js if both jQuery and bootstrap.js are loaded
// check for jQuery
if( window.jQuery ) {

	// Default Message viewing time
	let msgtime = 7500; // time before msg fadeout

	// check for bootstrap.js
	if( typeof $().modal == 'function' ) {

		/**
		 * Helper function to ad styles
		 */
		// add css rule to head
		let style = $('<style>#notifications { min-width: 320px; z-index: 9999;	position: fixed; bottom:0; right:0; margin: 10px 10px 0 10px; }</style>');
		$('html > head').append(style);

		/**
		 * Helper function fade in elements
		 */
		function fadeInEl( elclass ) {
			$( elclass ).slideDown( function() { $( elclass ).css({ display: "block", height: "auto" }); });
		}

		/**
		 * Helper function fade out elements
		 */
		function fadeOutEl( elclass ) {
			$( elclass ).fadeTo( 500, 0 ).slideUp( function() { $( elclass ).css({ display: "none", height: "0" }).remove(); });
		}

		/**
		 * Helper function to create a div
		 */
		function renderContainer( el, id ) {
			if( id === undefined ) id = 'notifications';
			if( el === undefined ) el = 'div';
			if ( $( '#' + id ).length != 1 ) {
				let element = '<' + el + ' id="' + id + '"></' + el + '>'; 
				$( 'body' ).prepend( element );
			} 
		}

		/**
		 * Message caller function
		 */
		function showMsg( type, title, msg, millisec ) {
			if( title === undefined ) title = 'Info';
			if( type === undefined ) type = 'info';
			if( msg === undefined ) msg = 'notifications.js Fehler!';
      if( millisec === undefined ) millisec = msgtime;
      let id = gwid(); // create new id
			renderContainer( 'div', 'notifications' );
			let result = '<div id="' + id + '" class="notification alert alert-' + type + ' alert-dismissable fade in" title="Message title" style="">';
      result += '  <button class="close" data-dismiss="alert" aria-label="close" style="margin: -15px -17px 0 10px;">×</button>';
      result += '  <strong style="margin-right:20px;">' + title + '</strong> ' + msg;
      result += '</div>';
			$( '#' + 'notifications' ).append( result );
			if( type != 'danger' ) { // danger should not disapear
				$( '#' + id ).fadeTo( millisec , 500 ).slideDown( function() { 
          $( '#' + id ).css({ opacity: 1, display: "block", height: "auto" }).remove().slideUp( 500 ); 
        });
			} else {
        // danger is set only to visible
			  $( '#' + id ).css({ opacity: 1, display: "block", height: "auto" }); 
			}
		}

		/**
		 * Helper function to create a random id (Generate Window ID)
		 */
		function gwid() {
			function s4() {
				return Math.floor((1 + Math.random()) * 0x10000)
				  .toString(16)
				  .substring(1);
			}
			return s4() + s4();
		}

	} // end check for bootstrap.js

} // end check for jQuery.js
