<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <div id="output"></div>
			<div id="content">
		    loading...
			</div>
		</div>
	</div><!-- END row-->
  
	<script type="text/javascript">
    
    let controller = controllerName;
    let action = null;
    let currentProtocols = null;
    let currentTypes = null;
    let currentType = null;
    let target = $( '#content' );

    /**
     * List of daemon types
     */
    function getTypes() {
      let types = apiJson( 'devicetypes', 'read' ).data;
      let newTypes = new Array();
      $( types ).each( function( index, value ) {
        newTypes[ value.id ] = value;
      });
      currentTypes = newTypes;
    }

    /**
     * List of daemon types
     */
    function getTypesName( id ) {
      let name = undefined;
      if( id === undefined ) return false;
      let types = currentTypes;
      $( types ).each( function( key, entry ) {
        if( key == id ) {
          name = entry.name;
        }
      });
      return name;
    }

    function renderTypesSelector() {
      let content = '<select class="form-control" name="type" id="type">';
      content += '<option value="default" selected="selected" disabled="disabled">Bitte auswählen</option>';
      let types = currentTypes;
      $( types ).each( function( key, entry ) {
        if( entry != undefined ) {
          if( entry.id == currentType ) {
            content += '  <option value="' + entry.id + '" selected>' + entry.id + ' - ' + entry.name + '</option>';
          } else {
            content += '  <option value="' + entry.id + '">' + entry.id + ' - ' + entry.name + '</option>';
          }
        }
      });
      content += '</select>';
      return content;
    }

    /**
     * List of daemon types
     */
    function getProtocols() {
      let protocols = apiJson( 'protocols', 'read' ).data;
      let newProtocols = new Array();
      $( protocols ).each( function( index, value ) {
        newProtocols[ value.id ] = value;
      });
      currentProtocols = newProtocols;
    }
    
    /**
     * List of daemon types
     */
    function getProtocolsName( id ) {
      let name = undefined;
      if( id === undefined ) return false;
      let protocols = currentProtocols;
      $( protocols ).each( function( key, entry ) {
        if( key == id ) {
          name = entry.name;
        }
      });
      return name;
    }
    
    function renderProtocolsSelector() {
      let content = '<select class="form-control" name="protocol" id="protocole">';
      content += '<option value="default" selected="selected" disabled="disabled">Bitte auswählen</option>';
      let protocols = currentProtocols;
      $( protocols ).each( function( key, entry ) {
        if( entry != undefined ) {
          if( entry.id == currentType ) {
            content += '  <option value="' + entry.id + '" selected>' + entry.id + ' - ' + entry.name + '</option>';
          } else {
            content += '  <option value="' + entry.id + '">' + entry.id + ' - ' + entry.name + '</option>';
          }
        }
      });
      content += '</select>';
      return content;
    }

    function devicesRead() {
      let devices = apiJson( controllerName, 'read' ).data;
      return currentDevices = devices;
    }

    function renderDevices() {
      let content = "<div><a href='?action=create'>Create Entry</a><br />\n";
      let devices = currentDevices;
      let total = devices.length -1;
      content += "<div>";
      $( devices ).each( function( key, value ) {
        content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Name: " + value.name + "</span><br />\n";
        content += "<span>Data: " + value.data + "</span><br />\n";
        content += "<span>Exec: " + value.exec + "</span><br />\n";
        content += "<span>Params: " + value.params + "</span><br />\n";
        content += "<span>Function: " + value.function + "</span><br />\n";
        content += "<span>HTML: " + value.html + "</span><br />\n";
        content += "<span>JS: " + value.js + "</span><br />\n";
        content += "<span>Pin: " + value.pin + "</span><br />\n";
        content += "<span>Protocol: " + getProtocolsName( value.protocol ) + "</span><br />\n";
        content += "<span>Status: " + value.status + "</span><br />\n";
        content += "<span>Threshold: " + value.threshold + "</span><br />\n";
        content += "<span>Type: " + getTypesName( value.type ) + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a> <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&id=" + value.id + "' >Delete</a>";
        content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function devicesFormFields( item ) {
      let content = "";
      if( item === undefined || item.id === undefined || item.id === null ) {
        item = {
          id: null,
          name: "",
          data: "",
          exec: "",
          params: "",
          function: "",
          html: "",
          js: "",
          pin: "",
          protocol: "",
          status: "",
          threshold: "",
          updated: "",
          created: "",
       };
      } else {
        content = "<input type='hidden' name='id' value='" + item.id + "' />";
      }
      content += "<fieldset>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Name:</label>";
      content += "    <input id='name' type='text' name='name' placeholder='" + item.name + "' value='" + item.name + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Data:</label>";
      content += "    <input id='data' type='text' name='data' placeholder='" + item.data + "' value='" + item.data + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Exec:</label>";
      content += "    <input id='exec' type='text' name='exec' placeholder='" + item.exec + "' value='" + item.exec + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Params:</label>";
      content += "    <input id='params' type='text' name='params' placeholder='" + item.params + "' value='" + item.params + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Function:</label>";
      content += "    <input id='function' type='text' name='function' placeholder='" + item.function + "' value='" + item.function + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>HTML:</label>";
      content += "    <input id='html' type='text' name='html' placeholder='" + item.html + "' value='" + item.html + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>JS:</label>";
      content += "    <input id='js' type='text' name='js' placeholder='" + item.js + "' value='" + item.js + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Pin:</label>";
      content += "    <input id='pin' type='text' name='pin' placeholder='" + item.pin + "' value='" + item.pin + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Protocol:</label>";
      content += "    " + renderProtocolsSelector( item.protocol );
//      content += "    <input id='protocol' type='text' name='protocol' placeholder='" + item.protocol + "' value='" + item.protocol + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Status:</label>";
      content += "    <input id='status' type='text' name='status' placeholder='" + item.status + "' value='" + item.status + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Threshold:</label>";
      content += "    <input id='threshold' type='number' name='threshold' placeholder='" + item.threshold + "' value='" + item.threshold + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Type:</label>";
      content += "    " + renderTypesSelector( item.type );
//      content += "    <input id='type' type='text' name='type' placeholder='" + item.type + "' value='" + item.type + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Updated:</label>";
      content += "    <input id='updated' type='text' name='updated' placeholder='" + item.updated + "' value='" + item.updated + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Created:</label>";
      content += "    <input id='created' type='text' name='created' placeholder='" + item.created + "' value='" + item.created + "' class='form-control' />";
      content += "  </div>";
      content += "  <button type='submit' style='margin:15px 0 0;'>Submit</button> <a href='./" + controllerName + ".php'>Show all</a>";
      content += "</fieldset>";
      return content;
    }
    
    
    function devicesEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let devices = apiJson( controllerName, 'read', id ).data;
      let total = devices.length -1;
      let content = "<form id='devicesform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='update' />";
      $( devices ).each( function( key, value ) {
        currentType = value.type;
        currentProtocol = value.protocol;
        content += devicesFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }

    function devicesDelete( id ) {
      let result = apiJson( controllerName, 'delete', id );
      target.html( result );
      return result;
    }

    function devicesCreate() {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let devices = {
        id: null,
        name: "",
        data: "",
        exec: "",
        params: "",
        function: "",
        html: "",
        js: "",
        pin: "",
        protocol: "",
        status: "",
        threshold: "",
        updated: "",
        created: "",
      };
      let content = "<form id='devicesform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='create' />";
      $( devices ).each( function( key, value ) {
//        currentType = value.type;
        content += devicesFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }

    $(document).ready( function() {
      let action = GetURLParameter('action');
      let id = GetURLParameter('id');
      getTypes();
      getProtocols();
//      console.log( 'action: ' + action + ', id: ' + id );
      if( action == undefined || action === 'read' ) {
        // no action given or action = read, just render the devices List
        devicesRead();
        
        renderDevices();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          devicesEdit( id );
        }
      } else if( action === 'create' ) {
        // editing an entry
        devicesCreate();
      } else if( action === 'delete' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          devicesDelete( id );
          devicesRead();

          renderDevices();
        }
      }
      // 
      //console.log( typeof( GetURLParameter('namen') ) );
    });
    
    $(function() {
      // event for form by id
      $("#devicesform").submit( function(e) {

        // prevent Default
        e.preventDefault();

        // get form attribute or set default value if not set
        var actionurl = ( e.currentTarget.action !== undefined || e.currentTarget.action !== "" ) ? e.currentTarget.action : window.location; // default action
				var method = ( e.currentTarget.method !== undefined || e.currentTarget.method !== "" ) ? e.currentTarget.method : 'post'; // default method
				var datatype = ( e.currentTarget.datatype !== undefined || e.currentTarget.datatype !== "" ) ? e.currentTarget.datatype : 'json'; // default data type
		
        // do the request
        $.ajax({
          url: actionurl,
          type: method,
          dataType: datatype,
          data: $("#devicesform").serialize(),
          success: function( data ) {
          	// handle the results
						var erg = "Response: <br />";
            //$.each( data, function( key, value ) {
            //   erg += key + ": " + value + "<br />";
            //});
            erg += "state: " + data.state + "<br />";
            erg += "data: " + JSON.stringify( data.data ) + "<br />";
            
            $("#output").html( erg )
            	.fadeIn(300)    // fade in time
            	.delay(10000)   // message appears for X milli seconds
            .fadeOut( "slow" ) ; // fade out effect ( slow, normal, fast )
          },
          fail: function( err ) {
          	// View the error dump
          	console.dump( err );
          }
        });

      });

    });
    
	</script>
    
	<?php require_once( 'inc/tpl/footer.php' ); ?>

</div><!-- END container-->

</body>
</html>