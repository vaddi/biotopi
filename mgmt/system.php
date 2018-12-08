<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
      <div id="system-pannel"></div>
			<div id="content">
		    loading...
			</div>
			<div id="result"></div>
		</div>
	</div><!-- END row-->
  
	<script type="text/javascript">
    
    let controller = controllerName;
    let action = null;
    let target = $( '#content' );
    
    function systemRestart() {
      let confirmation = confirm('Acknowledge restart - Y/N');
      if( confirmation ) {
        return apiJson( controllerName, 'restart' );
      }
      return false;
    }

    function systemShutdown() {
      let confirmation = confirm('Acknowledge shutdown - Y/N');
      if( confirmation ) {
        return apiJson( controllerName, 'shutdown' );
      }
      return false;
    }
    
    function systemDashboard() {
      let systemEntries = apiJson( controllerName, 'getAll' );
//      let total = systemEntries.length -1;
      let content = "<div>";
      // $( systemEntries ).each( function( key, value ) {
      //   //content += "<span>ID: " + value.id + "</span><br />\n";
      //   console.log( value.mem );
      // });
//      content += "<span class='sparkline' data-width='100px' size='2.5' color='auto'>3 6 7 4 5 2 3 2 3 4 6</span><br />";
      content += "<span>Host: " + JSON.stringify( systemEntries.data.host ) + "</span><br />";
      content += "<span>Memory: " + JSON.stringify( systemEntries.data.mem ) + "</span><br />";
      content += "<span>Load: " + JSON.stringify( systemEntries.data.load ) + "</span><br />";
      content += "<span>FS: " + JSON.stringify( systemEntries.data.fs ) + "</span><br />";
      content += "<span>Net: " + JSON.stringify( systemEntries.data.net ) + "</span><br />";
//      content += "<span>Time: " + JSON.stringify( systemEntries.time ) + "</span><br />";
      content += "<br /><button onclick='return systemRestart()'>systemRestart</button> <button onclick='return systemShutdown()'>Shutdown</button><br />";
      content += "<div><br />";
      $( '#system-pannel' ).html( content );
    }
    
    // System functions:
    // getAll
    // getHost
    // getCpuTemp
    // getMem
    // getLoad
    // getFs
    // getNet
    // getUpdates
    
    // reboot
    // shutdown
    
    function systemRead() {
      let content = "<div> <a href='?action=create'>Create Entry</a><br />\n";
      let entry = apiJson( controllerName, 'read' ).data;
      if( entry === null || entry.length <= 0 ) {
        target.html( content + ' No Entries.' );
        return false;
      }
      let total = entry.length -1;
      $( entry ).each( function( key, value ) {
        content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Name: " + value.name + "</span><br />\n";
        content += "<span>Value: " + value.value + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a> <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&id=" + value.id + "' >Delete</a>";
        content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function systemFormFields( item ) {
      let content = "";
      if( item === undefined || item.id === undefined || item.id === null ) {
        item = {
          name: "",
          value: "",
          updated: "",
          created: ""
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
      content += "    <label for='name'>Value:</label>";
      content += "    <input id='value' type='text' name='value' placeholder='" + item.value + "' value='" + item.value + "' class='form-control' />";
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
    
    function systemEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let entry = apiJson( controllerName, 'read', id ).data;
      let total = entry.length -1;
      let content = "<form id='entryform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='update' />";
      $( entry ).each( function( key, value ) {
        content += systemFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }
    
    function systemDelete( id ) {
      let result = apiJson( controllerName, 'delete', id );
      target.html( result );
      return result;
    }
    
    function systemCreate() {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      //let devices = currentDaemons;
      let systems = {
        id: null,
        name: "",
        value: "",
        updated: "",
        created: ""
      };
      let content = "<form id='devicesform' action='../' method='POST'>";
      content += "  <input type='hidden' name='controller' value='" + controllerName + "' />";
      content += "  <input type='hidden' name='action' value='create' />";
      $( systems ).each( function( key, value ) {
//        currentType = value.type;
        content += systemFormFields( value );
      });
      content += "</form><br />";
      target.html( content );
    }
    
    
    // Main caller
    $(document).ready( function() {
      let action = GetURLParameter('action');
      let id = GetURLParameter('id');
      if( action == undefined || action === 'read' ) {
        systemDashboard();
        // no action given or action = read, just render the entry List
        systemRead();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          systemEdit( id );
        }
      } else if( action === 'delete' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          systemDelete( id );
          systemDashboard();
          systemRead();
        }
      } else if( action === 'create' ) {
        systemCreate( id );
      }
    });
    
    $(function() {
      // event for form by id
      $("#entryform").submit( function(e) {

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
          data: $("#entryform").serialize(),
          success: function( data ) {
          	// handle the results
						var erg = "Response: <br />";
            //$.each( data, function( key, value ) {
            //   erg += key + ": " + value + "<br />";
            //});
            erg += "state: " + data.state + "<br />";
            erg += "data: " + JSON.stringify( data.data ) + "<br />";
            
            $("#result").html( erg )
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