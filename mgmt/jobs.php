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

let jobs = [];
let currentJobs = null;

// show list of all Types
function readJobs( jobsName ) {
  let jobs = apiJson( jobsName, 'read' ).data;
  return currentJobs = jobs;
}

// show list of all Type Elements
function jobsRead( jobsName ) {
  let content = "<div><a href='jobs.php'>View All Types</a> | <a href='?action=create&type=" + jobsName + "'>Create Entry</a><hr />\n";
  $( '#highlight' ).html( 'List of all <span style="color:#aaa;">' + jobsName + '</span>' );
  readJobs( jobsName );
  let jobs = currentJobs;
//  let types = Object.keys( defaultStruct() );
  if( jobs == undefined || jobs == null ) {
//    types = [{  }];
    content += 'No Entries.';
    $( '#content' ).html( content );
    return false;
  }
  let total = jobs.length -1;
  content += '<div>';
  $( jobs ).each( function( key, value ) {
    $.each(value, function( index, entry ) {
      //let datatype = defaultStruct( index );
      content += '<span>' + index + ': ' + entry + '</span><br />' + "\n";
    });
    content += "<a href='?action=edit&type=" + jobsName + "&id=" + value.id + "'>Edit</a> | <a onclick='return confirm(\"Acknowledge delete Entry " + value.id + " - " + value.name + " - Y/N\")' href='?action=delete&type=" + jobsName + "&id=" + value.id + "' >Delete</a>";
    if( key < total ) content += "<hr /><br />\n";
  });
  content += '</div>';
  content += '</div>';
  $( '#content' ).html( content );
}

$(document).ready( function() {


    jobsRead( controllerName + '_V' );


});
    
	</script>
    
	<?php require_once( 'inc/tpl/footer.php' ); ?>

</div><!-- END container-->

</body>
</html>