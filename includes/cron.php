<?php
/** 
*
* @package includes
* @version $Id: cron.php,v 1.47.2.5 2006/02/28 17:49:42 princeomz2004 Exp $
* @copyright (c) 2006 Omar Ramadan
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

if (!$board_config['enable_autobackup'])
{
	message_die(GENERAL_MESSAGE, $lang['Autobackup_disabled']);
}

//
// Increase maximum execution time, but don't complain about it if it isn't
// allowed.
//
// timing
$tstart = microtime();
@set_time_limit(1200);

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auto_backup.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auto_backup.' . $phpEx);

// Debugging
$debug = false;

// time + timezone = timezone in all dates
$time = create_date('U', time(), $board_config['board_timezone']);
if ($debug) echo gmdate($board_config['default_dateformat'], $time) . "\n";

/****************************************/
/*		don't change anything here		*/
/****************************************/
define("PC_MINUTE",	0);
define("PC_HOUR",	1);
define("PC_DOM",	2);
define("PC_MONTH",	3);
define("PC_DOW",	4);

$resultsSummary = '';

// Debugging function
function mark_start_finish ( $action )
{
	global $db, $time, $mark_start;
	
	if ( !$mark_start )
	{
		$sql = "SELECT finished
			FROM " . BACKUP_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			email_message_die($lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		if ( $row['finished'] == 0)
		{
			$update_time = "last_run = '" . $time . "' ,";
		}
	}
	
	$sql = "UPDATE " . BACKUP_TABLE . " 
		SET " . $update_time . " finished = '" . $action . "';";
	if ( !$db->sql_query($sql) )
	{
		email_message_die($lang['Error_updating_auto_backup'], '', __LINE__, __FILE__, $sql);
	} 
}

function email_message_die($msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $lang, $board_config, $phpEx;
	
	// Get info from backup table
	$query = "SELECT *
		FROM " . BACKUP_TABLE;
	$result = $db->sql_query($query);
	$backup_table = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	// Email Message
	$to      =  $backup_table['email'];
	$from    = $board_config['board_email'];
	$subject = ( empty($msg_title) ) ? $lang['error_email_subject'] : $msg_title;
	$message = $msg_text.' on line'.$err_line.' in'.$err_file."\n".$sql;
	$headers = 'From: '.$from. "\r\n";
	
	// Send the message
	if ( $board_config['smtp_delivery'] )
	{
		include($phpbb_root_path . 'includes/smtp.'.$phpEx);
		smtpmail($to, $subject, $message, $headers);
	}
	else
	{
		mail($to, $subject, $message, $headers);
	}
}

function lTrimZeros($number)
{
	global $debug;
	
	while ($number[0] == 0) 
	{
		$number = substr($number,1);
	}
	
	return $number;
}

function multisort(&$array, $sortby, $order='asc') 
{
	foreach($array as $val) 
	{
		$sortarray[] = $val[$sortby];
	}
	$c = $array;
	$const = $order == 'asc' ? SORT_ASC : SORT_DESC;
	$s = array_multisort($sortarray, $const, $c, $const);
	$array = $c;
	
	return $s;
}

function parseElement($element, &$targetArray, $numberOfElements) 
{
	global $debug;
	
	$subelements = explode(",", $element);
	for ($i = 0; $i < $numberOfElements; $i++) 
	{
		$targetArray[$i] = $subelements[0] == "*";
	}
	
	for ($i = 0; $i < count($subelements); $i++) 
	{
		if (preg_match("~^(\\*|([0-9]{1,2})(-([0-9]{1,2}))?)(/([0-9]{1,2}))?$~",$subelements[$i],$matches)) 
		{
			if ($matches[1] == "*") 
			{
				$matches[2] = 0;		// from
				$matches[4] = $numberOfElements;		//to
			} elseif ($matches[4] == "") 
			{
				$matches[4] = $matches[2];
			}
			
			if ($matches[5][0] != "/") 
			{
				$matches[6] = 1;		// step
			}
			
			for ($j = lTrimZeros($matches[2]); $j <= lTrimZeros($matches[4]); $j+=lTrimZeros($matches[6])) 
			{
				$targetArray[$j] = TRUE;
			}
		}
	}
}

