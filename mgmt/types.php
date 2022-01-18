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

// some variables
let timeout = 1000;
let types = [];
let currentTypes = null;

const structure = {
  'daemons': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Daemon Type Name' },
    'type': { type: 'dropdown', ddd: 'daemontypes', hidden: false }, // has to be a id of devicesubtype
    'device': { type: 'dropdown', ddd: 'devices', hidden: false },
    'active': { type: 'checkbox', hidden: false },
    'start': { type: 'datetime', hidden: false },
    'end': { type: 'datetime', hidden: false },
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'daemontypes': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Daemon Type Name' },
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'devices': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Device Name' },
    'js': { type: 'text', hidden: false, placeholder: 'Device JavaScript' },
    'html': { type: 'text', hidden: false, placeholder: 'Device HTML' },
    'status': { type: 'number', hidden: false },
    'type': { type: 'dropdown', ddd: 'devicetypes', hidden: false },
    'threshold': { type: 'text', hidden: false, placeholder: 'Device Threshold' },
    'protocol': { type: 'dropdown', ddd: 'protocols', hidden: false },
    'data': { type: 'text', hidden: false, placeholder: 'Device Data' },
    'function': { type: 'text', hidden: false, placeholder: 'Device Function' },
    'params': { type: 'text', hidden: false, placeholder: 'Device Function Params' },
    'pins': { type: 'text', hidden: false, placeholder: 'Device IO Pins' },
    'exec': { type: 'text', hidden: false, placeholder: 'Device Executable' },
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'devicetypes': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Device Type Name' },
    'subtype': { type: 'dropdown', ddd: 'devicesubtypes', hidden: false }, // has to be a id of devicesubtype
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'devicesubtypes': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Devied Subtype Name' },
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'protocols': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Protocol Name' },
    'type': { type: 'dropdown', ddd: 'protocoltypes', hidden: false }, // has to be a id of 
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
  'protocoltypes': {
    'id':  { type: 'number', hidden: true },
    'name': { type: 'text', hidden: false, placeholder: 'Protocol Type Name' },
    'data': { type: 'text', hidden: false, placeholder: '' },
    'created': { type: 'datetime', hidden: true },
    'updated': { type: 'datetime', hidden: true },
  },
};

// the default datastructure
function defaultStruct( searchTerm ) {
  // if we had a searchterm
  if( searchTerm !== undefined ) {
    let result = "";
    Object.keys( structure ).forEach( function( index, key ) {
      if( index == searchTerm ) {
        result = structure[ index ]; // return all types as object
      } else {
        Object.keys( structure[ index ] ).forEach( function( typename, typekey ) {
          if( typename == searchTerm ) {
            result = structure[ index ][ typename ]; // return concrete type
          }
        });
      }
    });
    return result;
  }
  return structure;
}

// redirecting the Visitor to another url after X milliseconds
function redirect( url, milliseconds ) {
  setTimeout( function() {
    window.location.href = url;
  }, milliseconds );
}

// renders dropdown boxes for forms
function renderSelector( current_id, controller_name, selector ) {
  let content = '<select class="form-control" name="' + selector + '" id="' + selector + '">';
  content += '<option value="" selected="selected" disabled="disabled">Bitte auswählen</option>';
  let elements = apiJson( controller_name, 'read' ).data;
  $( elements ).each( function( key, entry ) {
    if( entry != undefined ) {
      if( entry.id == current_id ) {
        content += '  <option value="' + entry.id + '" selected>' + entry.id + ' - ' + entry.name + '</option>';
      } else {
        content += '  <option value="' + entry.id + '">' + entry.id + ' - ' + entry.name + '</option>';
      }
    }
  });
  content += '</select>';
  return content;
}

// get controller entry names by by id
function getNameById( current_id, controller_name ) {
  let result = "";
  let elements = apiJson( controller_name, 'read', current_id ).data;
  $( elements ).each( function( key, entry ) {
    if( entry.id != undefined && entry.id == current_id ) {
      result = entry.name;
    }
  });
  return result;
}

