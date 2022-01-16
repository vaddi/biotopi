<?php

// Redirect if there is any script in the url
$currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if( strpos( $currentURL, 'SCRIPT' ) !== false ) {
	header("Location: " . str_replace( SCRIPT, '', $currentURL ) ); /* Browser umleiten */
	exit;
}
// Sanity check, install should only be checked from index.php
defined('PATH') or exit('Install tests must be loaded from within index.php!');

// Setup checks
$checks = array(
	// basicly neccessary
	'version' => true,
	'pcre' => true,
	'spl' => true,
	'reflection' => true,
	'filters' => true,
	'iconv' => true,
	'mbrstring' => true,
	'ctype' => true,
	'url' => true,
	'appdir' => true,
	'docroot' => true,
	'determ' => true,
	// optionals
	'pecl' => false,
	'curl' => true,
	'mcrypt' => false,
	'gd' => true,
	'mysql' => true,
	'pdo' => true
);

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	// Clear out the cache to prevent errors. This typically happens on Windows/FastCGI.
	clearstatcache();
} else {
	// Clearing the realpath() cache is only possible PHP 5.3+
	clearstatcache(TRUE);
}

function urlExists( $url = null ) {  
    if($url == null) return false;  
    if( function_exists( 'curl_init' ) ) {
    	$ch = curl_init($url);  
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			$data = curl_exec($ch);  
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
			curl_close($ch);
    } else {
    	return null;
    }
      
    if($httpcode>=200 && $httpcode<300){  
        return true;  
    } else {  
        return false;  
    }  
}

?>
<!DOCTYPE html>