function incDate(&$dateArr, $amount, $unit) 
{
	global $debug;
	
	if ($debug) echo sprintf("Increasing from %02d.%02d.%04d %02d:%02d by %d %6s ",$dateArr['mday'],$dateArr['mon'],$dateArr['year'],$dateArr['hours'],$dateArr['minutes'],$amount,$unit);
	if ($unit == "mday") 
	{
		$dateArr["hours"] = $dateArr["minutes"] = $dateArr["seconds"] = 0;
		$dateArr["mday"] += $amount;
		$dateArr["wday"] += $amount % 7;
		if ($dateArr["wday"] > 6) 
		{
			$dateArr["wday"]-=7;
		}

		$months28 = array(2);
		$months30 = array(4, 6, 9, 11);
		$months31 = array(1, 3, 5, 7, 8, 10, 12);
		
		if ( ((in_array($dateArr["mon"], $months28) && $dateArr["mday"] == 28) || (in_array($dateArr["mon"], $months30) && $dateArr["mday"] == 30) || (in_array($dateArr["mon"], $months31) && $dateArr["mday"] == 31)) && ($dateArr["hours"] >= 23)) 
		{
			$dateArr["mday"] = 1;
			
			if ( $dateArr["mon"] == 12 )
			{
				$dateArr["mon"] = 1;
				$dateArr["year"]++;
			}
			else
			{
				$dateArr["mon"]++;
			}
		}
	} 
	elseif ($unit=="hour") 
	{
		if ($dateArr["hours"]==23) 
		{
			incDate($dateArr, 1, "mday");
		} 
		else 
		{
			$dateArr["minutes"] = 0;
			$dateArr["seconds"] = 0;
			$dateArr["hours"]++;
		}
	} 
	elseif ($unit=="minute") 
	{
		if ($dateArr["minutes"]==59) 
		{
			incDate($dateArr, 1, "hour");
		} 
		else 
		{
			$dateArr["seconds"] = 0;
			$dateArr["minutes"]++;
		}
	}
	if ($debug) echo sprintf("to %02d.%02d.%04d %02d:%02d\n",$dateArr['mday'],$dateArr['mon'],$dateArr['year'],$dateArr['hours'],$dateArr['minutes']);
}

function getLastScheduledRunTime($job) 
{
	global $debug, $board_config;

	$extjob = Array();
	parseElement($job[PC_MINUTE], $extjob[PC_MINUTE], 60);
	parseElement($job[PC_HOUR], $extjob[PC_HOUR], 24);
	parseElement($job[PC_DOM], $extjob[PC_DOM], 31);
	parseElement($job[PC_MONTH], $extjob[PC_MONTH], 12);
	parseElement($job[PC_DOW], $extjob[PC_DOW], 7);
	
	$dateArr = gmgetdate(getLastActualRunTime());
	if ($debug) { echo 'gmgetdate ='; print_r($dateArr); }
	
	$minutesAhead = 0;
	while 
		(
		$minutesAhead<525600 AND 
		(!$extjob[PC_MINUTE][$dateArr["minutes"]] OR 
		!$extjob[PC_HOUR][$dateArr["hours"]] OR 
		(!$extjob[PC_DOM][$dateArr["mday"]] OR !$extjob[PC_DOW][$dateArr["wday"]]) OR
		!$extjob[PC_MONTH][$dateArr["mon"]])
	) {
	
	
		// make sure we have the right amount of days
		$months28 = Array(2);
		$months30 = Array(4,6,9,11);
		$months31 = Array(1,3,5,7,8,10,12);
		if ( in_array($dateArr["mon"], $months28) )
		{
			$days_in_month = 28;
		}
		else if ( in_array($dateArr["mon"], $months30) )
		{
			$days_in_month = 30;
		}
		else if ( in_array($dateArr["mon"], $months31) )
		{
			$days_in_month = 31;
		}
		
		
		if ( ( !$extjob[PC_DOM][$dateArr["mday"]] OR !$extjob[PC_DOW][$dateArr["wday"]] ) && $dateArr["mday"] <= $days_in_month)
		{
			incDate($dateArr,1,"mday");
			$minutesAhead+=1440;
			continue;
		}
		if (!$extjob[PC_HOUR][$dateArr["hours"]]) {
			incDate($dateArr,1,"hour");
			$minutesAhead+=60;
			continue;
		}
		if (!$extjob[PC_MINUTE][$dateArr["minutes"]]) {
			incDate($dateArr,1,"minute");
			$minutesAhead++;
			continue;
		}
	}
	
	$mktime = gmmktime($dateArr["hours"],$dateArr["minutes"],0,$dateArr["mon"],$dateArr["mday"],$dateArr["year"]);
	
	if ($debug) print_r($dateArr);
	if ($debug) echo "gmmktime is " . $mktime . "\n";
	
	return $mktime;
}

function getLastActualRunTime() 
{
	global $db;

	$sql = "SELECT last_run
		FROM " . BACKUP_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		email_message_die($lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $row['last_run'];
}

function gmgetdate( $time )
{
	$time_array = array
	(
	    'seconds' => intval(gmdate("s", $time)),
	    'minutes' => intval(gmdate("i", $time)),
	    'hours' => intval(gmdate("H", $time)),
	    'mday' => intval(gmdate("d", $time)),
	    'wday' => intval(gmdate("w", $time)),
	    'mon' => intval(gmdate("m", $time)),
	    'year' => intval(gmdate("Y", $time)),
	    'yday' =>  intval(gmdate("z", $time)),
	    'weekday' => gmdate("l", $time),
	    'month' => gmdate("F", $time),
	    '0' => gmdate("U", $time),
	);
	
	return $time_array;
}

function markLastRun() 
{
	global $db, $time;
	
	$sql = "UPDATE " . BACKUP_TABLE . " 
		SET last_run = '" . $time . "'";
	if ( !$db->sql_query($sql) )
	{
		email_message_die($lang['Error_updating_auto_backup'], '', __LINE__, __FILE__, $sql);
	} 
}