// generates formfields for edit and create
function typesForm( typesName, action, id ) {
  let types = null;
  if( action === 'edit' ) {
    readTypes( typesName );
    types = currentTypes;
  } else { // create form needs the default scheme data
    types = defaultStruct( typesName );
  }
  let dateTypes = [];
  let content = "  <fieldset>";
  $( types ).each( function( key, value ) {
    // if we had a id
    if( id != undefined && id == value.id ) { // only for the requested id
      $.each( value, function( index, entry ) {
        let datatype = defaultStruct( index ); // get the current datatype
        if( datatype.hidden ) {
          content += "  <input id='" + index + "' type='hidden' name='" + index + "' class='form-control " + index + "' " + ( entry != undefined ? 'value="' + entry + '"' : '' ) + " />";
        } else if( datatype.type == "dropdown" ) {
          content += "  <div class='form-group'>";
          content += "    <label for='" + index + "'>" + index + ":</label>";
          content += renderSelector( entry, datatype.ddd, index );
          content += "  </div>";
        } else {
          content += "  <div class='form-group'>";
          content += "    <label for='" + index + "'>" + index + ":</label>";
          content += "    <input id='" + index + "' type='" + datatype.type + "' name='" + index + "' " + ( datatype.placeholder != undefined ? 'placeholder="' + datatype.placeholder + '"' : index ) + "' value='" + entry + "' class='" + index + " form-control'";
          if( datatype.type == "datetime" ) {
            dateTypes.push( index ); // remember all id's, to add javascript later
            content += ' data-format="yyyy-mm-dd hh:ii:ss"';
          }
          content += " />";
          content += "  </div>";
        }
      });
    } else if( action != undefined && action == 'create' ) {
      $.each( value, function( index, type ) {
        let datatype = defaultStruct( index );
        if( datatype.hidden ) {
          if( index === "id" ) {
            // do nothing, we have no id on create and dont need a field (only all other datafields)
          } else {
            content += "  <input id='" + index + "' type='hidden' name='" + index + "' class='form-control " + index + "' />";
          }
        } else if( datatype.type == "dropdown" ) {
          content += "  <div class='form-group'>";
          content += "    <label for='" + index + "'>" + index + ":</label>";
          console.log( datatype.ddd );
          content += renderSelector( index, datatype.ddd, index );
          content += "  </div>";
        } else {
          content += "  <div class='form-group'>";
          content += "    <label for='" + index + "'>" + index + ":</label>";
          content += "    <input id='" + index + "' type='" + datatype.type + "' name='" + index + "' " + ( datatype.placeholder != undefined ? 'placeholder="' + datatype.placeholder + '"' : index ) + "' class='" + index + " form-control'";
          if( datatype.type == "datetime" ) {
            dateTypes.push( index );
            content += ' data-format="yyyy-mm-dd hh:ii:ss"';
          }
          content += " />";
          content += "  </div>";
        }
      });
    }
  });
  content += "  </fieldset>";
  content += "  <fieldset>";
  content += "  <button type='submit' class='btn btn-primary mb-2'>Submit</button> <a href='?action=show&type=" + typesName + "'>Show all</a>";
  content += "  </fieldset>";
  // add datetime picker to all datetime elements
  $(document).ready( function() {
    $( dateTypes ).each( function( key, value ) {
      $('.' + value ).datetimepicker({ autoclose: true, language: 'de-DE', format: 'yyyy-mm-dd hh:ii:ss' });
    });
  });
  return content;
} 

// simple render a list of all type keys
function renderTypesList() {
  let content = "<div>";
  let types = Object.keys( defaultStruct() );
  let total = types.length -1;
  $( types ).each( function( key, value ) {
    content += '<span><a href="?action=show&type=' + value + '">' + value + '</a></span><br />\n';
    if( key < total ) content += "<hr /><br />\n";
  });
  content += "</div>";
  $( '#content' ).html( content );
}

// generate form for Create a Type
function typeCreate( type, action ) {
//  let typeData = apiJson( types, 'read', id ).data;
  $( '#highlight' ).html( capitalize( action ) + ' New <span style="color:#aaa">' + type + '</span>' );
  let content = "<div><a href='types.php?action=show&type=" + type + "'>View All " + type + "</a><hr />\n";
  content += "<form id='typesform' action='../' method='POST'>";
  content += "  <input type='hidden' name='controller' value='" + type + "' />";
  content += "  <input type='hidden' name='action' value='create' />";
  id = undefined;
  content += typesForm( type, action, id );
  content += "</form><br />";
  content += "</div><br />";
  $( '#content' ).html( content );
}

// show list of all Types
function readTypes( typesName ) {
  let types = apiJson( typesName, 'read' ).data;
  return currentTypes = types;
}

// show list of all Type Elements
function typeRead( typesName ) {
  let content = "<div><a href='types.php'>View All Types</a> | <a href='?action=create&type=" + typesName + "'>Create Entry</a><hr />\n";
  $( '#highlight' ).html( 'List of all <span style="color:#aaa;">' + typesName + '</span>' );
  readTypes( typesName );
  let types = currentTypes;
//  let types = Object.keys( defaultStruct() );
  if( types == undefined || types == null ) {
//    types = [{  }];
    content += 'No Entries.';
    $( '#content' ).html( content );
    return false;
  }
  let total = types.length -1;
  content += '<div>';
  $( types ).each( function( key, value ) {
    $.each(value, function( index, entry ) {
      let datatype = defaultStruct( index );
      if( datatype.hidden) {
        content += '<span style="color:#777">' + index + ': ' +  ( datatype.type == 'dropdown' ? entry + ' - ' + getNameById( entry, datatype.ddd ) : entry ) + '</span><br />' + "\n";
      } else {
        content += '<span>' + index + ': ' + ( datatype.type == 'dropdown' ? entry + ' - ' + getNameById( entry, datatype.ddd ) : entry ) + '</span><br />' + "\n";
      }
    });
    content += "<a href='?action=edit&type=" + typesName + "&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&type=" + typesName + "&id=" + value.id + "' >Delete</a>";
    if( key < total ) content += "<hr /><br />\n";
  });
  content += '</div>';
  content += '</div>';
  $( '#content' ).html( content );
}

