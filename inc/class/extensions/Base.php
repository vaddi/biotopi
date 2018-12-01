<?php

/**
 * Class Base
 * extend your Controllers by this class
 */
class Base {
	
	//
	// Git stuff
	//
	
	/**
	 * Compare the commit Hashes from the current commit and the last from git logs
	 */
	protected static function gitLast() {
		if( self::git() ) {
			if( is_file( '/usr/bin/git' ) ) {
				$fromlog = exec( 'git log -1 | grep commit | tail -c 41' );
				$current = exec( 'git rev-parse HEAD' );
				$result = '<span style="color:';
				if( $fromlog == $current ) $result .= 'inherit';
					else $result .= 'red';
				$result .= '"';
				$result .= '>' . $fromlog . '</span>';
				return $result;
			}
		}
		return false;
	}
	
	
	/**
	 * Get the current remote url 
	 */
	protected static function gitRemote() {
		if( self::git() ) {
			if( is_file( '/usr/bin/git' ) ) {
				$remotes = exec( '/usr/bin/git remote -v' );
				$line = explode( "\t", $remotes );
				$result = isset( $line[1] ) ? $line[1] : null;
				$result = preg_replace('/\(.*?\)|\s*/', '', $result);
				return $result;
			}
		}
		return false;
	}
	
	
	/**
	 * Get total application size
	 * @return	Appsize (/ whithout git folder)
	 */
	protected static function appSize() {
		$path = exec( 'pwd' );
		$size = explode( "\t", exec( '/usr/bin/du -s ' . $path ) );
		$real = isset( $size[0] ) ? number_format( $size[0] / 1024, 2 ) : null;
		if( self::git() ) {
			$size = explode( "\t", exec( '/usr/bin/du -s ' . $path . '/.git' ) );
			$git = isset( $size[0] ) ? number_format( $size[0] / 1024, 2 ) . ' MB' : null;
			return $real . 'MB/' . ( $real - $git );
		}
		return $real;
	}
	
	
	/**
	 * Get total application files in upload
	 */
	protected static function totalFiles() {
		$path = realpath( './' ) . '/' . Config::get( 'uploadfolder' );
		return ( exec( "find $path -not -type d | wc -l |tr -d ' '" ) );
	}
	
	
	/**
	 * Get the total amount of pushed commits
	 */
	protected static function gitCommits() {
		if( self::git() ) {
			if( is_file( '/usr/bin/git' ) ) 
				return exec( '/usr/bin/git rev-list --reverse HEAD | awk "{ print NR }" | tail -n 1' );
		}
		return false;
	}

	protected static function checkForUpdate() {
		// FIX me
//		if( is_file( '/usr/bin/git' ) ) {
//			$folder = str_replace( '/admin', '', realpath( './' ) ); 
//			return (int) shell_exec( "[ $(/usr/bin/git -C $folder rev-parse HEAD) = $(/usr/bin/git -C $folder ls-remote $(/usr/bin/git -C $folder rev-parse --abbrev-ref @{u} | \sed 's/\// /g') | cut -f1) ] && echo -n 0 || echo -n 1" );
//		}
		return false;
	} 

	/** 
	 * Helper function to get version number from "git tag" (dont forget to commit them!)
	 */
	protected static function getVersion() {
		if( self::git() ) {
			if( is_file( '/usr/bin/git' ) ) 
				return exec( '/usr/bin/git describe --abbrev=0 --tags' );
		}
		return false;
	}
	
	private static function git() {
		if( is_dir( realpath( './' ) . '/.git' ) ) return true;
		return false;
	}
	
	/**
	 * Helper function to get the used enviroment
	 */
	protected static function getEnv() {
		return ENV;
	}
	
	/**
	 * Helper to send SMTP E-Mails via PHP Pear Modul
	 * 
	 * sudo pear install Mail
	 * sudo pear install Net_SMTP
	 */
	protected static function smtpMail( $recipients = null, $msg = null, $subject = null ) {
		if( $recipients === null || $msg === null ) return false;
		if( $subject === null || $subject == "" ) $subject = "Default Subject";
		
		$mailhost = Config::get( 'mailhost' );
		$mailuser = Config::get( 'mailuser' );
		$mailpass = Config::get( 'mailpass' );
		$mailport = Config::get( 'mailport' );
		$mailproto = Config::get( 'mailproto' );
		
		if( $mailhost == null || $mailhost == '' || $mailuser == null || $mailuser == '' || $mailpass == null || $mailpass == '' || $mailproto == null || $mailproto == '' ) throw new Exception( "Missing Mail parameters." );
			
		$result = false;
		try {

			// http://email.about.com/od/emailprogrammingtips/qt/PHP_Email_SMTP_Authentication.htm
			// http://stackoverflow.com/a/33506709
		
			// load PHP Pear Mail
			$file = "/usr/share/php/Mail.php";
			// && is_file( '/usr/share/php/test/Net_SMTP/tests/config.php.dist' )
			if( is_file( $file ) ) {
				require_once $file;
			} else {
				// escape if php pear mail is not installed
				throw new Exception( 'You musst install PHP Pear Mail and Net_SMTP by Hand: sudo /usr/bin/pear install Mail Net_SMTP' );
				return;
			}
		
			// load config
	//		$file = __DIR__ . "/config.php";
	//		if( is_file( $file ) ) require_once $file;
			// Set recipient(s)
			if( is_array( $recipients ) ) {
				$to = '';
				foreach ( $recipients as $key => $recipient ) {
					$name = explode( '@', $recipient );
					$name = isset( $name[0] ) ? $name[0] : $recipient;
					$name = str_replace( '.',' ', $name );
					$to .= "$name <" . $recipient . ">;";
				}
				error_log( 'array: ' . $to ,0 );
			} else {
				$name = explode( '@', $recipients );
				$name = isset( $name[0] ) ? $name[0] : $recipients;
				$name = str_replace( '.',' ', $name );
				$to = "$name <" . $recipients . ">";
			}
		
			// Set Sender
			$from = Config::get( 'appname', $_SESSION['site'] ) . " <" . Config::get( 'mailuser', $_SESSION['site'] ) . ">";
		
			// should not occure, escape if still
			if( $to === null || $to === "" ) { throw new Exception( 'Failed to use recipient: ' . $recipient . ', aborted.' ); return; }
		
			// Our maildata from config
			$host 		= $mailhost;
			$username = $mailuser;
			$password = $mailpass;
			$protocol = $mailproto; // "ssl"
			$port 		= ( $mailport != null && is_numeric( $mailport ) && $mailport != 0 ) ? $mailport : 465;
//			$smtp 		= $protocol . "://" . $host . ":" . $port;
			$smtp 		= $protocol . "://" . $host;
		
			// Create mail header
			$headers = array ('From' => $from,
			 'To' => $to,
			 'Subject' => $subject);
			 
			// Create PHP Pear Mail Object
			$smtp = Mail::factory('smtp',
			 array (	'host' => $smtp,
				      	'auth' => true,
				      	'port' => $port,
				      	'timeout' => 10,
				      	'username' => $username,
				      	'password' => $password));
		
			// And send the mail out
			$mail = $smtp->send($to, $headers, $msg);
			
			$result = true;
		
	//		if( PEAR::isError( $mail ) ) {
			if( (new PEAR)->isError( $mail ) ) {
				throw new Exception( 'Fail send mail to ' . $to . ', aborted.' );
			} else {
				$result = true;
			}
		
		} catch( Exception $e ) {
			throw new Exception( '' . $e->getMessage() );
		}
	
		return $mail;	
	} // end smtpMail
	
}

?>