<html lang="<?= APPLANG ?>">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= APPNAME ?> Installation</title>

	<style type="text/css">
	body { width: 42em; margin: 0 auto; font-family: sans-serif; background: #FFF; color: #000; font-size: 1em; }
	h1 { letter-spacing: -0.04em; }
	h1 + p { margin: 0 0 2em; color: #000; font-size: 90%; font-style: italic; }
	code { font-family: monaco, monospace; }
	table { border-collapse: collapse; width: 100%; }
		table th,
		table td { padding: 0.4em; text-align: left; vertical-align: top; }
		table th { width: 12em; font-weight: normal; }
		table tr:nth-child(odd) { background: #eee; }
		table td.pass { color: #171; }
		table td.fail { color: #711; }
	@-webkit-keyframes reset { 0% { opacity: 0; } 100% { opacity: 0; } }
	@-webkit-keyframes fade-in { 0% { opacity: 0; } 60% { opacity: 0; } 100% { opacity: 1; } }
	@-moz-keyframes reset { 0% { opacity: 0; } 100% { opacity: 0; } }
	@-moz-keyframes fade-in { 0% { opacity: 0; } 60% { opacity: 0; } 100% { opacity: 1; } }
	@keyframes reset { 0% { opacity: 0; } 100% { opacity: 0; } }
	@keyframes fade-in { 0% { opacity: 0; } 60% { opacity: 0; } 100% { opacity: 1; } }
	.fade-in {
		-webkit-animation-name: reset, fade-in;
		-webkit-animation-duration: 1s;
		-webkit-animation-timing-function: ease-in;
		-webkit-animation-iteration-count: 1;
		-moz-animation-name: reset, fade-in;
		-moz-animation-duration: 1s;
		-moz-animation-timing-function: ease-in;
		-moz-animation-iteration-count: 1;    
		animation-name: reset, fade-in;
		animation-duration: 1s;
		animation-timing-function: ease-in;
		animation-iteration-count: 1;
	}
	#mainh {text-align:center;}
	#results { padding: 0.8em; color: #fff; font-size: 1.4em; }
	#results.pass { background: #171; }
	#results.fail { background: #711; }
	</style>

</head>
<body>

	<h1 id="mainh">Environment Tests</h1>

	<p>
		The following tests have been run to determine if <a href="https://github.com/vaddi/biotopi"><?= APPNAME ?></a> will work in your environment.
		If any of the tests have failed, consult the <a href="https://github.com/vaddi/biotopi/wiki">documentation</a>
		for more information on how to correct the problem.
	</p>

	<?php $failed = FALSE ; ?>

	<table cellspacing="0">
		<?php if( $checks['version'] ) : ?>
			<tr>
				<th>PHP Version</th>
				<?php if (version_compare(PHP_VERSION, '5.3.3', '>=')): ?>
					<td class="pass"><?php echo PHP_VERSION ?></td>
				<?php else: $failed = TRUE ?>
					<td class="fail"><?= APPNAME ?> requires PHP 5.3.3 or newer, this version is <?php echo PHP_VERSION ?>.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['pcre'] ) : ?>
			<tr>
				<th>PCRE UTF-8</th>
				<?php if ( ! @preg_match('/^.$/u', 'ñ')): $failed = TRUE ?>
					<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.</td>
				<?php elseif ( ! @preg_match('/^\pL$/u', 'ñ')): $failed = TRUE ?>
					<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.</td>
				<?php else: ?>
					<td class="pass">Pass</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['spl'] ) : ?>
			<tr>
				<th>SPL Enabled</th>
				<?php if (function_exists('spl_autoload_register')): ?>
					<td class="pass">Pass</td>
				<?php else: $failed = TRUE ?>
					<td class="fail">PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['reflection'] ) : ?>
			<tr>
				<th>Reflection Enabled</th>
				<?php if (class_exists('ReflectionClass')): ?>
					<td class="pass">Pass</td>
				<?php else: $failed = TRUE ?>
					<td class="fail">PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['filters'] ) : ?>
			<tr>
				<th>Filters Enabled</th>
				<?php if (function_exists('filter_list')): ?>
					<td class="pass">Pass</td>
				<?php else: $failed = TRUE ?>
					<td class="fail">The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['iconv'] ) : ?>
			<tr>
				<th>Iconv Extension Loaded</th>
				<?php if (extension_loaded('iconv')): ?>
					<td class="pass">Pass</td>
				<?php else: $failed = TRUE ?>
					<td class="fail">The <a href="http://php.net/iconv">iconv</a> extension is not loaded.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['mbrstring'] ) : ?>
		<?php if (extension_loaded('mbstring')): ?>
			<tr>
				<th>Mbstring Not Overloaded</th>
				<?php if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING): $failed = TRUE ?>
					<td class="fail">The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP's native string functions.</td>
				<?php else: ?>
					<td class="pass">Pass</td>
				<?php endif ?>
			</tr>
		<?php endif ?>
		<?php endif; ?>
		<?php if( $checks['ctype'] ) : ?>
			<tr>
				<th>Character Type (CTYPE) Extension</th>
				<?php if ( ! function_exists('ctype_digit')): $failed = TRUE ?>
					<td class="fail">The <a href="http://php.net/ctype">ctype</a> extension is not enabled.</td>
				<?php else: ?>
					<td class="pass">Pass</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['url'] ) : ?>
			<tr>
				<th>URL validation</th>
				<?php // echo URL . "/" . $fcheck; ?>
				<?php if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', URL)): ?>
					<?php $fcheck = '/install.php'; if ( urlExists( URL . "/inc/assets/installation" . $fcheck ) ): ?>
					  <td class="pass"><?php echo URL ?></td>
					<?php else: $failed = TRUE ?>
						<td class="fail">The configured system <b>url</b> <br><b><?php echo URL ?></b><br> couldn't get resolved by curl. Verify that "php5-curl" is installed.</td>
					<?php endif ?>
				<?php else: $failed = TRUE ?>
					<td class="fail">The configured system <b>url</b> <br><b><?php echo URL ?></b><br> does not match to valid URL encoding.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['appdir'] ) : ?>
			<tr>
				<th>Application Directory</th>
				<?php if( is_file( __DIR__ . "/" . $fcheck ) ): ?>
					<td class="pass"><?php echo PATH ?></td>
				<?php else: $failed = TRUE ?>
					<td class="fail">The configured <b>system</b> directory <br><b><?php echo PATH ?></b><br> does not exist or does not contain required files <b><?= $fcheck ?></b>.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['docroot'] ) : ?>
			<tr>
				<th>Docroot Directory</th>
				<?php $fcheck = __DIR__ . "/install.php" ; if (is_file( $fcheck )): ?>
					<td class="pass"><?= $fcheck ?></td>
				<?php else: $failed = TRUE ?>
					<td class="fail">The <b><?= $fcheck ?></b> doesn't exist!</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['determ'] ) : ?>
			<tr>
				<th>PHP URI Determination</th>
				<?php if (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO'])): ?>
					<td class="pass">Pass</td>
				<?php else: $failed = TRUE ?>
					<td class="fail">Neither: <br>$_SERVER['REQUEST_URI'] <?= $_SERVER['REQUEST_URI'] ?><br>$_SERVER['PHP_SELF'] <?= $_SERVER['PHP_SELF'] ?><br>$_SERVER['PATH_INFO'] <?= $_SERVER['PATH_INFO'] ?><br>are available.</td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
	</table>

	<?php if ($failed === TRUE): ?>
		<p id="results" class="fail fade-in">✘ Application <b><?= APPNAME ?></b><br />
			&nbsp;&nbsp;&nbsp;&nbsp;will not work correctly with your environment!</p>
	<?php else: ?>
		<?php $realpath = str_replace("index.php/", "", $_SERVER['REQUEST_URI']); ?>
		<?php $abs_path = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) ?>
		<p id="results" class="pass fade-in">✔ Your environment passed all requirements.</p>
		<p>Run <b>install.sh</b> to install neccessary pakages and setup i2c and SPI kernel modules</p>
		<p>Remove or rename the <b>assets/installation/install.php</b> file or the whole assets/installation folder.<br />
		<br />
	<?php endif ?>
	
	<h1>Optional Tests</h1>

	<p>
		The following extensions are not required to run the <?= APPNAME ?> core, but if enabled can provide access to additional classes.
	</p>

	<table cellspacing="0">
		<?php if( $checks['pecl'] ) : ?>
			<tr>
				<th>PECL HTTP Enabled</th>
				<?php if (extension_loaded('http')): ?>
					<td class="pass">Pass</td>
				<?php else: ?>
					<td class="fail">Application can use the <a href="http://php.net/http">http</a> extension for the Request_Client_External class.<br /><pre class="hint">Try to add the extensions in <b>/etc/php5/apache2/php.ini</b>

	extension=raphf.so
	extension=propro.so
	extension=http.so
					</pre></td>
				<?php endif ?>
			</tr>
		<?php endif; ?>
		<?php if( $checks['curl'] ) : ?>
		<tr>
			<th>cURL Enabled</th>
			<?php if (extension_loaded('curl')): ?>
				<td class="pass">Pass</td>
			<?php else: ?>
				<td class="fail">Application can use the <a href="http://php.net/curl">cURL</a> extension for the Request_Client_External class.</td>
			<?php endif ?>
		</tr>
		<?php endif; ?>
		<?php if( $checks['mcrypt'] ) : ?>
		<tr>
			<th>mcrypt Enabled</th>
			<?php if (extension_loaded('mcrypt')): ?>
				<td class="pass">Pass</td>
			<?php else: ?>
				<td class="fail">Application requires <a href="http://php.net/mcrypt">mcrypt</a> for the Encrypt class.<br /><pre class="hint">Try this:

