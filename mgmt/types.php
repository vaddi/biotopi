<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <h3 id="highlight">Types</h3>
      <div id="output"></div>
			<div id="content">
		    loading...
			</div>
		</div>
	</div><!-- END row-->

<script type="text/javascript">
  
let types = new Array();
types[0] = 'daemontypes';
types[1] = 'devicetypes';
types[2] = 'devicesubtypes';
types[3] = 'protocoltypes';

// let types = [
//   { name: 'daemontypes' schema: [{ id: 'INTEGER', name: 'TEXT' }], },
//   { name: 'devicetypes' schema: [{ id: 'INTEGER', name: 'TEXT' }], },
//   { name: 'devicesubtypes' schema: [{ id: 'INTEGER', name: 'TEXT' }], },
//   { name: 'protocoltypes' schema: [{ id: 'INTEGER', name: 'TEXT' }], },
// ];

let currentTypes = null;


function readTypes( typesName ) {
  let types = apiJson( typesName, 'read' ).data;
  return currentTypes = types;
}

function renderTypesData( typesName ) {
  $( '#highlight' ).html( $( '#highlight' ).text() + ': ' + typesName );
  readTypes( typesName );
  let types = currentTypes;
  let total = types.length -1;
  let content = "<div><a href='types.php'>View All Types</a> | <a href='?action=create&type=" + typesName + "'>Create Entry</a><hr />\n";
  content += '<div>';
  $( types ).each( function( key, value ) {
    $.each(value, function( index, entry ) {
      content += '<span>' + index + ' ' + entry + '</span><br />' + "\n";
    });
    content += "<a href='?action=edit&type=" + typesName + "&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&type=" + typesName + "&id=" + value.id + "' >Delete</a>";
    if( key < total ) content += "<hr /><br />\n";
  });
  content += '</div>';
  content += '</div>';
  $( '#content' ).html( content );
}

function typesForm( typesName, action, id ) {
  $( '#highlight' ).html( capitalize( action ) + ': ' + typesName );
  let types = null;
  if( action === 'edit' ) {
    readTypes( typesName );
    types = currentTypes;

  } else {
    types = [{  }];
  }
//    console.log( types );
//  let total = types.length -1;
  let content = "<div><a href='types.php?action=show&type=" + typesName + "'>View All " + typesName + "</a><hr />\n";
  content += "<form id='typesform' action='../' method='POST'>";
  content += "  <input type='hidden' name='controller' value='" + typesName + "' />";
  content += "  <input type='hidden' name='action' value='create' />";
  $( types ).each( function( key, value ) {
//    console.log( types );
    $.each(value, function( index, entry ) {
      //content += '<span>' + index + ' ' + entry + '</span><br />' + "\n";
    });
//    content += "<a href='?action=edit&type=" + typesName + "&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&type=" + typesName + "&id=" + value.id + "' >Delete</a>";
//    if( key < total ) content += "<hr /><br />\n";
  });
  content += '</form>';
  content += '</div>';
  $( '#content' ).html( content );
}

function renderTypesList() {
  let content = "<div>";
  let total = types.length -1;
  $( types ).each( function( key, value ) {
    content += '<spqn><a href="?action=show&type=' + value + '">' + value + '</a></span><br />\n';
    if( key < total ) content += "<hr /><br />\n";
  });
  content += "</div>";
  $( '#content' ).html( content );
  // return content;
}


// parse action

$(document).ready( function() {
  
  let action = GetURLParameter('action');
  let type = GetURLParameter('type');
  let id = GetURLParameter('id');
  
  if( action == undefined || action === 'read' ) {
    renderTypesList();
  } else if( action === 'show' ) {
    // read X
    renderTypesData( type );
    
  } else if( action === 'edit' ) {
    typesForm( type, action, id );
  } else if( action === 'create' ) {
    typesForm( type, action );
  } else {
    $( '#content' ).html( 'Unknown action "' + action + '"' );
  }
});
  
</script>

	<?php require_once( 'inc/tpl/footer.php' ); ?>

</div><!-- END container-->

</body>
</html>