<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <h3>Daemons</h3>
      <div id="daemons-pannel">
        <button onclick='daemonsCmd( "status" )'>Status</button> 
        <button onclick='daemonsCmd( "start" )'>Start</button> 
        <button onclick='daemonsCmd( "stop" )'>Stop</button> 
        <button onclick='daemonsCmd( "restart" )'>Restart</button> 
        <button onclick='daemonsCmd( "log" )'>Log</button>
        <button onclick='daemonsCmd( "clean" )'>Clean</button> 
      </div><br />
      <div id="output"></div><br />
			<div id="content">
		    loading...
			</div>
		</div>
	</div><!-- END row-->
  
	<script type="text/javascript">
    
    let controller = controllerName;
    let action = null;
    let currentDaemons = null;
    let currentTypes = null;
    let currentDevices = null;
    let currentType = null;
    let target = $( '#content' );
    
    // daemon cmd's
    // clean
    // status
    // start
    // stop
    // restart
    // log
    
    /**
     * Execute Daemon commands (clean,status,start,stop,restart,log)
     */
    function daemonsCmd( cmd ) {
      let result = apiJson( controllerName, 'daemon&cmd=' + cmd );
      if( result.state ) {
        var erg = result.data;
        if( Array.isArray(erg) ) {
          let tmperg = "";
          $( erg ).each( function( key, value ) {
            tmperg += value + "<br />\n";
          });
          erg = tmperg;
        } else {
          erg = JSON.stringify( erg );
        }
        $("#output").html( erg ).fadeIn(300).delay(10000).fadeOut();
        return true;
      }
      return false;
    }
    
    /**
     * List of daemon types
     */
    function getTypes() {
      let types = apiJson( 'daemontypes', 'read' ).data;
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
    function getDevices() {
      let devices = apiJson( 'devices', 'read' ).data;
      let newDevices = new Array();
      $( devices ).each( function( index, value ) {
        newDevices[ value.id ] = value;
      });
      currentDevices = newDevices;
    }
    
    /**
     * List of daemon types
     */
    function getDevicesName( id ) {
      let name = undefined;
      if( id === undefined ) return false;
      let devices = currentDevices;
      $( devices ).each( function( key, entry ) {
        if( key == id ) {
          name = entry.name;
        }
      });
      return name;
    }
    
    function renderDevicesSelector() {
      let content = '<select class="form-control" name="device" id="device">';
      content += '<option value="default" selected="selected" disabled="disabled">Bitte auswählen</option>';
      let devices = currentDevices;
      $( devices ).each( function( key, entry ) {
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
    
    function daemonsRead() {
      let daemons = apiJson( controllerName, 'read' ).data;
      return currentDaemons = daemons;
    }
    
    function renderDaemons() {
      let content = "<div><a href='?action=create'>Create Entry</a><hr />\n";
      daemonsRead();
      let daemons = currentDaemons;
      let total = daemons.length -1;
      $( daemons ).each( function( key, value ) {
        // content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Name: " + value.name + "</span><br />\n";
        content += "<span>Device: " + getDevicesName( value.device ) + "</span><br />\n";
        content += "<span>Type: " + getTypesName( value.type ) + "</span><br />\n";
        content += "<span>Active: " + value.active + "</span><br />\n";
        content += "<span>Start: " + value.start + "</span><br />\n";
        content += "<span>End: " + value.end + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&id=" + value.id + "' >Delete</a>";
        // content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function daemonsFormFields( item ) {
      let content = "";
      if( item === undefined || item.id === undefined || item.id === null ) {
        item = {
          id: null,
          name: "",
          device: "",
          type: "",
          active: "",
          start: "",
          end: "",
          updated: "",
          created: "",
       };
      } else {
        content = "<input type='hidden' name='id' value='" + item.id + "' />";
      }
      content += "<fieldset>";
//        content += "  <legend>Edit device #" + id + "</legend>";
      content += "  <div class='form-group'>";
      content += "    <label for='name'>Name:</label>";
      content += "    <input id='name' type='text' name='name' placeholder='" + item.name + "' value='" + item.name + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='device'>Device:</label>";
      content += "    " + renderDevicesSelector();
//      content += "    <input id='device' type='text' name='device' placeholder='" + item.device + "' value='" + item.device + "' class='form-control' />";
      content += "  </div>";
      
      content += "  <div class='form-group'>";
      content += "    <label for='type'>Type:</label>";
      content += "    " + renderTypesSelector();
      content += "  </div>";
      
      content += "  <div class='form-group'>";
      content += "    <label for='active'>Active:</label>";
      content += "    <input id='active' type='text' name='active' placeholder='" + item.active + "' value='" + item.active + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='start'>Start:</label>";
      content += "    <input id='start' type='text' name='start' placeholder='" + item.start + "' value='" + item.start + "' class='form-control' />";
      content += '  ';
      content += '  ';
      content += '  ';
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='end'>End:</label>";
      content += "    <input id='end' type='datetime' name='end' placeholder='" + item.end + "' value='" + item.end + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='updated'>Updated:</label>";
      content += "    <input id='updated' type='datetime' name='updated' placeholder='" + item.updated + "' value='" + item.updated + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='created'>Created:</label>";
      content += "    <input id='created' type='datetime' name='created' placeholder='" + item.created + "' value='" + item.created + "' class='form-control' />";
      content += "  </div>";
      content += "  <button type='submit' class='btn btn-primary mb-2'>Submit</button> <a href='./" + controllerName + ".php'>Show all</a>";
      content += "</fieldset>";
      let dateTypes = [ 'updated', 'created', 'datetime', 'end' ];
      $(document).ready( function() {
        $( dateTypes ).each( function( key, value ) {
          $('#' + value ).datetimepicker({ autoclose: true, language: 'de-DE', format: 'yyyy-mm-dd hh:ii:ss' });
        });
      });
      return content;
    }
    
    function daemonsEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let devices = apiJson( controllerName, 'read', id ).data;
      let total = devices.length -1;
      let content = "<form id='daemonsform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='update' />";
      $( devices ).each( function( key, value ) {
        currentType = value.type;
        
        content += daemonsFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }
    
    function daemonsDelete( id ) {
      let result = apiJson( controllerName, 'delete', id );
      target.html( result );
      return result;
    }
    
    function daemonsCreate() {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let devices = {
        id: null,
        name: "",
        device: "",
        type: "",
        active: "",
        start: "",
        end: "",
        updated: "",
        created: ""
      };
      let content = "<form id='daemonsform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='create' />";
      $( devices ).each( function( key, value ) {
//        currentType = value.type;
        content += daemonsFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }
    
    $(document).ready( function() {
      let action = GetURLParameter('action');
      let id = GetURLParameter('id');
      getTypes();
      getDevices();
      if( action == undefined || action === 'read' ) {
        // no action given or action = read, just render the devices List
        renderDaemons();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          daemonsEdit( id );
        }
      } else if( action === 'create' ) {
        // editing an entry
        daemonsCreate();
      } else if( action === 'delete' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          daemonsDelete( id );
          getTypes();
          getDevices();
          renderDaemons();
        }
      }
      // 
      //console.log( typeof( GetURLParameter('namen') ) );
    });
    
    /**
     * Submit form
     */
    $(function() {
      // event for form by id
      $("#daemonsform").submit( function(e) {
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
          data: $("#daemonsform").serialize(),
          success: function( data ) {
          	// handle the results
						var erg = "Response: <br />";
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