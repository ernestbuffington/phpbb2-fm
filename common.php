<?php
/** 
*
* @package phpBB2
* @version $Id: common.php,v 1.74.2.13 2004/07/15 18:00:34 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// The following code (unsetting globals)
// Thanks to Matt Kavanagh and Stefan Esser for providing feedback as well as patch files

// PHP5 with register_long_arrays off?
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;

	// _SESSION is the only superglobal which is conditionally set
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

// Protect against GLOBALS tricks
if (isset($HTTP_POST_VARS['GLOBALS']) || isset($HTTP_POST_FILES['GLOBALS']) || isset($HTTP_GET_VARS['GLOBALS']) || isset($HTTP_COOKIE_VARS['GLOBALS']))
{
	die('Hacking attempt!');
}

// Protect against HTTP_SESSION_VARS tricks
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS))
{
	die('Hacking attempt!');
}

if (@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on')
{
	// PHP4+ path
 	$not_unset = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS', 'HTTP_POST_FILES', 'phpEx', 'phpbb_root_path');
	
	// Not only will array_merge give a warning if a parameter
	// is not an array, it will actually fail. So we check if
	// HTTP_SESSION_VARS has been initialised.
	if (!isset($HTTP_SESSION_VARS) || !is_array($HTTP_SESSION_VARS))
	{
		$HTTP_SESSION_VARS = array();
	}

	// Merge all into one extremely huge array; unset
	// this later
	$input = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_SESSION_VARS, $HTTP_ENV_VARS, $HTTP_POST_FILES);

	unset($input['input']);
	unset($input['not_unset']);

	while (list($var,) = @each($input))
	{
		if (in_array($var, $not_unset))
		{
			die('Hacking attempt!');
		}
		unset($$var);
	}

	unset($input);
}

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($HTTP_GET_VARS) )
	{
		while( list($k, $v) = each($HTTP_GET_VARS) )
		{
			if( is_array($HTTP_GET_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
				{
					$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_GET_VARS[$k]);
			}
			else
			{
				$HTTP_GET_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_GET_VARS);
	}

	if( is_array($HTTP_POST_VARS) )
	{
		while( list($k, $v) = each($HTTP_POST_VARS) )
		{
			if( is_array($HTTP_POST_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
				{
					$HTTP_POST_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_POST_VARS[$k]);
			}
			else
			{
				$HTTP_POST_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_POST_VARS);
	}

	if( is_array($HTTP_COOKIE_VARS) )
	{
		while( list($k, $v) = each($HTTP_COOKIE_VARS) )
		{
			if( is_array($HTTP_COOKIE_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
				{
					$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_COOKIE_VARS[$k]);
			}
			else
			{
				$HTTP_COOKIE_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_COOKIE_VARS);
	}
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and other array values via
// URI params
//
$board_config = $forums_config = $advance_html = $userdata = $theme = $images = $lang = $pjirc_config = array();
$dss_seeded = $gen_simple_header = FALSE;

include($phpbb_root_path . 'config.'.$phpEx);

if( !defined("PHPBB_INSTALLED") )
{
	header('Location: ' . $phpbb_root_path . 'install/install.' . $phpEx);
	exit;
}

include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'includes/template.'.$phpEx);
include($phpbb_root_path . 'includes/sessions.'.$phpEx);
include($phpbb_root_path . 'includes/auth.'.$phpEx);
include($phpbb_root_path . 'includes/functions.'.$phpEx);
include($phpbb_root_path . 'includes/db.'.$phpEx);

// We do not need this any longer, unset for safety purposes
unset($dbpasswd);

// Include files for various MODS ...
include($phpbb_root_path . 'includes/db_optimize.'.$phpEx);
include($phpbb_root_path . 'includes/functions_points.'.$phpEx);
include($phpbb_root_path . 'includes/functions_modules.'.$phpEx); 

if (!(function_exists('phpbbdoctor_populate_cache')))
{
	include($phpbb_root_path . 'includes/functions_page_permissions.'.$phpEx);
}

//
// Obtain and encode users IP
//
// I'm removing HTTP_X_FORWARDED_FOR ... this may well cause other problems such as
// private range IP's appearing instead of the guilty routable IP, tough, don't
// even bother complaining ... go scream and shout at the idiots out there who feel
// "clever" is doing harm rather than good ... karma is a great thing ... :)
//
$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
$user_ip = encode_ip($client_ip);


//
// Setup forum wide options, if this fails
// then we output a CRITICAL_ERROR since
// basic forum information is not available
//
$cache_dir = $phpbb_root_path . 'cache';

//
// Get board_config
//
$cache_file = $cache_dir . '/config_board.php';

function get_board_config()
{
	global $db;

	$board_config = array();

	$sql = "SELECT *
		FROM " . CONFIG_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$board_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);
	
	return $board_config;
}

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$board_config = get_board_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($board_config as $k => $v)
			{
				if (is_int($v))
				{
					$lines[] = "'$k' => $v";
				}
				else if (is_bool($v))
				{
					$lines[] = "'$k' => " . (($v) ? 'TRUE' : 'FALSE');
				}
				else
				{
					$lines[] = "'$k' => '" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
				}
			}
			@fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $board_config = array(' . implode(',', $lines) . '); ?>');
			@fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$board_config = get_board_config();
}

// Override board configuration setting if user is an Administrator. 
if ($userdata['user_level'] == ADMIN) 
{
   	$board_config['allow_html'] = 1; 
}

//
// Get advance_html config
//
$cache_file = $cache_dir . '/config_html.php';

function get_html_config()
{
	global $db;

	$advance_html = array();

	$sql = "SELECT *
		FROM " . ADVANCE_HTML_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, "Could not query advance_html config information", "", __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$advance_html[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);
	
	return $advance_html;
}

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$advance_html = get_html_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($advance_html as $k => $v)
			{
				if (is_int($v))
				{
					$lines[] = "'$k' => $v";
				}
				else if (is_bool($v))
				{
					$lines[] = "'$k' => " . (($v) ? 'TRUE' : 'FALSE');
				}
				else
				{
					$lines[] = "'$k' => '" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
				}
			}
			@fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $advance_html = array(' . implode(',', $lines) . '); ?>');
			@fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$advance_html = get_html_config();
}

//
// Get pjirc config
//
$cache_file = $cache_dir . '/config_pjirc.php';

function get_pjirc_config()
{
	global $db;

	$pjirc_config = array();

	$sql = "SELECT *
		FROM " . PJIRC_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{		
		message_die(CRITICAL_ERROR, "Could not query chat config information", "", __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$pjirc_config[$row['pjirc_name']] = $row['pjirc_value'];
	}
	$db->sql_freeresult($result);

	return $pjirc_config;
}

if (file_exists($cache_dir) && is_dir($cache_dir) && is_writable($cache_dir))
{
	if (file_exists($cache_file))
///	if (file_exists($cache_file) && (time() < ''))
	{
		include($cache_file);
	}
	else
	{
		$pjirc_config = get_pjirc_config();
		$fp = @fopen($cache_file, 'wt+');
		if ($fp)
		{
			$lines = array();
			foreach ($pjirc_config as $k => $v)
			{
				if (is_int($v))
				{
					$lines[] = "'$k' => $v";
				}
				else if (is_bool($v))
				{
					$lines[] = "'$k' => " . (($v) ? 'TRUE' : 'FALSE');
				}
				else
				{
					$lines[] = "'$k' => '" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
				}
			}
			@fwrite($fp, '<?php if (!defined(\'IN_PHPBB\')) { die(\'Hacking attempt\'); } $pjirc_config = array(' . implode(',', $lines) . '); ?>');
			@fclose($fp);

			@chmod($cache_file, 0777);
		}
	}
}
else
{
	$pjirc_config = get_pjirc_config();
}

//
// Get meeting config
//
if (defined('IN_MEETING'))
{
	// Get the meeting config
	$sql = "SELECT * 
		FROM " . MEETING_CONFIG_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, "Could not query meeting config information", "", __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$board_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);
}


//
// Select default language
//
if( !isset($board_config['real_default_lang']) )
{
	$board_config['real_default_lang'] = $board_config['default_lang'];
}
$language = ( isset($HTTP_POST_VARS['language']) ) ? $HTTP_POST_VARS['language'] : $HTTP_GET_VARS['language']; 
if ($language) 
{ 
	$language = trim(strip_tags($language));	
	$board_config['default_lang'] = $language; 
	setcookie($board_config['cookie_name'] . '_default_lang', $language, (time() + 21600), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']); 
} 
else 
{
	if (isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_default_lang']) ) 
	{
	   $board_config['default_lang'] = $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_default_lang']; 
	} 
	else
	{
		$dir = @opendir($phpbb_root_path . 'language');
		$lang_d = array();
		while (false !== ($file = @readdir($dir)))
		{
			if ( ereg('^lang_', $file) && !@is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !@is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) )
			{
				$filename = trim(str_replace('lang_', '', $file));
				$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
				$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
				$lang_d[$displayname] = '1';
			}
		}
		@closedir($dir);
		asort($lang_d);
		reset($lang_d);
		while ( list($displayname, $filename) = @each($lang_d) )
		{
			if (strpos($displayname, $_SERVER['HTTP_ACCEPT_LANGUAGE']) === 0)
			{
				$language = $displayname;
				$board_config['default_lang'] = $language; 
				break;
			}
		}
	}
}

include_once($phpbb_root_path . 'mods/attachments/attachment_mod.'.$phpEx);

//
// Auto unban main admin & keep as main admin (user_id = 2)
//
$sql = "DELETE FROM " . BANLIST_TABLE . "
	WHERE ban_userid = 2";
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not un-ban the Main Administrator.', '', __LINE__, __FILE__, $sql);
}

$sql = "UPDATE " . USERS_TABLE . "
	SET user_level = " . ADMIN . " 
	WHERE user_id = 2";
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not update Main Administrator level information.', '', __LINE__, __FILE__, $sql);
}

$color_groups = color_groups();

//
// Check for Bots
//
define('IS_ROBOT', is_robot());

//
// Check for install/ directory, if needed disable the board
//
if ( file_exists('install') )
{
	message_die(GENERAL_MESSAGE, 'Board_disable');
}

?>