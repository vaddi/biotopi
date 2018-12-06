<!DOCTYPE html>
<html lang="de">
<?php require_once( 'inc/tpl/head.php' ); ?>

<body>

<?php require_once( 'inc/tpl/nav.php' ); ?>

<div id="template_container" class="container">

	<?php require_once( 'inc/tpl/header.php' ); ?>

	<div id="searchfield" class="row">
		<div class="col-sm-12">
			<div id="content">
		    loading...
			</div>
			<div id="result">
		    
			</div>
		</div>
	</div><!-- END row-->
  
	<script type="text/javascript">
    
    let controller = controllerName;
    let action = null;
    let target = $( '#content' );
    
    function devicesRead() {
      let devices = apiJson( controllerName, 'read' ).data;
      let total = devices.length -1;
      let content = "<div>";
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
        content += "<span>Protocol: " + value.protocol + "</span><br />\n";
        content += "<span>Status: " + value.status + "</span><br />\n";
        content += "<span>Threshold: " + value.threshold + "</span><br />\n";
        content += "<span>Type: " + value.type + "</span><br />\n";
        content += "<span>Updated: " + value.updated + "</span><br />\n";
        content += "<span>Created: " + value.created + "</span><br />\n";
        content += "<a href='?action=edit&id=" + value.id + "'>Edit</a>";
        content += "<div>";
        if( key < total ) content += "<hr /><br />\n";
      });
      content += "<div>";
      target.html( content );
    }
    
    function devicesEdit( id ) {
      //console.log( 'controller: ' + controllerName + ', action: ' + action + ', id: ' + id );
      let devices = apiJson( controllerName, 'read', id ).data;
      let total = devices.length -1;
      let content = "<form id='devicesform' action='../' method='POST'>";
      $( devices ).each( function( key, value ) {
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
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Exec:</label>";
        content += "    <input id='exec' type='text' name='exec' placeholder='" + value.exec + "' value='" + value.exec + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Params:</label>";
        content += "    <input id='params' type='text' name='params' placeholder='" + value.params + "' value='" + value.params + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Function:</label>";
        content += "    <input id='function' type='text' name='function' placeholder='" + value.function + "' value='" + value.function + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>HTML:</label>";
        content += "    <input id='html' type='text' name='html' placeholder='" + value.html + "' value='" + value.html + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>JS:</label>";
        content += "    <input id='js' type='text' name='js' placeholder='" + value.js + "' value='" + value.js + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Pin:</label>";
        content += "    <input id='pin' type='text' name='pin' placeholder='" + value.pin + "' value='" + value.pin + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Protocol:</label>";
        content += "    <input id='protocol' type='text' name='protocol' placeholder='" + value.protocol + "' value='" + value.protocol + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Status:</label>";
        content += "    <input id='status' type='text' name='status' placeholder='" + value.status + "' value='" + value.status + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Threshold:</label>";
        content += "    <input id='threshold' type='number' name='threshold' placeholder='" + value.threshold + "' value='" + value.threshold + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Updated:</label>";
        content += "    <input id='updated' type='text' name='updated' placeholder='" + convertDate( value.updated ) + "' value='" + value.updated + "' class='form-control' />";
        content += "  </div>";
        content += "  <div class='form-group'>";
        content += "    <label for='name'>Created:</label>";
        content += "    <input id='created' type='text' name='created' placeholder='" + convertDate( value.created ) + "' value='" + value.created + "' class='form-control' />";
        content += "  </div>";
        content += "  <button type='submit' style='margin:15px 0 0;'>Submit</button>"
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
        devicesRead();
      } else if( action === 'edit' ) {
        if( id == undefined  ) {
          console.log( 'No or wrong ID given.' );
        } else {
          // editing an entry
          devicesEdit( id );
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