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
      content += "<span>Host: " + JSON.stringify( systemEntries.data.host ) + "</span><br />";
      content += "<span>CPUTemp: " + systemEntries.data.cpuTemp + "</span><br />";
      content += "<span>Memory: " + JSON.stringify( systemEntries.data.mem ) + "</span><br />";
      content += "<span>Load: " + JSON.stringify( systemEntries.data.load ) + "</span><br />";
      content += "<span>FS: " + JSON.stringify( systemEntries.data.fs ) + "</span><br />";
      content += "<span>Net: " + JSON.stringify( systemEntries.data.net ) + "</span><br />";
//      content += "<span>Time: " + JSON.stringify( systemEntries.time ) + "</span><br />";
      content += "<span>Updates: " + JSON.stringify( systemEntries.data.updates ) + "</span><br />";
      content += "<span>Git Last: " + JSON.stringify( systemEntries.data.gitlast ) + "</span><br />";
      content += "<span>Git Remote: " + JSON.stringify( systemEntries.data.gitremote ) + "</span><br />";
      content += "<span>Git Commits: " + JSON.stringify( systemEntries.data.gitcommits ) + "</span><br />";
      content += "<span>Git Version: " + JSON.stringify( systemEntries.data.gitversion ) + "</span><br />";
      content += "<span>App Size: " + JSON.stringify( systemEntries.data.appsize ) + "</span><br />";
      content += "<span>Total Files: " + JSON.stringify( systemEntries.data.ftotal ) + "</span><br />";
      content += "<span>Enviroment: " + JSON.stringify( systemEntries.data.env ) + "</span><br />";
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
      let entry = apiJson( controllerName, 'read' ).data;
      let total = entry.length -1;
      let content = "<div>";
      $( entry ).each( function( key, value ) {
        content += "<div>";
        content += "<span>ID: " + value.id + "</span><br />\n";
        content += "<span>Name: " + value.name + "</span><br />\n";
        content += "<span>Data: " + value.data + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a> <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&id=" + value.id + "' >Delete</a>";
        content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function systemEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let entry = apiJson( controllerName, 'read', id ).data;
      let total = entry.length -1;
      let content = "<form id='entryform' action='../' method='POST'>";
      $( entry ).each( function( key, value ) {
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
        content += "    <label for='name'>Data:</label>";
        content += "    <input id='data' type='text' name='data' placeholder='" + value.data + "' value='" + value.data + "' class='form-control' />";
        content += "  </div>";
        content += "  <button type='submit' style='margin:15px 0 0;'>Submit</button>"
        content += "</fieldset>";
      });
      content += "<form><br />";
      content += "<a href='./" + controllerName + ".php'>Overview</a>";
      target.html( content );
    }
    
    function systemDelete( id ) {
      let result = apiJson( controllerName, 'delete', id );
      return result;
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