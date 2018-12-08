<?php

// get git values
if( is_file( '/usr/bin/git' ) ) $tags = exec( '/usr/bin/git describe --abbrev=0 --tags' );
if( is_file( '/usr/bin/git' ) ) $commits = exec( '/usr/bin/git rev-list --reverse HEAD | awk "{ print NR }" | tail -n 1' );

?>
<footer class="page-footer">
	<p class="pull-center pull-top">Ladezeit: <span id="pageload"></span> Sek.</p>
	<p class="pull-right"><?= $tags ?></p>
	<p class="pull-left"><?= $commits ?> git commits</p>
</footer>