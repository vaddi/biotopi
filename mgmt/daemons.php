<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <div id="daemons-pannel">
        <button onclick='daemonsCmd( "clean" )'>Clean</button> 
        <button onclick='daemonsCmd( "status" )'>Status</button> 
        <button onclick='daemonsCmd( "start" )'>Start</button> 
        <button onclick='daemonsCmd( "stop" )'>Stop</button> 
        <button onclick='daemonsCmd( "restart" )'>Restart</button> 
        <button onclick='daemonsCmd( "log" )'>Log</button>
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
        $("#output").html( erg ).fadeIn(300).delay(10000).fadeOut( "slow" );
      }
    }
    
    /**
     * List of daemon types
     */
    function getTypes() {
      let types = apiJson( 'daemontypes', 'read' );
      let newTypes = new Array();
      $( types ).each( function( key, entry ) {
        $( entry.data ).each( function( index, value ) {
          newTypes[ value.id ] = value;
        });
      });
      return newTypes;
    }
    
    /**
     * List of daemon types
     */
    function getTypesName( id ) {
      let name = undefined;
      if( id === undefined ) return false;
      let types = apiJson( 'daemontypes', 'read', id );
      $( types ).each( function( key, entry ) {
        if( entry.data[0].id == id ) {
          name = entry.data[0].name;
        }
      });
      return name;
    }
    
    function renderTypesSelector() {
      let content = '<select class="form-control" name="type" id="type">';
      let types = getTypes();
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
    
    function daemonsRead() {
      let daemons = apiJson( controllerName, 'read' ).data;
      currentDaemons = daemons;
      let total = daemons.length -1;
      let content = "<div>";
      $( daemons ).each( function( key, value ) {
        // content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Name: " + value.name + "</span><br />\n";
        content += "<span>Device: " + value.device + "</span><br />\n";
        content += "<span>Type: " + getTypesName( value.type ) + "</span><br />\n";
        content += "<span>Active: " + value.active + "</span><br />\n";
        content += "<span>Start: " + value.start + "</span><br />\n";
        content += "<span>End: " + value.end + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a>";
        // content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function daemonsEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let devices = apiJson( controllerName, 'read', id ).data;
      let total = devices.length -1;
      let content = "<form id='devicesform' action='../' method='POST'>";
      $( devices ).each( function( key, value ) {
        currentType = value.type;
        content += "<fieldset>";
//        content += "  <legend>Edit device #" + id + "</legend>";
        content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
        content += "  <input type='hidden' name='action' value='update' />";
        content += "  <input type='hidden' name='id' value='" + value.id + "' />";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Name:</label>";
        content += "    <input id='name' type='text' name='name' placeholder='" + value.name + "' value='" + value.name + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Device:</label>";
        content += "    <input id='device' type='text' name='device' placeholder='" + value.device + "' value='" + value.device + "' class='form-control' />";
        content += "  </div>";
        
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Type:</label>";
        content += "    " + renderTypesSelector();
//        content += "    <input id='type' type='text' name='type' placeholder='" + value.type + "' value='" + value.type + "' class='form-control' />";
        content += "  </div>";
        
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Active:</label>";
        content += "    <input id='active' type='text' name='active' placeholder='" + value.active + "' value='" + value.active + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Start:</label>";
        content += "    <input id='start' type='text' name='start' placeholder='" + value.start + "' value='" + value.start + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>End:</label>";
        content += "    <input id='end' type='text' name='end' placeholder='" + value.end + "' value='" + value.end + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Updated:</label>";
        content += "    <input id='updated' type='text' name='updated' placeholder='" + value.updated + "' value='" + value.updated + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Created:</label>";
        content += "    <input id='created' type='text' name='created' placeholder='" + value.created + "' value='" + value.created + "' class='form-control' />";
        content += "  </div>";
        content += "  <button type='submit' class='btn btn-primary mb-2'>Submit</button>"
        content += "</fieldset>";
      });
      content += "<form><br />";
      content += "<a href='./" + controllerName + ".php'>Overview</a>";
      target.html( content );
    }

    $(document).ready( function() {
      let action = GetURLParameter('action');
      let id = GetURLParameter('id');
//      console.log( 'action: ' + action + ', id: ' + id );
      if( action == undefined || action === 'read' ) {
        // no action given or action = read, just render the devices List
        daemonsRead();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          daemonsEdit( id );
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