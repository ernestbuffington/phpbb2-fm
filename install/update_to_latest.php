<?php
/** 
*
* @package install
* @version $Id: update_to_latest.php 29/05/2007 3:50 PM mj Exp $
* @copyright (c) 2005 MJ, Fully Modded phpBB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/


//
// New version information
//
$script_version = 'Fully Modded SVU v4.0';
$updates_to_version = '.0.23';
$updates_to_fm_version = 90409;


// ---------
// START FUNCTIONS
//
function _sql($sql, &$errored, &$error_ary, $echo_dot = true)
{
	global $db;

	if (!($result = $db->sql_query($sql)))
	{
		$errored = true;
		$error_ary['sql'][] = (is_array($sql)) ? $sql[$i] : $sql;
		$error_ary['error_code'][] = $db->sql_error();
	}

	if ($echo_dot)
	{
		echo ". \n";
		flush();
	}

	return $result;
}

//
// Function will apply each fm_update completely in turn, this will fix any problems of tables 
// being deleted from the schema update before the data update has had a chance to move the 
// data to another table. Will also define which errors belong to which build update.
//
function _update_fm($old_ver, $new_ver, $update_file)
{
	global $db, $board_config, $phpbb_root_path, $phpEx, $table_prefix;

	$sql = $error_ary = array(); 
	$errored = false;
	
	echo "<p>Updating <b>" . $old_ver . "</b> to <b>" . $new_ver . "</b><br />\n";
	echo "Progress :: ";
	include($phpbb_root_path . 'install/updates/' . $update_file . '.'.$phpEx);
	if (sizeof($sql))
	{
		echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";
			
		if ($errored)
		{
			echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";
		
			for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
			{
				echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
				echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
			}
				
			echo "</ul>\n<p>This is probably nothing to worry about, update to the next build will continue.</p>\n";
		}
		else
		{
			echo "<b>No errors encounted</b></p>\n";
		}
	}
	
	return;
}
//
// END FUNCTIONS
// ---------

//
// Begin
//
error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// The following code (unsetting globals)
// Thanks to Matt Kavanagh and Stefan Esser for providing feedback as well as patch files

//
// PHP5 with register_long_arrays off?
//
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
	die("Hacking attempt");
}

// Protect against HTTP_SESSION_VARS tricks
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS))
{
	die("Hacking attempt");
}

if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
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
if (!get_magic_quotes_gpc())
{
	if (is_array($HTTP_GET_VARS))
	{
		while (list($k, $v) = each($HTTP_GET_VARS))
		{
			if (is_array($HTTP_GET_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_GET_VARS[$k]))
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

	if (is_array($HTTP_POST_VARS))
	{
		while (list($k, $v) = each($HTTP_POST_VARS))
		{
			if (is_array($HTTP_POST_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_POST_VARS[$k]))
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

	if (is_array($HTTP_COOKIE_VARS))
	{
		while (list($k, $v) = each($HTTP_COOKIE_VARS))
		{
			if (is_array($HTTP_COOKIE_VARS[$k]))
			{
				while (list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]))
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
// Begin main prog
//
@set_time_limit(300);
define('IN_PHPBB', 1);
$phpbb_root_path = './../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'config.'.$phpEx);

//
// Check for correct MySQL setting, 
// 'mysql' is the ONLY dbm compatible!
//
if ( !isset($dbms) || $dbms !== 'mysql' )
{
	die('<b class="error">CRITICAL ERROR!</b><br /><br />Possible reasons:<ul type="i"><li>No <u>config.php</u> file exists</li><li>No <u>MySQL</u> database exists (Only MySQL is supported by Fully Modded phpBB)</li><li>Incorrect setting in the <u>config.php</u> file (Open with a text editor and change the <u>mysql4</u> value to <u>mysql</u> before attempting to update)</li></ul>');
}


//
// Include some required functions
//
include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'includes/functions.'.$phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'includes/db.'.$phpEx);


//
// Check for min. PHP version
//
if (version_compare(@phpversion(), PHP_REQ) < 0)
{
	die("<b style='color: #D20000'>CRITICAL ERROR!</b><br /><br />Sorry, the PHP version on this server does not meet the minimum requirements for Fully Modded phpBB. Please contact your host to update it.<br /><br /><b>Req. RHP Version: " . PHP_REQ . "<br />Your PHP version: " . @phpversion() . "</b>");
}


//
// Obtain various vars
//
// $mode = request_var('mode', '');
if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}


//
// Setup forum wide options, if this fails
// then we output a CRITICAL_ERROR since
// basic forum information is not available
//
$board_config = array();
$sql = "SELECT * FROM " . CONFIG_TABLE;
if (!($result = $db->sql_query($sql)))
{
	die('<b class="error">CRITICAL ERROR!</b><br /><br />Could not query forum wide configuration information, please check a database exists.');
}

while ( $row = $db->sql_fetchrow($result) )
{
	$board_config[$row['config_name']] = $row['config_value'];
}
$db->sql_freeresult($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fully Modded phpBB &bull; Smart Version Updater</title>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">
<!--
* { margin-top: 0; }
body {  font-family: "Lucida Grande", Verdana, Helvetica, Arial, sans-serif; color: #536482; background: #E4EDF0 url("../templates/subSilver/images/bg_header.gif") 0 0 repeat-x; font-size: 62.5%; scrollbar-face-color: #DCE1E5; scrollbar-highlight-color: #FFFFFF; scrollbar-shadow-color: #DCE1E5; scrollbar-3dlight-color: #C7CFD7; scrollbar-arrow-color:  #006699; scrollbar-track-color: #ECECEC; scrollbar-darkshadow-color: #98AAB1; margin-top: 0; }
font,th,td { font-size: 11px; color: #323D4F; font-family: Verdana, Arial, Helvetica, sans-serif }
p { margin-bottom: 1.0em; line-height: 1.5em; font-size: 11px; }
ul { list-style: disc; margin: 0 0 1em 2em; }
a:link,a:active,a:visited { color: #006699 ;text-decoration: none; }
a:hover	{ text-decoration: underline; color: #DD6900; }
hr { border: 0 none; border-top: 1px solid #C7CFD7; margin-bottom: 5px; padding-bottom: 5px; height: 1px; }
.bodyline { background-color: #FFFFFF; border: 1px #98AAB1 solid; }
.copyright { font-size: 10px; color: #444444; letter-spacing: -1px; }
a.copyright { color: #444444; text-decoration: none; }
a.copyright:hover { color: #323D4F; text-decoration: underline; }
h1 { margin: 0; font: bold 1.8em "Lucida Grande", 'Trebuchet MS', Verdana, sans-serif; text-decoration: none; color: #323D4F; }
input, select { color: #323D4F; background-color: #FFFFFF; font: normal 11px Verdana, Arial, Helvetica, sans-serif; border-color: #323D4F; border-top-width : 1px; border-right-width : 1px; border-bottom-width : 1px; border-left-width : 1px; }
input { text-indent : 2px; }
input.mainoption { background-color: #FAFAFA; font-weight: bold; }
img, .forumline img { border: 0; }
.ok { color: #009900; }
.error { color: #D20000; }
#wrap { padding: 0 20px 0px 20px; min-width: 615px; }
#page-header { text-align: right; background: url("../templates/subSilver/images/logo_acp_phpBB.gif") 0 0 no-repeat; height: 84px; }
#page-header h1 { font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif; color: #323D4F; font-size: 1.5em; font-weight: normal; }
#page-header p { font-size: 1.1em; }
-->
</style>
</head>
<body topmargin="0" bgcolor="#FFFFFF" text="#323D4F" link="#006699" vlink="#5493B4">
<a name="top"></a>

<div id="wrap">
	<div id="page-header"><br />
		<h1>Fully Modded phpBB Smart Version Updater</h1>
		<p><i>The quick and intelligent way to update your board</i></p>
	</div>
</div>
<table align="center" width="98%" cellpadding="10" cellspacing="0">
<tr> 
	<td class="bodyline"><table width="100%" cellpadding="5" cellspacing="0">
  	<tr> 
  		<td>

<?php

//
// Begin program modes
//
if ($mode == 'phpbbfm')
{
	//
	// Version Information
	//
	echo "<h1>Version Information</h1>\n";
	echo "<p>Current build :: <b>" . $board_config['fm_version'] . "</b><br />\n";
	echo "Updating to &nbsp; :: <b>" . $updates_to_fm_version . "</b></p>\n";


	//
	// First disable the board ... hold tight, here we go!!
	//
	echo "<h1>Disabling the board</h1>\n";
	echo "<p>Result :: ";
	
	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = 1 WHERE `config_name` = 'board_disable'";
	_sql($sql, $errored, $error_ary, '');

	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	echo " <b class='ok'>Done</b></p>\n";

	
	//
	// Update the database to latest Fully Modded build, build by build
	//
	echo "<h1>Updating to current build</h1>\n";
	flush();
	
	switch ($board_config['fm_version'])
	{
		//
		// Tables in update files can be commented out if you currently have the EXACT SAME MOD version installed 
		// that Fully Modded uses, first make sure you know what your doing and have made a db backup!!
		//
		// Apply each update...
		// function(old_version, new_version, update_filename)
		//
		case 'Bootleg 3.1.0':
			_update_fm('Bootleg 3.1.0', '204.1.10', 03310);
		
		case '204.1.10':
			_update_fm('204.1.10', '204.1.60', 04110);
			
		case '204.1.60':
			_update_fm('204.1.60', '204.2.10', 04160);

		case '204.2.10':
			_update_fm('204.2.10', '204.2.50', 04210);
			
		case '204.2.50':
			_update_fm('204.2.50', '205.2.70', 04250);
		
		case '205.2.70':
			_update_fm('205.2.70', '206.3.0', 05270);
			
		case '206.3.0':
			_update_fm('206.3.0', '206.3.3', 06300);
			
		case '206.3.3':
		case '208.3.5':
			_update_fm('206.3.3/208.3.5', '209.3.5', 08350);
		
		case '209.3.5':
			_update_fm('209.3.5', '2010.3.5', 09350);

		case '2010.3.5':
			_update_fm('2010.3.5', '2010.3.6', 10350);
			
		case '2010.3.6':
			_update_fm('2010.3.6', '2010.3.65', 10360);
			
		case '2010.3.65':
			_update_fm('2010.3.65', '2011.3.65', 10365);
			
		case '2011.3.65':
		case '2012.3.65':
		case '2013.3.65':
		case '2013.3.58':
			_update_fm('2011.3.65/2012.3.65/2013.3.65/2013.3.58', '2014.3.58', 13358);

		case '2014.3.58':
			_update_fm('2014.3.58', '2015.3.58', 14358);

		case '2015.3.58':
		case '2016.3.58':
		case '2017.3.59':
			_update_fm('2015.3.58/2016.3.58/2017.3.59', '2017.4 RC-1', 17359);

		case '2017.4 RC-1':
			_update_fm('2017.4 RC-1', '2017.4 RC-2', '17400rc1');

		case '2017.4 RC-2':
			_update_fm('2017.4 RC-2', '2017.4 RC-3', '17400rc2');

		case '2017.4 RC-3':
			_update_fm('2017.4 RC-3', '2017.4.05', '17400rc3');

		case '2017.4.05':
			_update_fm('2017.4.05', '2018.4.11', 17405);

        case '2018.4.11':
			_update_fm('2018.4.11', '2019.4.20', 18411);

		case '2019.4.20':
			_update_fm('2019.4.20', '2020.4.35', 19420);

		case '2020.4.35':
			_update_fm('2020.4.35', '2021.4.40', 20435);

		case '2021.4.40':
			_update_fm('2021.4.40', 22447, 21440);

		case '22447':
			_update_fm(22447, 22470, 22447);

		case '22470':
			_update_fm(22470, 22473, 22470);

		case '22473':
			_update_fm(22473, 22483, 22473);

		case '22483':
			_update_fm(22483, 22488, 22483);

		case '22488':
			_update_fm(22488, 22489, 22488);
		
		case '22489':
			_update_fm(22489, 22505, 22489);
		
		case '22505':
			_update_fm(22505, 22507, 22505);

		case '22507':
			_update_fm(22507, 71122, 22507);
		
		case '71122':
			_update_fm(71122, 71212, 71122);

		case '71212':
			_update_fm(71212, 80220, 71212);
		
		case '80220':
			_update_fm(80220, 80316, 80220);

		case '80316':
			_update_fm(80316, 80421, 80316);

		case '80421':
			_update_fm(80421, 80510, 80421);
		
		case '80510':
		case '80708':
			_update_fm('80510/80708', 80923, 80708);
		
		case '80809':
		case '80923':
		case '81009':
			_update_fm('80809/80923/81009', 81016, 81009);

		case '81016':
			_update_fm('81016', 81104, 81016);

		case '81104':
			_update_fm('81104', $updates_to_fm_version, 81104);
			break;

		//
		// function(old_version, new_version, update_filename)
		//

		default:
			echo "<p>Result :: <b class=\"ok\">No updates required</b></p>\n";
			break;
	}
}
else if ($mode == 'phpbb')
{
	//
	// Version Information
	//
	echo '<h1>Version Information</h1>
	<p>';
	
	if ($board_config['fm_version'])
	{
		die("<p class='error'>A Fully Modded phpBB version has been detected.</p><br /><div align='center' class='copyright'>Powered by <a href='http://phpbb-fm.com' target='_blank' class='copyright'>" . $script_version . "</a> &copy; 2005, " . date('Y') . "</div>");
	}
	
	switch ($board_config['version'])
	{
		case '':
			echo 'Previous version :: <b>phpBB &lt; RC-3</b>';
			break;
		case 'RC-3':
			echo 'Previous version :: <b>phpBB RC-3</b>';
			break;
		case 'RC-4':
			echo 'Previous version :: <b>phpBB RC-4</b>';
			break;
		default:
			echo 'Previous version :: <b>phpBB 2' . $board_config['version'] . '</b>';
			break;
	}
	
	echo '<br />Updated version &nbsp;:: <b>Fully Modded phpBB ' . $updates_to_fm_version . '</b></p>' ."\n";


	//
	// First disable the board ... hold tight, here we go!!
	//
	echo "<h1>Disabling the board</h1>\n";
	echo "<p>Result :: <b>";
	
	$sql2 = 'UPDATE ' . $table_prefix . "config SET `config_value` = 1 WHERE `config_name` = 'board_disable'";
	_sql($sql2, $errored, $error_ary, '');
	
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	echo "</b> <b class='ok'>Done</b></p>\n";


	//
	// Schema updates
	// First update the core-schema to latest phpBB version
	//
	$sql = array();
	switch ($board_config['version'])
	{
		case '':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users DROP COLUMN `user_autologin_key`';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'ranks DROP COLUMN `rank_max`';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN `user_session_time` int(11) DEFAULT "0" NOT NULL, ADD COLUMN `user_session_page` smallint(5) DEFAULT "0" NOT NULL, ADD INDEX (user_session_time)';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'search_results MODIFY COLUMN `search_id` int(11) NOT NULL';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'topics MODIFY COLUMN `topic_moved_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, ADD COLUMN `topic_first_post_id` MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL, ADD INDEX (topic_first_post_id)';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'themes_name ADD COLUMN `tr_class1_name` VARCHAR(50) NULL, ADD COLUMN `tr_class2_name` VARCHAR(50) NULL, ADD COLUMN `tr_class3_name` VARCHAR(50) NULL, ADD COLUMN `th_class1_name` VARCHAR(50) NULL, ADD COLUMN `th_class2_name` VARCHAR(50) NULL, ADD COLUMN `th_class3_name` VARCHAR(50) NULL, ADD COLUMN `td_class1_name` VARCHAR(50) NULL, ADD COLUMN `td_class2_name` VARCHAR(50) NULL, ADD COLUMN `td_class3_name` VARCHAR(50) NULL, ADD COLUMN `span_class1_name` VARCHAR(50) NULL, ADD COLUMN `span_class2_name` VARCHAR(50) NULL, ADD COLUMN `span_class3_name` VARCHAR(50) NULL';

		case 'RC-3':
		case 'RC-4':
		case '.0.0':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `user_id` MEDIUMINT(8) NOT NULL, MODIFY COLUMN `user_timezone` decimal(5,2) DEFAULT "0" NOT NULL';

		case '.0.1':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'groups MODIFY COLUMN `group_id` MEDIUMINT(8) NOT NULL auto_increment';

		case '.0.3':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'search_wordmatch ADD INDEX post_id (post_id)';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users MODIFY COLUMN `user_timezone` decimal(5,2) DEFAULT "0" NOT NULL, MODIFY COLUMN `user_notify` tinyint(1) DEFAULT "0" NOT NULL';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'forum_prune MODIFY COLUMN `prune_days` smallint(5) UNSIGNED NOT NULL, MODIFY COLUMN `prune_freq` smallint(5) UNSIGNED NOT NULL';

		case '.0.4':
			$sql[] = 'CREATE TABLE ' . $table_prefix . 'confirm (`confirm_id` char(32) DEFAULT "" NOT NULL, `session_id` char(32) DEFAULT "" NOT NULL, `code` char(6) DEFAULT "" NOT NULL, PRIMARY KEY (session_id, confirm_id))';

		case '.0.5':
		case '.0.6':
		case '.0.7':
		case '.0.8':
		case '.0.9':
		case '.0.10':
		case '.0.11':
		case '.0.12':
		case '.0.13':
		case '.0.14':
			$sql[] = 'ALTER TABLE '. $table_prefix .'sessions ADD COLUMN session_admin tinyint(2) DEFAULT "0" NOT NULL';
		case '.0.15':
		case '.0.16':
		case '.0.17':
			$sql[] = 'CREATE TABLE ' . $table_prefix . 'sessions_keys (`key_id` varchar(32) DEFAULT "0" NOT NULL, `user_id` mediumint(8) DEFAULT "0" NOT NULL, `last_ip` varchar(8) DEFAULT "0" NOT NULL, `last_login` int(11) DEFAULT "0" NOT NULL, PRIMARY KEY (`key_id`, `user_id`), KEY last_login (`last_login`))';
		case '.0.18':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN user_login_tries smallint(5) UNSIGNED DEFAULT "0" NOT NULL';
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'users ADD COLUMN user_last_login_try int(11) DEFAULT "0" NOT NULL';
		case '.0.19':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'search_results ADD COLUMN search_time int(11) DEFAULT "0" NOT NULL';
		case '.0.21':
			$sql[] = 'ALTER TABLE ' . $table_prefix . 'search_results MODIFY COLUMN search_array MEDIUMTEXT NOT NULL';
		break;
	}

	//
	// Now thats updated, let's modify the phpBB core-schema for Fully Modded
	// We must alter one field at a time per table, to avoid sql die on a duplicate field, 
	// instead, msg out the error to the user and continue ...
	//
	include($phpbb_root_path . 'install/updates/phpbbs.'.$phpEx);
		
	//
	// Add new tables, dropping an existing table to avoid database/MOD inconsistancies with 
	// current MODs the user may have installed, god only knows what they have installed!!
	//
	// Tables in update file can be commented out if you currently have the same MOD version installed 
	// that Fully Modded uses, first make sure you know what your doing and have made a db backup!!
	//
	include($phpbb_root_path . 'install/updates/phpbbs2.'.$phpEx);

	echo "<h1>Updating database schema</h1>\n";
	echo "<p>Progress :: ";
	flush();
	
	$error_ary = array();
	$errored = false;

	for ($i = 0; $i < sizeof($sql); $i++)
	{
		_sql($sql[$i], $errored, $error_ary);
	}
	
	echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";

	if ($errored)
	{
		echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";

		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
			echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
		}

		echo "</ul>\n<p>This is probably nothing to worry about, update will continue.</p>\n";
	}
	else
	{
		echo "<b>No errors encounted</b></p>\n";
	}
	
	//
	// Data updates
	// First, update the core-data to latest phpBB version
	//
	unset($sql);
	$error_ary = array();
	$errored = false;

	echo "<h1>Updating data</h1>\n";
	echo "<p>Progress :: ";
	flush();

	switch ($board_config['version'])
	{
		case '':
			$sql = "SELECT themes_id FROM " . $table_prefix . "themes WHERE `template_name` = 'subSilver'";
			$result = _sql($sql, $errored, $error_ary);

			if ($row = $db->sql_fetchrow($result))
			{
				$theme_id = $row['themes_id'];
				$sql = 'UPDATE ' . $table_prefix . 'themes SET `head_stylesheet` = "subSilver.css", `body_background` = "", `body_bgcolor` = "E5E5E5", `body_text` = "323D4F", `body_link` = "006699", `body_vlink` = "5493B4", `body_alink` = "", `body_hlink` = "DD6900", `tr_color1` = "EFEFEF", `tr_color2` = "DEE3E7", `tr_color3` = "D1D7DC", `tr_class1` = "", `tr_class2` = "", `tr_class3` = "", `th_color1` = "98AAB1", `th_color2` = "006699", `th_color3` = "FFFFFF", `th_class1` = "cellpic1.gif", `th_class2` = "cellpic3.gif", `th_class3` = "cellpic2.jpg", `td_color1` = "FAFAFA", `td_color2` = "FFFFFF", `td_color3` = "", `td_class1` = "row1", `td_class2` = "row2", `td_class3` = "", `fontface1` = "Verdana, Helvetica, Arial, sans-serif", `fontface2` = "Trebuchet MS", `fontface3` = "Courier, \'Courier New\', sans-serif", `fontsize1` = "10", `fontsize2` = "11", `fontsize3` = "12", `fontcolor1` = "444444", `fontcolor2` = "006600", `fontcolor3` = "FFA34F", `span_class1` = "", `span_class2` = "", `span_class3` = "" WHERE `themes_id` = ' . $theme_id;
				_sql($sql, $errored, $error_ary);

				$sql = 'DELETE FROM ' . $table_prefix . 'themes_name WHERE `themes_id` = ' . $theme_id;
				_sql($sql, $errored, $error_ary);

				$sql = 'INSERT INTO ' . $table_prefix . 'themes_name (`themes_id`, `tr_color1_name`, `tr_color2_name`, `tr_color3_name`, `tr_class1_name`, `tr_class2_name`, `tr_class3_name`, `th_color1_name`, `th_color2_name`, `th_color3_name`, `th_class1_name`, `th_class2_name`, `th_class3_name`, `td_color1_name`, `td_color2_name`, `td_color3_name`, `td_class1_name`, `td_class2_name`, `td_class3_name`, `fontface1_name`, `fontface2_name`, `fontface3_name`, `fontsize1_name`, `fontsize2_name`, `fontsize3_name`, `fontcolor1_name`, `fontcolor2_name`, `fontcolor3_name`, `span_class1_name`, `span_class2_name`, `span_class3_name`) VALUES ("' . $theme_id . '", "The lightest row colour", "The medium row color", "The darkest row colour", "", "", "", "Border round the whole page", "Outer table border", "Inner table border", "Silver gradient picture", "Blue gradient picture", "Fade-out gradient on index", "Background for quote boxes", "All white areas", "", "Background for topic posts", "2nd background for topic posts", "", "Main fonts", "Additional topic title font", "Form fonts", "Smallest font size", "Medium font size", "Normal font size (post body etc)", "Quote & copyright text", "Code text colour", "Main table header text colour", "", "", "")';
				_sql($sql, $errored, $error_ary);
			}
			$db->sql_freeresult($result);

			$sql = 'SELECT MIN(post_id) AS first_post_id, topic_id FROM ' . $table_prefix . 'posts GROUP BY `topic_id` ORDER BY `topic_id` ASC';
			$result = _sql($sql, $errored, $error_ary);
	
			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					$sql = 'UPDATE ' . $table_prefix . 'topics SET `topic_first_post_id` = ' . $row['first_post_id'] . ' WHERE `topic_id` = ' . $row['topic_id'];
					_sql($sql, $errored, $error_ary);
				}
				while ($row = $db->sql_fetchrow($result));
			}
			$db->sql_freeresult($result);
	
			$sql = "SELECT DISTINCT u.user_id FROM " . $table_prefix . "users u, " . $table_prefix . "user_group ug, " . $table_prefix . "auth_access aa WHERE `aa.auth_mod` = 1 AND `ug.group_id` = aa.group_id AND `u.user_id` = ug.user_id AND `u.user_level` <> " . ADMIN;
			$result = _sql($sql, $errored, $error_ary);
	
			$mod_user = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$mod_user[] = $row['user_id'];
			}
			$db->sql_freeresult($result);
	
			if (sizeof($mod_user))
			{
				$sql = 'UPDATE ' . $table_prefix . 'users SET `user_level` = "' . MOD . '" WHERE `user_id` IN ("' . implode(', ', $mod_user) . '")';
				_sql($sql, $errored, $error_ary);
			}
	
			$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("server_name", "www.yourdomain.com")';
			_sql($sql, $errored, $error_ary);
			
			$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("script_path", "/phpBB2/")';
			_sql($sql, $errored, $error_ary);
			
			$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("server_port", "80")';
			_sql($sql, $errored, $error_ary);
			
			$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("record_online_users", "1")';
			_sql($sql, $errored, $error_ary);	
			
			$sql = 'INSERT INTO ' . $table_prefix . 'config (`config_name`, `config_value`) VALUES ("record_online_date", "' . time() . '")';
			_sql($sql, $errored, $error_ary);
	
		case 'RC-3':
		case 'RC-4':
		case '.0.0':
		case '.0.1':
			$sql = 'SELECT topic_id, topic_moved_id FROM ' . $table_prefix . 'topics WHERE `topic_moved_id` <> 0 AND `topic_status` = ' . TOPIC_MOVED; 
			$result = _sql($sql, $errored, $error_ary);
	
			$topic_ary = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$topic_ary[$row['topic_id']] = $row['topic_moved_id'];
			}
			$db->sql_freeresult($result);
	
			while (list($topic_id, $topic_moved_id) = each($topic_ary))
			{
				$sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts FROM " . $table_prefix . "posts WHERE `topic_id` = $topic_moved_id";
				$result = _sql($sql, $errored, $error_ary);
	
				$sql = ($row = $db->sql_fetchrow($result)) ? "UPDATE " . $table_prefix . "topics SET topic_replies = " . ($row['total_posts'] - 1) . ", topic_first_post_id = " . $row['first_post'] . ", topic_last_post_id = " . $row['last_post'] . " WHERE topic_id = $topic_id" : "DELETE FROM " . $table_prefix . "topics WHERE topic_id = " . $row['topic_id'];
				_sql($sql, $errored, $error_ary);
			}
	
			unset($sql);
	
			sync('all forums');
	
		case '.0.2':
		case '.0.3':
			// Topics will resync automatically
			// Remove stop words from search match and search words
			$dirname = 'language';
			$dir = @opendir($phpbb_root_path . $dirname);
	
			while ($file = @readdir($dir))
			{
				if (preg_match("#^lang_#i", $file) && !is_file($phpbb_root_path . $dirname . "/" . $file) && !is_link($phpbb_root_path . $dirname . "/" . $file) && file_exists($phpbb_root_path . $dirname . "/" . $file . '/search_stopwords.txt'))
				{
					$stopword_list = trim(preg_replace('#([\w\.\-_\+\'-\\\]+?)[ \n\r]*?(,|$)#', '\'\1\'\2', str_replace("'", "\'", implode(', ', file($phpbb_root_path . $dirname . "/" . $file . '/search_stopwords.txt')))));
					
					$sql = "SELECT word_id FROM " . $table_prefix . "search_wordlist WHERE `word_text` IN ($stopword_list)";
					$result = _sql($sql, $errored, $error_ary);
	
					$word_id_sql = '';
					if ($row = $db->sql_fetchrow($result))
					{
						do
						{
							$word_id_sql .= (($word_id_sql != '') ? ', ' : '') . $row['word_id'];
						}
						while ($row = $db->sql_fetchrow($result));
	
						$sql = "DELETE FROM " . $table_prefix . "search_wordlist WHERE `word_id` IN ($word_id_sql)";
						_sql($sql, $errored, $error_ary);
	
						$sql = "DELETE FROM " . $table_prefix . "search_wordmatch WHERE `word_id` IN ($word_id_sql)";
						_sql($sql, $errored, $error_ary);
					}
					$db->sql_freeresult($result);
				}
			}
			@closedir($dir);
	
			// Mark common words ...
			remove_common('global', 4/10);
	
			// Remove superfluous polls ... grab polls with topics then delete polls not in that list
			$sql = "SELECT v.vote_id  FROM " . $table_prefix . "topics t, " . $table_prefix . "vote_desc v WHERE `v.topic_id` = t.topic_id";
			$result = _sql($sql, $errored, $error_ary);	

			$vote_id_sql = '';
			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					$vote_id_sql .= (($vote_id_sql != '') ? ', ' : '') . $row['vote_id'];
				}
				while ($row = $db->sql_fetchrow($result));
	
				$sql = "DELETE FROM " . $table_prefix . "vote_desc WHERE `vote_id` NOT IN ($vote_id_sql)";
				_sql($sql, $errored, $error_ary);
	
				$sql = "DELETE FROM " . $table_prefix . "vote_results WHERE `vote_id` NOT IN ($vote_id_sql)";
				_sql($sql, $errored, $error_ary);
	
				$sql = "DELETE FROM " . $table_prefix . "vote_voters WHERE `vote_id` NOT IN ($vote_id_sql)";
				_sql($sql, $errored, $error_ary);
			}
			$db->sql_freeresult($result);
	
			// Update PM counters
			$sql = "SELECT privmsgs_to_userid, COUNT(privmsgs_id) AS unread_count FROM " . $table_prefix . "privmsgs WHERE `privmsgs_type` = " . PRIVMSGS_UNREAD_MAIL . " GROUP BY `privmsgs_to_userid`";
			$result = _sql($sql, $errored, $error_ary);
	
			if ($row = $db->sql_fetchrow($result))
			{
				$update_users = array();
				do
				{
					$update_users[$row['unread_count']][] = $row['privmsgs_to_userid'];
				}
				while ($row = $db->sql_fetchrow($result));
	
				while (list($num, $user_ary) = each($update_users))
				{
					$user_ids = implode(', ', $user_ary);
	
					$sql = "UPDATE " . $table_prefix . "users SET `user_unread_privmsg` = $num WHERE `user_id` IN ($user_ids)";
					_sql($sql, $errored, $error_ary);
				}
				unset($update_list);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT privmsgs_to_userid, COUNT(privmsgs_id) AS new_count FROM " . $table_prefix . "privmsgs WHERE `privmsgs_type` = " . PRIVMSGS_NEW_MAIL . " GROUP BY `privmsgs_to_userid`";
			$result = _sql($sql, $errored, $error_ary);
	
			if ($row = $db->sql_fetchrow($result))
			{
				$update_users = array();
				do
				{
					$update_users[$row['new_count']][] = $row['privmsgs_to_userid'];
				}
				while ($row = $db->sql_fetchrow($result));
	
				while (list($num, $user_ary) = each($update_users))
				{
					$user_ids = implode(', ', $user_ary);
	
					$sql = 'UPDATE ' . $table_prefix . 'users SET `user_new_privmsg` = ' . $num . ' WHERE `user_id` IN (' . $user_ids . ')';
					_sql($sql, $errored, $error_ary);
				}
				unset($update_list);	
			}
			$db->sql_freeresult($result);		

			// Remove superfluous watched topics	
			$sql = 'SELECT t.topic_id FROM ' . $table_prefix . 'topics t, ' . $table_prefix . 'topics_watch w WHERE `w.topic_id` = ' . t.topic_id;
			$result = _sql($sql, $errored, $error_ary);
	
			$topic_id_sql = '';
			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . $row['topic_id'];
				}
				while ($row = $db->sql_fetchrow($result));
	
				$sql = "DELETE FROM " . $table_prefix . "topics_watch WHERE `topic_id` NOT IN ($topic_id_sql)";
				_sql($sql, $errored, $error_ary);
			}
			$db->sql_freeresult($result);
	
			// Reset any email addresses which are non-compliant ... something not 
			// done in the upgrade script and thus which may affect some mysql users
			$sql = "UPDATE " . $table_prefix . "users SET `user_email` = '' WHERE `user_email` NOT REGEXP '^[a-zA-Z0-9_\+\.\-]+@.*[a-zA-Z0-9_\-]+\.[a-zA-Z]{2,}$'";
			_sql($sql, $errored, $error_ary);
	
		case '.0.4':
			// Add the confirmation code switch ... save time and trouble elsewhere
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('enable_confirm', '1')";
			_sql($sql, $errored, $error_ary);
	
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('sendmail_fix', '0')";
			_sql($sql, $errored, $error_ary);
	
		case '.0.5':
			$sql = "SELECT user_id, username FROM " . $table_prefix . "users";
			$result = _sql($sql, $errored, $error_ary);
	
			while ($row = $db->sql_fetchrow($result))
			{
				if (!preg_match('#(&gt;)|(&lt;)|(&quot)|(&amp;)#', $row['username']))
				{
					if ($row['username'] != htmlspecialchars($row['username']))
					{
						$sql = "UPDATE " . $table_prefix . "users SET `username` = '" . str_replace("'", "''", htmlspecialchars($row['username'])) . "' WHERE `user_id` = " . $row['user_id'];
						_sql($sql, $errored, $error_ary);
					}
				}
			}
			$db->sql_freeresult($result);

		case '.0.6':
		case '.0.7':
		case '.0.8':
		case '.0.9':
		case '.0.10':
		case '.0.11':
		case '.0.12':
		case '.0.13':
		case '.0.14':
			$sql = "UPDATE " . $table_prefix . "users SET user_allowhtml = 1 WHERE user_id = " . ANONYMOUS;
			_sql($sql, $errored, $error_ary);

			// We reset those having autologin enabled and forcing the re-assignment of a session id
			$sql = "DELETE FROM " . $table_prefix . "sessions";
			_sql($sql, $errored, $error_ary);

		case '.0.15':
		case '.0.16':
		case '.0.17':
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('allow_autologin', '1')";
			_sql($sql, $errored, $error_ary);

			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('max_autologin_time', '0')";
			_sql($sql, $errored, $error_ary);

			$sql = "UPDATE " . $table_prefix . "users SET user_active = 0 WHERE user_id = " . ANONYMOUS;
			_sql($sql, $errored, $error_ary);

		case '.0.18':
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('max_login_attempts', '5')";
			_sql($sql, $errored, $error_ary);

			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('login_reset_time', '30')";
			_sql($sql, $errored, $error_ary);

		case '.0.19':
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('search_flood_interval', '15')";
			_sql($sql, $errored, $error_ary);

			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('rand_seed', '0')";
			_sql($sql, $errored, $error_ary);

		case '.0.20':
			$sql = "INSERT INTO " . $table_prefix . "config (`config_name`, `config_value`) VALUES ('search_min_chars', '3')";
			_sql($sql, $errored, $error_ary);
		
			break;
	}
		
	//
	// Now the default core-data is updated, let's add the new core-data that is required for Fully Modded
	//
	include($phpbb_root_path . 'install/updates/phpbbd.'.$phpEx);

	echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";

	if ($errored)
	{
		echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";
	
		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
			echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
		}
	
		echo "</ul>\n<p>This is probably nothing to worry about, update will continue.</p>\n";
	}
	else
	{
		echo "<b>No errors encounted</b></p>\n";
	}
		
	//
	// Assign points to each user based on their current topics and posts
	// 
	// Default values: 2 Points per topic, 1 Point per post
	//
	unset($sql);
	$error_ary = array();
	$errored = false;

	echo "<h1>Assigning points to current users</h1>\n";
	echo "<p>Progress :: ";
	flush();

	$sql = "SELECT user_id FROM " . $table_prefix . "users WHERE `user_id` != " . ANONYMOUS;
	$result = _sql($sql, $errored, $error_ary, '');

	$users = $db->sql_fetchrowset($result);

	$total_users = sizeof($users);

	$b = 1;
	for($i = 0; $i < $total_users; $i++)
	{
		flush();

		$user_id = $users[$i]['user_id'];
	
		$sql = "SELECT COUNT(*) AS all_posts FROM " . $table_prefix . "posts WHERE `poster_id` = $user_id";
		$result = _sql($sql, $errored, $error_ary, '');
	
		$all_posts = $db->sql_fetchrow($result);
	
		$sql = "SELECT COUNT(*) AS total_topics FROM " . $table_prefix . "topics WHERE `topic_poster` = $user_id";
		$result = _sql($sql, $errored, $error_ary, '');

		$total_topics = $db->sql_fetchrow($result);

		$total_posts = $all_posts['all_posts'] - $total_topics['total_topics'];
		$total_topics = $total_topics['total_topics'];
		$points = 0;
		$points = $points + ($total_posts * 1); // 1 Point per post
		$points = $points + ($total_topics * 2); // 2 Points per topic

		$sql = "UPDATE " . $table_prefix . "users SET `user_points` = '$points' WHERE `user_id` = '$user_id'";
		_sql($sql, $errored, $error_ary);
		
		$b++;
	}
	$db->sql_freeresult($result);

	echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";

	if ($errored)
	{
		echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";
	
		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
			echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
		}
	
		echo "</ul>\n<p>This is probably nothing to worry about, update will continue.</p>\n";
	}
	else
	{
		echo "<b>No errors encounted</b></p>\n";
	}
}
else if ($mode == 'uninstall')
{
	//
	// Version Information
	//
	echo '<h1>Version Information</h1>
	<p>';
	
	switch ($board_config['fm_version'])
	{
		// Security check to catch phpBB users trying to uninstall, well, I dunno what !?!
		case '':
			die("<p class='error'>A Fully Modded phpBB version has not been detected.</p><br /><div align='center' class='copyright'>Powered by <a href='http://phpbb-fm.com' target='_blank' class='copyright'>" . $script_version . "</a> &copy; 2005, " . date('Y') . "</div>");
			break;
		default:
			echo 'Previous version :: <b>Fully Modded phpBB ' . $board_config['fm_version'] . '</b>';
			break;
	}
	
	echo '<br />Updated version &nbsp;:: <b>phpBB 2' . $updates_to_version . '</b></p>' ."\n";


	//
	// First disable the board ... hold tight, here we go!!
	//
	echo "<h1>Disabling the board</h1>\n";
	echo "<p>Result :: ";
	
	$sql = 'UPDATE ' . $table_prefix . 'config SET config_value = 1 WHERE `config_name` = "board_disable"';
	_sql($sql, $errored, $error_ary, '');

	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	echo " <b class='ok'>Done</b></p>\n";

	$sql = array();

	
	//
	// Schema downgrade
	// Remove the Fully Modded schema
	//
	include($phpbb_root_path . 'install/updates/phpbbrs.'.$phpEx);
	
	echo "<h1>Updating database schema</h1>\n";
	echo "<p>Progress :: ";
	flush();
	
	$error_ary = array();
	$errored = false;

	for ($i = 0; $i < sizeof($sql); $i++)
	{
		_sql($sql[$i], $errored, $error_ary);
	}
	
	echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";

	if ( $errored )
	{
		echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";

		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
			echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
		}

		echo "</ul>\n<p>This is probably nothing to worry about, update will continue.</p>\n";
	}
	else
	{
		echo "<b>No errors encounted</b></p>\n";
	}
	
	//
	// Data downgrade
	// Remove all Fully Modded data
	//
	unset($sql);
	$error_ary = array();
	$errored = false;

	echo "<h1>Updating data</h1>\n";
	echo "<p>Progress :: ";
	flush();

	include($phpbb_root_path . 'install/updates/phpbbrd.'.$phpEx);
	
	echo " <b class=\"ok\">Done</b><br />Result &nbsp; &nbsp; :: \n";

	if ( $errored )
	{
		echo " <b>Some queries failed, the statements and errors are listed below</b>\n<ul>";
	
		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo "<li>Error :: <b class='error'>" . $error_ary['error_code'][$i]['message'] . "</b><br />";
			echo "SQL &nbsp; :: <b>" . $error_ary['sql'][$i] . "</b><br /><br /></li>";
		}
	
		echo "</ul>\n<p>This is probably nothing to worry about, update will continue.</p>\n";
	}
	else
	{
		echo "<b>No errors encounted</b></p>\n";
	}
}
else
{	
	//
	// Default intro screen, info and how-to use
	//

	//
	// Site Information
	//
	if (!empty($board_config['fm_version'])) 
	{
		$build_title = 'Fully Modded Build';
		$current_build = $board_config['fm_version'];
	}
	else
	{
		$build_title = 'phpBB2 Version';
		$current_build = '2' . $board_config['version'];
	}

	// Check for gd version
	if (function_exists('gd_info')) 
	{
		$info = gd_info();
		$keys = array_keys($info);
	
		for ( $i=0; $i < 1; $i++ )
		{
			$gd_pass = TRUE;
			$gd_version = $info[$keys[$i]];
		}
	} 
	else 
	{
		$gd_pass = FALSE;
		$gd_version = 'n/a'; 
	}

	// Check for server version
	define('SAPI_NAME', php_sapi_name());
	if (preg_match('#(Apache)/([0-9\.]+)\s#siU', $_SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "$wsregs[1] v$wsregs[2]";
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else if (preg_match('#Microsoft-IIS/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "IIS v$wsregs[1]";
		$addsapi = true;
	}
	else if (preg_match('#Zeus/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "Zeus v$wsregs[1]";
		$addsapi = true;
	}
	else if (strtoupper($_SERVER['SERVER_SOFTWARE']) == 'APACHE')	
	{
		$server = 'Apache';
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else
	{
		$server = SAPI_NAME;
	}	
	
	if ($addsapi)
	{
		$server .= ' (' . SAPI_NAME . ')';
	}
	
	echo '<h1>Information</h1>
	<br />
	<table cellpadding="4" cellspacing="1">
	<tr>
		<td align="right">' . $build_title . ':</td>
		<td><b>' . $current_build . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td><b class="ok">PASSED</span></td>
	</tr>
	<tr>
		<td align="right">Register Globals:</td>
		<td><b>' . ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? '<span class="error">' . 'ON' . '</span>' : 'OFF') . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_' . ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'no' : 'yes') . '.png" alt="' . ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'FAILED' : 'PASSED') . '" title="' . ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'FAILED' : 'PASSED') . '" /></td>
		<td><b class="' . ((@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? 'error">FAILED' : 'ok">PASSED') . '</span></td>
	</tr>
	<tr>
		<td align="right">Safe Mode:</td>
		<td><b>' . ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? '<span class="error">' . 'ON' . '</span>' : 'OFF') . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_' . ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'no' : 'yes') . '.png" alt="' . ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'FAILED' : 'PASSED') . '" title="' . ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'FAILED' : 'PASSED') . '" /></td>
		<td><b class="' . ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? 'error">FAILED' : 'ok">PASSED') . '</span></td>
	</tr>
	<tr>
		<td align="right">Server:</td>
		<td><b>' . $server . ' (' . PHP_OS . ')</b></td>
		<td><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td><b class="ok">PASSED</span></td>
	</tr>
	</tr>
		<td align="right">Database:</td>
		<td width="200"><b>' . strtoupper(SQL_LAYER) . ' ' . mysql_get_server_info() . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td><b class="ok">PASSED</span></td>
	</tr>
	<tr>
		<td align="right">PHP version:</td>
		<td><b>' . @phpversion() . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_yes.png" alt="PASSED" title="PASSED" /></td>
		<td><b class="ok">PASSED</span></td>
	</tr>
	<tr>
		<td align="right">GD version:</td>
		<td><b>' . $gd_version . '</b></td>
		<td><img src="../templates/subSilver/images/ajax_' . (($gd_pass) ? 'yes' : 'no') . '.png" alt="' . (($gd_pass) ? 'PASSED' : 'FAILED') . '" title="' . (($gd_pass) ? 'PASSED' : 'FAILED') . '" /></td>
		<td><b class="' . (($gd_pass) ? 'ok">PASSED' : 'error">FAILED') . '</span></td>
	</tr>
	<tr>
		<td align="right">PHP memory limit:</td>
		<td colspan="3"><b>' . (( (@ini_get('memory_limit')) && (@ini_get('memory_limit') != -1) ) ? ini_get('memory_limit') : 'None') . '</b></td>
	</tr>
	</table>
	<hr />
	<h1>Options</h1>
	<br />
	<ul>';

	if ($board_config['fm_version'])
	{
		if ($board_config['fm_version'] != $updates_to_fm_version)
		{
			echo '<li><b>Update Fully Modded phpBB <i>' . $board_config['fm_version'] . '</i></b><br />This option allows you to update your current Fully Modded phpBB (<b>' . $board_config['fm_version'] . '</b>) to the latest (<b>' . $updates_to_fm_version . '</b>). <br />&nbsp;</li>';
		}
		
		echo '<li><b>Un-Install Fully Modded phpBB</b><br />This option allows you to remove Fully Modded phpBB completely from your database and revert back to the latest phpBB version (<b>2' . $updates_to_version . '</b>). All extra database tables and data installed by Fully Modded phpBB will be removed, leaving you with an original phpBB 2' . $updates_to_version . ' installation.<br />&nbsp;</li>';
	}
	else
	{
		echo '<li><b>Update phpBB to Fully Modded phpBB</b><br />From this screen you can update <u>any</u> existing phpBB version to the latest Fully Modded phpBB (<b>' . $updates_to_fm_version . '</b>) by clicking below.<br />&nbsp;</li>';
	}
	
	echo '</ul>
	<p><b class="error">Please make sure you have performed a full database backup <i>BEFORE</i> you execute any database changes from here.<br />Be aware that this script may take some time to update your database, so please be patient until it is finished.</b></p>
	<form action="' . basename(__FILE__) . '" method="post">
	<p><select name="mode">';
	
	if ($board_config['fm_version'])
	{
		if ($board_config['fm_version'] == $updates_to_fm_version)
		{
			$updates = '';
		}
		else
		{
			$updates = '<option value="phpbbfm">&nbsp;Update Fully Modded phpBB ' . $board_config['fm_version'] . '</option><option value="" disabled>------------------------------------------------</option>';
		}
		
		echo $updates . '<option value="uninstall">&nbsp;Un-Install Fully Modded phpBB</option>';
	}
	else
	{
		echo '<option value="phpbb">&nbsp;Update phpBB 2 to Fully Modded phpBB</option>';
	}
	
	echo '</select> &nbsp;<input type="submit" name="submit" value="Go" class="mainoption" /></p>
	</form>';
}

//
// Requirements for both update methods and un-install
// Version updates and database optimization
//
if ( $mode == 'phpbbfm' || $mode == 'phpbb' || $mode == 'uninstall')
{
	//
	// Optimize/vacuum analyze the tables where appropriate
	//
	echo "<h1>Optimizing database</h1>\n";
	echo "<p>Result :: ";
		
	$sql = "UPDATE " . $table_prefix . "posts_text SET `bbcode_uid` = '' WHERE `post_text` NOT LIKE '%[%]%[/%]%'";
	_sql($sql, $errored, $error_ary, '');

	$sql = "UPDATE " . $table_prefix . "users SET `user_sig_bbcode_uid` = '' WHERE `user_sig` NOT LIKE '%[%]%[/%]%'";
	_sql($sql, $errored, $error_ary, '');
	
	$sql = 'OPTIMIZE TABLE 
		' . $table_prefix . 'account_hist,
		' . $table_prefix . 'advance_html, 
		' . $table_prefix . 'album, 
		' . $table_prefix . 'album_cat, 
		' . $table_prefix . 'album_comment, 
		' . $table_prefix . 'album_config, 
		' . $table_prefix . 'album_rate, 
		' . $table_prefix . 'attachments, 
		' . $table_prefix . 'attachments_config, 
		' . $table_prefix . 'attachments_desc, 
		' . $table_prefix . 'attach_quota, 
		' . $table_prefix . 'auth_access, 
		' . $table_prefix . 'avatartoplist, 
		' . $table_prefix . 'backup, 
		' . $table_prefix . 'bank, 
		' . $table_prefix . 'banlist, 
		' . $table_prefix . 'banned_sites, 
		' . $table_prefix . 'banned_visitors, 
		' . $table_prefix . 'banner, 
		' . $table_prefix . 'banner_stats, 
		' . $table_prefix . 'banvote_voters, 
		' . $table_prefix . 'bookie_admin_bets, 
		' . $table_prefix . 'bookie_bets, 
		' . $table_prefix . 'bookie_bet_setter, 
		' . $table_prefix . 'bookie_categories, 
		' . $table_prefix . 'bookie_meetings, 
		' . $table_prefix . 'bookie_selections, 
		' . $table_prefix . 'bookie_selections_data, 
		' . $table_prefix . 'bookie_stats, 
		' . $table_prefix . 'bots, 
		' . $table_prefix . 'bots_archive, 
		' . $table_prefix . 'categories, 
		' . $table_prefix . 'cat_rel_cat_parents, 
		' . $table_prefix . 'cat_rel_forum_parents, 
		' . $table_prefix . 'charts, 
		' . $table_prefix . 'charts_voters, 
		' . $table_prefix . 'chatbox, 
		' . $table_prefix . 'chatbox_session, 
		' . $table_prefix . 'config, 
		' . $table_prefix . 'config_nav, 
		' . $table_prefix . 'confirm, 
		' . $table_prefix . 'digests, 
		' . $table_prefix . 'digests_config, 
		' . $table_prefix . 'digests_forums, 
		' . $table_prefix . 'digests_log, 
		' . $table_prefix . 'disallow, 
		' . $table_prefix . 'extensions, 
		' . $table_prefix . 'extension_groups, 
		' . $table_prefix . 'flags, 
		' . $table_prefix . 'forbidden_extensions, 
		' . $table_prefix . 'forums, 
		' . $table_prefix . 'forums_descrip, 
		' . $table_prefix . 'forums_watch, 
		' . $table_prefix . 'forum_move, 
		' . $table_prefix . 'forum_prune, 
		' . $table_prefix . 'forum_tour, 
		' . $table_prefix . 'groups, 
		' . $table_prefix . 'guestbook,
		' . $table_prefix . 'guestbook_config,
		' . $table_prefix . 'helpdesk_emails, 
		' . $table_prefix . 'helpdesk_importance, 
		' . $table_prefix . 'helpdesk_msgs, 
		' . $table_prefix . 'helpdesk_reply, 
		' . $table_prefix . 'im_buddy_list, 
		' . $table_prefix . 'im_config, 
		' . $table_prefix . 'im_prefs, 
		' . $table_prefix . 'im_sessions, 
		' . $table_prefix . 'im_sites, 
		' . $table_prefix . 'ina_ban, 
		' . $table_prefix . 'ina_categories, 
		' . $table_prefix . 'ina_challenge_tracker, 
		' . $table_prefix . 'ina_challenge_users, 
		' . $table_prefix . 'ina_chat, 
		' . $table_prefix . 'ina_cheat_fix, 
		' . $table_prefix . 'ina_data, 
		' . $table_prefix . 'ina_favorites, 
		' . $table_prefix . 'ina_gamble, 
		' . $table_prefix . 'ina_gamble_in_progress, 
		' . $table_prefix . 'ina_games, 
		' . $table_prefix . 'ina_hall_of_fame, 
		' . $table_prefix . 'ina_last_game_played, 
		' . $table_prefix . 'ina_rating_votes, 
		' . $table_prefix . 'ina_scores, 
		' . $table_prefix . 'ina_sessions, 
		' . $table_prefix . 'ina_top_scores, 
		' . $table_prefix . 'ina_trophy_comments, 
		' . $table_prefix . 'inline_ads, 
		' . $table_prefix . 'ip, 
		' . $table_prefix . 'jobs,
		' . $table_prefix . 'jobs_employed,
		' . $table_prefix . 'kb_articles, 
		' . $table_prefix . 'kb_categories, 
		' . $table_prefix . 'kb_results, 
		' . $table_prefix . 'kb_types, 
		' . $table_prefix . 'kb_wordlist, 
		' . $table_prefix . 'kb_wordmatch, 
		' . $table_prefix . 'lexicon, 
		' . $table_prefix . 'lexicon_cat, 
		' . $table_prefix . 'links, 
		' . $table_prefix . 'link_categories, 
		' . $table_prefix . 'link_comments, 
		' . $table_prefix . 'link_config, 
		' . $table_prefix . 'link_custom, 
		' . $table_prefix . 'link_customdata, 
		' . $table_prefix . 'link_votes, 
		' . $table_prefix . 'logs, 
		' . $table_prefix . 'lottery, 
		' . $table_prefix . 'lottery_history, 
		' . $table_prefix . 'medal, 
		' . $table_prefix . 'medal_cat, 
		' . $table_prefix . 'medal_mod, 
		' . $table_prefix . 'medal_user, 
		' . $table_prefix . 'meeting_comment, 
		' . $table_prefix . 'meeting_config, 
		' . $table_prefix . 'meeting_data, 
		' . $table_prefix . 'meeting_guestnames,
		' . $table_prefix . 'meeting_user, 
		' . $table_prefix . 'meeting_usergroup, 
		' . $table_prefix . 'modules, 
		' . $table_prefix . 'module_admin_panel, 
		' . $table_prefix . 'module_cache, 
		' . $table_prefix . 'module_group_auth, 
		' . $table_prefix . 'module_info, 
		' . $table_prefix . 'mycalendar, 
		' . $table_prefix . 'mycalendar_event_types, 
		' . $table_prefix . 'optimize_db, 
		' . $table_prefix . 'pages,
		' . $table_prefix . 'pa_auth, 
		' . $table_prefix . 'pa_cat, 
		' . $table_prefix . 'pa_comments, 
		' . $table_prefix . 'pa_config, 
		' . $table_prefix . 'pa_custom, 
		' . $table_prefix . 'pa_customdata, 
		' . $table_prefix . 'pa_download_info, 
		' . $table_prefix . 'pa_files, 
		' . $table_prefix . 'pa_license, 
		' . $table_prefix . 'pa_mirrors, 
		' . $table_prefix . 'pa_votes, 
		' . $table_prefix . 'pjirc, 
		' . $table_prefix . 'points_logger, 
		' . $table_prefix . 'portal, 
		' . $table_prefix . 'posts, 
		' . $table_prefix . 'posts_edit, 
		' . $table_prefix . 'posts_ignore_sigav, 
		' . $table_prefix . 'posts_text, 
		' . $table_prefix . 'privmsgs, 
		' . $table_prefix . 'privmsgs_archive, 
		' . $table_prefix . 'privmsgs_text, 
		' . $table_prefix . 'profile_view, 
		' . $table_prefix . 'quota_limits, 
		' . $table_prefix . 'ranks, 
		' . $table_prefix . 'rating, 
		' . $table_prefix . 'rating_bias, 
		' . $table_prefix . 'rating_config, 
		' . $table_prefix . 'rating_option, 
		' . $table_prefix . 'rating_rank, 
		' . $table_prefix . 'rating_temp, 
		' . $table_prefix . 'referers, 
		' . $table_prefix . 'referral, 
		' . $table_prefix . 'search_results, 
		' . $table_prefix . 'search_wordlist, 
		' . $table_prefix . 'search_wordmatch, 
		' . $table_prefix . 'serverload, 
		' . $table_prefix . 'sessions, 
		' . $table_prefix . 'sessions_keys, 
		' . $table_prefix . 'shops, 
		' . $table_prefix . 'shop_items, 
		' . $table_prefix . 'shop_transactions, 
		' . $table_prefix . 'shout, 
		' . $table_prefix . 'smilies, 
		' . $table_prefix . 'smilies_cat,
		' . $table_prefix . 'spelling_words, 
		' . $table_prefix . 'stats_smilies_index, 
		' . $table_prefix . 'stats_smilies_info, 
		' . $table_prefix . 'subscriptions_list, 
		' . $table_prefix . 'thanks, 
		' . $table_prefix . 'themes, 
		' . $table_prefix . 'themes_name, 
		' . $table_prefix . 'thread_kicker, 
		' . $table_prefix . 'title_infos, 
		' . $table_prefix . 'topics, 
		' . $table_prefix . 'topics_viewdata, 
		' . $table_prefix . 'topics_watch,
		' . $table_prefix . 'toplist, 
		' . $table_prefix . 'toplist_anti_flood, 
		' . $table_prefix . 'transactions, 
		' . $table_prefix . 'unique_hits, 
		' . $table_prefix . 'users, 
		' . $table_prefix . 'users_comments, 
		' . $table_prefix . 'user_group, 
		' . $table_prefix . 'user_notes, 
		' . $table_prefix . 'user_shops, 
		' . $table_prefix . 'user_shops_items, 
		' . $table_prefix . 'vote_desc, 
		' . $table_prefix . 'vote_results, 
		' . $table_prefix . 'vote_voters, 
		' . $table_prefix . 'words,
		' . $table_prefix . 'xdata_auth,
		' . $table_prefix . 'xdata_data,
		' . $table_prefix . 'xdata_fields
		';		

	_sql($sql, $errored, $error_ary, '');

	echo " <b class='ok'>Done</b></p>\n";

	//
	// Check file and directory permissions...
	// Attempt to set permissions and create any missing directories
	//
	if ($mode !== 'uninstall')
	{
		echo "<h1>Checking file permissions</h1>\n";
		echo "<p><span style='color:red'>Any files that are not writeable below must have permissions set manually</span>\n";
	
		// 0666
		$chmod_files = array(
			'cache/config_page_perms.'.$phpEx,
			'cache/reflog.txt',
			'cache/semaphore.ref',
			'language/lang_english/lang_bbcode.'.$phpEx,
			'language/lang_english/lang_faq.'.$phpEx,
			'language/lang_english/lang_faq_moderator.'.$phpEx
		);
	
		foreach ($chmod_files AS $dir)
		{
			if (!is_writeable($phpbb_root_path . $dir))
			{
				@chmod($phpbb_root_path . $dir, 0666);
			}
			echo '<li>' . $dir . ' &nbsp; <b class="' . (is_writeable($phpbb_root_path . $dir) ? 'ok">Writeable' : 'error">NOT WRITEABLE') . '</b></li>';
		}
			
		echo '</p>';
	
		echo "<h1>Checking directory permissions</h1>\n";
		echo "<p><span style='color:red'>Any directories that are not writeable below must have permissions set manually</span>\n";
	
		// 0777
		$chmod_dirs = array(
			'admin/backup/', 
			'cache/', 
			'cache/backup/',
			'cache/tpls/',
			'images/smiles/',
			'modules/',
			'modules/pakfiles/',
			'uploads/',
			'uploads/album/',
			'uploads/album/thumbs/',
			'uploads/attachments/',
			'uploads/attachments/thumbs/',
			'uploads/backups/',
			'uploads/pafiledb/',
			'uploads/pafiledb/screenshots/',
			'uploads/user_avatars/',
			'uploads/user_avatars/tmp/',
			'uploads/user_photos/'		
		);
		
		foreach ($chmod_dirs AS $dir)
		{
			$exists = $write = false;
	
			// Try to create the directory if it does not exist
			if (!file_exists($phpbb_root_path . $dir))
			{
				@mkdir($phpbb_root_path . $dir, 0777);
				@chmod($phpbb_root_path . $dir, 0777);
			}
	
			// Now really check
			if (file_exists($phpbb_root_path . $dir) && is_dir($phpbb_root_path . $dir))
			{
				if (!is_writeable($phpbb_root_path . $dir))
				{
					@chmod($phpbb_root_path . $dir, 0777);
				}
				$exists = true;
			}
	
			// Now check if it is writeable by storing a simple file
			$fp = @fopen($phpbb_root_path . $dir . 'test_lock', 'wb');
			if ($fp !== false)
			{
				$write = true;
			}
			@fclose($fp);
	
			@unlink($phpbb_root_path . $dir . 'test_lock');
	
			$exists = ($exists) ? '' : '<b class="error">NOT FOUND</b>, ';
			$write = ($write) ? '<b class="ok">Writeable</b>' : '<b class="error">NOT WRITEABLE</b>';
	
			echo '<li>' . $dir . ' - 0777 &nbsp; ' . $exists . $write . '</li>';
		}
	
		echo '</p>';
	}

	//
	// Update the Build and enable the board, database update is complete and we are all finished! ;)
	//
	echo "<h1>Enabling the board</h1>\n";
	echo "<p>Result :: ";

	$sql = "UPDATE " . $table_prefix . "config SET `config_value` = '$updates_to_version' WHERE `config_name` = 'version'";
	_sql($sql, $errored, $error_ary, '');
	
	if ( $mode != 'uninstall' )
	{
		$sql = "UPDATE " . $table_prefix . "config SET `config_value` = '$updates_to_fm_version' WHERE `config_name` = 'fm_version'";
		_sql($sql, $errored, $error_ary, '');
	}
	
	$sql = 'UPDATE ' . $table_prefix . "config SET `config_value` = 0 WHERE `config_name` = 'board_disable'";
	_sql($sql, $errored, $error_ary, '');

	//
	// We reset those having autologin enabled and forcing the re-assignment of a session id
	// since there have been changes to the way these are handled from previous builds
	//
	$sql = "DELETE FROM " . $table_prefix . "sessions";
	_sql($sql, $errored, $error_ary);
	
	$sql = "DELETE FROM " . $table_prefix . "sessions_keys";
	_sql($sql, $errored, $error_ary);

	//
	// Remove cache files
	//
	if ( $mode == 'phpbbfm')
	{
		// Clear template cache directory to remove any old templates
		if (!empty($board_config['xs_cache_dir']))
		{
			clear_directory($board_config['xs_cache_dir']);
		}
		
		// Delete other cached config files
		$cached_files = array('album', 'attach', 'digest', 'html', 'pafiledb', 'pjirc', 'prill');
		foreach ($cached_files AS $dir)
		{
			@unlink($phpbb_root_path . 'cache/config_' . $dir . '.'.$phpEx);
		}
	}
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	echo " <b class='ok'>Done</b></p>\n";
	
	echo "<h1>Update completed</h1>\n";
	echo "\n" . '<p style="color:red">Please make sure you have updated your board files too, this file has only updated your database.</p>';
	echo "\n<p>You should now visit your General Configuration settings page in the <a href=\"../admin/\">Administration Panel</a> and check the General Configuration of your board for any new or changed settings. Don't forget to <b>DELETE</b> the <u>install/</u> directory!</p>\n";
}

?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="1"><img src="../images/spacer.gif" height="1" alt="" title="" /></td>
	</tr>
	</table></td>
</tr>
</table>
<div align="center" class="copyright">
Powered by <a href='http://phpbb-fm.com/' target='_blank' class='copyright'><?php echo $script_version; ?></a> &copy; 2005, <?php echo date('Y'); ?>
</div>
</body>
</html>