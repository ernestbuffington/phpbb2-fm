<?php
/** 
*
* @package phpBB2
* @version $Id: lgf-reflog.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 Charles F. Johnson
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
   die("Hacking attempt");
}

$reflog = 'cache/reflog.txt';
$semaphore = 'cache/semaphore.ref';
$maxref = 10;
$mydomain = $board_config['server_name'];
$ref = getenv("HTTP_REFERER");

if (($ref) and (!strstr($ref, $mydomain))) 
{	
	// if there's a referrer, and it's not someone bouncing around this site
	$ref .= "\n"; // append a line feed
	$sp = @fopen($semaphore, 'w'); // open the semaphore file
	if (flock($sp, 2)) 
	{				
		// lock the semaphore; other processes will stop and wait here
		$rfile = file($reflog); // read the referrer log into an array
		if ($ref <> $rfile[0]) 
		{		
			// if this referrer is different from the last one
			if (sizeof($rfile) == $maxref) // if the file is full
			{
				array_pop($rfile); // pop the last element
			}
			array_unshift($rfile, $ref); // push the new referrer onto the front
			$r = join('', $rfile); // make the array into a string
			$rp = @fopen($reflog, 'w'); // open the referrer log in write mode
			$status = @fwrite($rp, $r); // write out the referrer URLs
			$status = @fclose($rp); // close the log
		}
	}
	$status = @fclose($sp); // close the semaphore (and release the lock)
}

?>