sudo updatedb 
locate mcrypt.ini

Should show it located at <b>/etc/php5/mods-available</b>

locate mcrypt.so

Edit mcrypt.ini and change extension to match the path to mcrypt.so, example:

extension=/usr/lib/php5/20121212/mcrypt.so
</pre>
</td>
			<?php endif ?>
		</tr>
		<?php endif; ?>
		<?php if( $checks['gd'] ) : ?>
		<tr>
			<th>GD Enabled</th>
			<?php if (function_exists('gd_info')): ?>
				<td class="pass">Pass</td>
			<?php else: ?>
				<td class="fail">Application requires <a href="http://php.net/gd">GD</a> v2 for the Image class. Verify that "php5-gd" is installed.</td>
			<?php endif ?>
		</tr>
		<?php endif; ?>
		<?php if( $checks['mysql'] ) : ?>
		<tr>
			<th>MySQL Enabled</th>
			<?php if (function_exists('mysql_connect')): ?>
				<td class="pass">Pass</td>
			<?php else: ?>
				<td class="fail">Application can use the <a href="http://php.net/mysql">MySQL</a> extension to support MySQL databases.</td>
			<?php endif ?>
		</tr>
		<?php endif; ?>
		<?php if( $checks['pdo'] ) : ?>
		<tr>
			<th>PDO Enabled</th>
			<?php if (class_exists('PDO')): ?>
				<td class="pass">Pass</td>
			<?php else: ?>
				<td class="fail">Application can use <a href="http://php.net/pdo">PDO</a> to support additional databases.</td>
			<?php endif ?>
		</tr>
		<?php endif; ?>
	</table>
	
	<br />
	<br />
	<br />
	
</body>
</html>

<?php exit ?>
