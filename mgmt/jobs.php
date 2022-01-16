<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <h3>Jobs</h3>
      <div id="output"></div>
			<div id="content">
		    loading...
			</div>
		</div>
	</div><!-- END row-->
  
	<script type="text/javascript">
    
    let controller = controllerName;
    let action = null;
    let currentJobs = null;
    let currentDevices = null;
    let currentDevice = null;
    let currentDaemons = null;
    let currentDaemon = null;
    let target = $( '#content' );

    /**
     * List of daemon types
     */
    function getDaemons() {
      let daemons = apiJson( 'daemons', 'read' ).data;
      let newDaemons = new Array();
      $( daemons ).each( function( index, value ) {
        newDaemons[ value.id ] = value;
      });
      currentDaemons = newDaemons;
    }

    /**
     * List of daemon types
     */
    function getDaemonsName( id ) {
      let name = undefined;
      if( id === undefined ) return false;
      let daemons = currentDaemons;
      $( daemons ).each( function( key, entry ) {
        if( key == id ) {
          name = entry.name;
        }
      });
      return name;
    }

    function renderDaemonsSelector() {
      let content = '<select class="form-control" name="id_daemons" id="id_daemons">';
      content += '<option value="default" selected="selected" disabled="disabled">Bitte auswählen</option>';
      let daemons = currentDaemons;
      $( daemons ).each( function( key, entry ) {
        if( entry != undefined ) {
          if( entry.id == currentDaemon ) {
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
      let content = '<select class="form-control" name="id_devices" id="id_devices">';
      content += '<option value="default" selected="selected" disabled="disabled">Bitte auswählen</option>';
      let devices = currentDevices;
      $( devices ).each( function( key, entry ) {
        if( entry != undefined ) {
          if( entry.id == currentDevice ) {
            content += '  <option value="' + entry.id + '" selected>' + entry.id + ' - ' + entry.name + '</option>';
          } else {
            content += '  <option value="' + entry.id + '">' + entry.id + ' - ' + entry.name + '</option>';
          }
        }
      });
      content += '</select>';
      return content;
    }

    function jobsRead() {
      let jobs = apiJson( controllerName, 'read' ).data;
      return currentJobs = jobs;
    }

    function renderJobs() {
      let content = "<div><a href='?action=create'>Create Entry</a><hr />\n";
      jobsRead();
      let jobs = currentJobs;
      let total = jobs != null && jobs.length > 0 ? jobs.length -1 : 0;
      content += "<div>";
      $( jobs ).each( function( key, value ) {
        content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Device: " + getDevicesName( value.id_devices ) + "</span><br />\n";
        content += "<span>Daemon: " + getDaemonsName( value.id_daemons ) + "</span><br />\n";
        content += "<span>Start: " + value.start + "</span><br />\n";
        content += "<span>End: " + value.end + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&id=" + value.id + "' >Delete</a>";
        content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }

    function jobsFormFields( item ) {
      let content = "";
      if( item === undefined || item.id === undefined || item.id === null ) {
        item = {
          id: null,
          id_devices: "",
          id_daemons: "",
          start: "",
          end: "",
          updated: "",
          created: ""
       };
      } else {
        content = "<input type='hidden' name='id' value='" + item.id + "' />";
      }
      content += "<fieldset>";
      content += "  <legend>" + item.id + "</legend>";
      content += "  <div class='form-group'>";
      content += "    <label for='id_devices'>Device:</label>";
      content += "    " + renderDevicesSelector();
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='id_daemons'>Daemon:</label>";
      content += "    " + renderDaemonsSelector();
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='start'>Start:</label>";
      content += "    <input id='start' type='text' name='start' placeholder='" + item.start + "' value='" + item.start + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='end'>End:</label>";
      content += "    <input id='end' type='text' name='end' placeholder='" + item.end + "' value='" + item.end + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='updated'>Updated:</label>";
      content += "    <input id='updated' type='text' name='updated' placeholder='" + item.updated + "' value='" + item.updated + "' class='form-control' />";
      content += "  </div>";
      content += "  <div class='form-group'>";
      content += "    <label for='created'>Created:</label>";
      content += "    <input id='created' type='text' name='created' placeholder='" + item.created + "' value='" + item.created + "' class='form-control' />";
      content += "  </div>";
      content += "  <button type='submit' class='btn btn-primary mb-2'>Submit</button> <a href='./" + controllerName + ".php'>Show all</a>";
      content += "</fieldset>";
      return content;
    }

    function jobsShow( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let jobs = apiJson( controllerName, 'read', id ).data;
      let total = jobs.length -1;
      let content = "<div id='jobsform'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='show' />";
      $( jobs ).each( function( key, value ) {
        currentDevice = value.id_devices;
        currentDaemon = value.id_daemons;
        content += jobsFormFields( value );
      });
      content += "</div><br />";
      target.html( content );
    }

    function jobsEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let jobs = apiJson( controllerName, 'read', id ).data;
      let total = jobs.length -1;
      let content = "<form id='jobsform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='update' />";
      $( jobs ).each( function( key, value ) {
        currentDevice = value.id_devices;
        currentDaemon = value.id_daemons;
        content += jobsFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }

    function jobsDelete( id ) {
      let result = apiJson( controllerName, 'delete', id );
      target.html( result );
      return result;
    }

    function jobsCreate() {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let jobs = {
        id: null,
        id_devices: "",
        id_daemons: "",
        start: "",
        end: "",
        updated: "",
        created: ""
      };
      let content = "<form id='devicesform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='create' />";
      $( jobs ).each( function( key, value ) {
//        currentType = value.type;
        content += jobsFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }

    $(document).ready( function() {
      let action = GetURLParameter('action');
      let id = GetURLParameter('id');
      getDevices();
      getDaemons();
      // getTypes();
      // getProtocols();
//      console.log( 'action: ' + action + ', id: ' + id );
      if( action == undefined || action === 'read' ) {
        // no action given or action = read, just render the devices List
        renderJobs();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          jobsEdit( id );
        }
      } else if( action === 'show' ) {
        // editing an entry
        jobsShow( id );
      } else if( action === 'create' ) {
        // editing an entry
        jobsCreate();
      } else if( action === 'delete' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          jobsDelete( id );
          getDevices();
          getDaemons();
          renderJobs();
        }
      }
      // 
      //console.log( typeof( GetURLParameter('namen') ) );
    });
    
    $(function() {
      // event for form by id
      $("#jobsform").submit( function(e) {

        // prevent Default
        e.preventDefault();

        // get form attribute or set default value if not set
        var actionurl = ( e.currentTarget.action !== undefined || e.currentTarget.action !== "" ) ? e.currentTarget.action : window.location; // default action
				var method = ( e.currentTarget.method !== undefined || e.currentTarget.method !== "" ) ? e.currentTarget.method : 'post'; // default method
				var datatype = ( e.currentTarget.datatype !== undefined || e.currentTarget.datatype !== "" ) ? e.currentTarget.datatype : 'json'; // default data type
        console.log( actionurl );
        // do the request
        $.ajax({
          url: actionurl,
          type: method,
          dataType: datatype,
          data: $("#jobsform").serialize(),
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