// generate form for Editing a Type
function typeEdit( type, action, id ) {
//  let typeData = apiJson( types, 'read', id ).data;
  $( '#highlight' ).html( capitalize( action ) + ' ID ' + id + ' of <span style="color:#aaa">' + type + '</span>' );
  let content = "<div><a href='types.php?action=show&type=" + type + "'>View All " + type + "</a><hr />\n";
  content += "<form id='typesform' action='../' method='POST'>";
  content += "  <input type='hidden' name='controller' value='" + type + "' />";
  content += "  <input type='hidden' name='action' value='update' />";
  content += typesForm( type, action, id );
  content += "</form><br />";
  content += "</div><br />";
  $( '#content' ).html( content );
}

// deleta a type
function typeDelete( type, id ) {
  //$( '#highlight' ).html( 'Delete ID ' + id + ' of <span style="color:#aaa">' + type + '</span>' );
  let result = apiJson( type, 'delete', id );
  if( result.state ) {
    console.log( result );
    if( result.data != undefined && result.data ) {
      let msg = 'Successfull ' + GetURLParameter( 'action' ) + ' id ' + GetURLParameter( 'id' ) + ' of ' + GetURLParameter( 'type' ) + result.data;
      showMsg( 'success', GetURLParameter( 'action' ), msg );
    }
    let url = window.location.origin + window.location.pathname + '?action=show&type=' + type;
    redirect( url, timeout );
  } else {
    showMsg( 'danger', 'AJAX request failed', result.errormsg );
  }
}

// process url params
$(document).ready( function() {

  let action = GetURLParameter('action');
  let type = GetURLParameter('type');
  let id = GetURLParameter('id');

  if( action == undefined || action === 'read' ) {
    renderTypesList();
  } else if( action === 'show' ) {
    // show list of all types
    if( type == undefined  ) { // if type is none of array types
      console.log( 'No or wrong TYPE given. Unable to ' + action + ' Entry.' );
    } else {
      // show list of all Type Elements
      typeRead( type );
    }
  } else if( action === 'delete' ) {
    if( id == undefined ) {
      console.log( 'No or wrong ID given. Unable to ' + action + ' Entry.' );
    } else {
      typeDelete( type, id );
    }
  } else if( action === 'edit' ) {
    if( id == undefined ) {
      console.log( 'No or wrong ID given. Unable to ' + action + ' Entry.' );
    } else {
      typeEdit( type, action, id );
    }
  } else if( action === 'create' ) {
    typeCreate( type, action );
  } else {
    $( '#content' ).html( 'Unknown action "' + action + '"' );
  }

});

$(function() {
  // event for form submit
  $("#typesform").submit( function(e) {

    // prevent Default
    e.preventDefault();

    // get form attribute or set default value if not set
    //let actionurl = ( e.currentTarget.action !== undefined || e.currentTarget.action !== "" ) ? e.currentTarget.action : window.location; // default action
    let actionurl = ( $("#typesform").attr('action') !== undefined || $("#typesform").attr('action') !== "" ) ? $("#typesform").attr('action') : window.location + '../'; // this should point allways to the API
		let method = ( e.currentTarget.method !== undefined || e.currentTarget.method !== "" ) ? e.currentTarget.method : 'post'; // default method
		let datatype = ( e.currentTarget.datatype !== undefined || e.currentTarget.datatype !== "" ) ? e.currentTarget.datatype : 'json'; // default data type
    if( datatype === undefined ) datatype = 'json';

    // do the request
    $.ajax({
      url: actionurl,
      type: method,
      dataType: datatype,
      data: $("#typesform").serialize(),
      success: function( data ) {
      	// handle the results
				let erg = "Response: <br />";
        //$.each( data, function( key, value ) {
        //   erg += key + ": " + value + "<br />";
        //});
        if( data.state !== undefined ) {
          erg += "state: " + data.state + "<br />";
        }
        if( data.data !== undefined ) {
          erg += "data: " + JSON.stringify( data.data ) + "<br />";
        }

        if( data.state !== undefined && data.state === false ) {
          if( data.errormsg !== undefined ) {
            erg += "error: " + JSON.stringify( data.errormsg ) + "<br />";
            showMsg( 'danger', 'Failed', erg );
          }
        } else {
          // show message and redirect
          let msg = 'Successfull ' + GetURLParameter( 'action' ) + ' id ' + GetURLParameter( 'id' ) + ' of ' + GetURLParameter( 'type' );
          showMsg( 'success', GetURLParameter( 'action' ), msg, timeout );
          let url = window.location.origin + window.location.pathname + '?action=show&type=' + GetURLParameter('type');
          redirect( url, timeout );
        }
      },
      fail: function( err ) {
      	// View the error dump
      	console.dump( err );
        showMsg( 'danger', 'AJAX request failed', err );
      }
    });

  });

});
</script>

	<?php require_once( 'inc/tpl/footer.php' ); ?>

</div><!-- END container-->

</body>
</html>