function write_cache($filename, $data)
{
	global $phpbb_root_path,$debug;
	
	// create sub dir if it doesnt exist
	if( !@is_dir($phpbb_root_path . '/cache/backup/') )
	{
		if ($debug) echo "Backups directory does not exist, attempting to create it\n";
		if( !@mkdir($phpbb_root_path . '/cache/backup/') )
		{
			if ($debug) echo "Can't create backups dir\n";
			return false;
		}
		else
		{
			if ($debug) echo "Directory created successfully. CHMODing to 0777\n";
			@chmod($path, 0777);
		}
	}
	
	if ($handle = @opendir($phpbb_root_path . '/cache/backup/'))
	{
		if ($debug) echo "Emptying Directory:\n";
		while (false !== ($item = readdir($handle))) 
		{
			if ($item != "." && $item != ".." && $item != "index.htm" && $item != "index.html" ) 
			{
				if ($debug) echo "\t Removing $item from cache\n";
				unlink($phpbb_root_path . '/cache/backup/' . $item);
			}
		}
		@closedir($handle);
	}
	
	// try to open file
	$file = @fopen($filename, 'w');

	// oh no! cant open the file!
	if(!$file)
	{
		if ($debug) echo "Can't open file\n";
		return false;
	}
	
	@fputs($file, "<?php\n//\n// Automatic Database Backup Cache.\n// Generated on " . date('r') . " (time=" . time() . ")\n//\n\n\$lastScheduled = $data;\n\n?>");
	@fputs($file, $code);
	@fclose($file);
	@chmod($filename, 0777);
	
	if ($debug) echo "Cache written!\n";
	return true;
}
function read_cache($filename)
{
	global $debug;
	
	if ( file_exists($filename) )
	{
		if ($debug) echo "Cache File Exists, Fetching Cache data.\n";
		include($filename);
		
		if ($debug) echo "Cache: last scheduled = $lastScheduled\n";
		return $lastScheduled;
	}
	else
	{
		if ($debug) echo "Cache File Doesn't Exists.\n";
		return false;
	}
}

function parseCron() 
{
	global $debug, $db, $phpEx, $phpbb_root_path;
	
	$sql = "SELECT *
		FROM " . BACKUP_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
	   email_message_die($lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
	}

	while (  $row = $db->sql_fetchrow($result) )
	{
		$cron_time = $row['cron_time'];
	}

	$when = "{$cron_time}";
	$job =  explode("    ", $when);
	if ( is_array( $job ) && count($job) == 5 ) 
	{
		if ($job[PC_DOW][0]!='*' AND !is_numeric($job[PC_DOW]))
		{
			$job[PC_DOW] = str_replace(
			Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat"),
			Array(0,1,2,3,4,5,6),
			$job[PC_DOW]);
		}
	}
	else
	{
		if ($debug) echo "Improper Cron Syntax\n";
		exit;
	}
	
	// last actual run time
	$job["lastActual"] = getLastActualRunTime();
	$filename = $phpbb_root_path . '/cache/backup/auto_backup_' . md5( $job['lastActual'] . $when ) . '.' . $phpEx;
	
	if ( !($job["lastScheduled"] = read_cache($filename) ) )
	{
		$job["lastScheduled"] = getLastScheduledRunTime($job);
		write_cache($filename, $job["lastScheduled"]);
	}
	
	multisort($job, "lastScheduled");
	
	if ($debug) var_dump($job);
	return $job;
}

// Run
	
mark_start_finish ('0'); // mark start
$mark_start = 1;
	
$job = parseCron();
$lastScheduled = $job["lastScheduled"];

// Drum added this line, and changed the if statement
// kkroo altered the if statement further more
$lastActual = $job["lastActual"];
			
$sql = "SELECT *
	FROM " . BACKUP_TABLE;
if ( !($result = $db->sql_query($sql)) )
{
	email_message_die($lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$delay_time = $row['delay_time'];
	
// show times if debug
if ($debug) echo "lastScheduled: " . gmdate($board_config['default_dateformat'], $lastScheduled) . "\n";
if ($debug) echo "lastActual: " . gmdate($board_config['default_dateformat'], $lastActual) . "\n";
if ($debug) echo "time - delay: " . gmdate($board_config['default_dateformat'], ($time - $delay_time)) . "\n";

// show parsing time if debug
$tend = microtime();
$taken = $tend - $tstart;

if ($debug) echo "Parsing cron took " . $taken . " seconds\n";

if ( ($lastScheduled <= $time) && ($lastActual < ($time - $delay_time)) )
{
	if ($debug) echo "Backup starting\n";
	// Drum moved these lines round
	markLastRun();
		
	$start = microtime();
	include($phpbb_root_path . 'includes/auto_backup.'.$phpEx);
	$finish = microtime();
		
	if ($debug) $taken = $finish - $start;
	if ($debug) echo "Backing up took " . $taken . " seconds\n";
} 

mark_start_finish ('1'); // Mark finish
	
?>