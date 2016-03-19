<!DOCTYPE html>
<?php $preload = 'inc/functions.php'; if (file_exists($preload)) include $preload; ?>
<html lang="<?= APPLANG ?>">
<head>
<?php incl('inc/head.php'); ?>
</head>

<body>

<div class="container">
	
	<?php incl('inc/header.php'); ?>
	
	<div>
	
		<h3>PAGE NAME<small> PAGE TITEL</small></h3>
		
		PAGE CONTENT
		
	</div>
	
	<?php incl('inc/footer.php'); ?>
	
</div><!-- END .containter -->

</body>
</html>
