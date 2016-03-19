<title><?= APPNAME ?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<?php /* favicon loader */ if ( is_file( FAVICON ) ) { ?>
<!-- icon -->
<link rel="shortcut icon" href="<?= FAVICON ?>" />
<?php } ?>
<!-- styles -->
<?php /* css loader */ 
setCss( 'inc/css/' ); ?>
<!-- javascript -->
<script type="text/javascript">
  var cid = "<?= CLIENTTOKEN; ?>";
</script>
<?php /* javascript loader */ 
setJs( 'inc/js/' ); ?>
