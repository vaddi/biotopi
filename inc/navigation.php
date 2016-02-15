<?php if( NAV === 0 ) : ?>
	<nav class="navigation">
		<ul id="headnav" class="nav nav-tabs">
			<li role="navigation"><a href="<?= URL ?>">Home</a></li>
<!--	<li role="navigation"><a href="stats.php" title="Status">Status</a></li>
			<li role="navigation"><a href="credits.php" title="Credits">Credits</a></li>  -->
		</ul>
		<script type="text/javascript">
			navigator( '#headnav' );
		</script>
	</nav>
<?php else: ?>
	<?php 
		$file = $_SERVER['DOCUMENT_ROOT'] . "/" . NAV;
		if( is_file( $file ) ) incl($file);
	?>
<?php endif; ?>


	
