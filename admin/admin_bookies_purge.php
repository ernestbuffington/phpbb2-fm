<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_purge.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Purge_Old_Bets'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = "";
}

function bookie_sync()
{
	global $db;

	// clean the stats db
	$user_ids = array();
	$user_id_now = array();
	
	$sql = "DELETE 
		FROM " . BOOKIE_STATS_TABLE;
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql);
	}
	//
	// Now let's start from scratch and create some stats
	//
	$sql = "SELECT user_id 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE checked = 1";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$user_ids[] = $row['user_id'];
	}
	for ( $i=0; $i<count($user_ids); $i++ )
	{
		if ( $user_ids[$i] != '*' )
		{
			$needle = $user_ids[$i];
			$user_ids[$i] = '*';
			for ($j=0; $j < count($user_ids); $j++)
			{
				if ( $user_ids[$j] == $needle )
				{
					$user_ids[$j] = '*';
				}
			}
			$user_id_now[] = $needle;
		}
	}
	
	for ( $i=0; $i < count($user_id_now); $i++ )
	{
		$sql = "INSERT INTO " . BOOKIE_STATS_TABLE . " (user_id) 
			VALUES (" . $user_id_now[$i] . ")";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql);
		}
	}
	
	$sql = "SELECT * 
		FROM " . BOOKIE_BETS_TABLE . "
		WHERE checked = 1";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql); 
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$this_user = $row['user_id'];
		$winnings = $row['win_lose'];
		
		$sql_field = ( $winnings > 0 ) ? "total_win=total_win+$winnings, bets_placed=bets_placed+1, netpos=netpos+$winnings" : "total_lose=total_lose+" . ($winnings*-1) . ", bets_placed=bets_placed+1, netpos=netpos+$winnings";
		
		$sql_a = "UPDATE " . BOOKIE_STATS_TABLE . "
			SET $sql_field
			WHERE user_id = $this_user";
		if ( !$db->sql_query($sql_a) )
		{
			message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql_a);
		} 
	}
}

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_purge.tpl')
);

if ( isset($HTTP_POST_VARS['purging']) )
{
	$template->set_filenames(array( 
        'body' => 'admin/admin_bookie_confirm_body.tpl') 
	);

	if ( isset($HTTP_POST_VARS['purge1']) )
	{
		$mesage_title = $lang['bookie_purge1_title'];
		$confirm_action = append_sid("admin_bookies_purge.$phpEx?mode=one");
		$message_text = $lang['bookie_purge1_text'];
	}
	else if ( isset($HTTP_POST_VARS['purge2']) )
	{
		$mesage_title = $lang['bookie_purge2_title'];
		$confirm_action = append_sid("admin_bookies_purge.$phpEx?mode=two");
		$message_text = $lang['bookie_purge2_text'];
	}
	else if ( isset($HTTP_POST_VARS['purge_mon']) )
	{
		$mesage_title = $lang['bookie_purge_mon_title'];
		$confirm_action = append_sid("admin_bookies_purge.$phpEx?mode=mon");
		$message_text = $lang['bookie_purge_mon_text'];
	}
	else if ( isset($HTTP_POST_VARS['purge_all']) )
	{
		$mesage_title = $lang['bookie_purge_all_title'];
		$confirm_action = append_sid("admin_bookies_purge.$phpEx?mode=all");
		$message_text = $lang['bookie_purge_all_text'];
	}
	else
	{
		$mesage_title = $lang['bookie_purge_error'];
		$confirm_action = '';
		$message_text = $lang['bookie_purge_error'];
	}
		
	$template->assign_vars(array(
		'MESSAGE_TITLE' => $mesage_title,
		'S_CONFIRM_ACTION' => $confirm_action,
		'MESSAGE_TEXT' => $message_text)
	);
	
	include('./page_header_admin.'.$phpEx);
	
	$template->pparse('body');
		
	include('./page_footer_admin.'.$phpEx);
}

if ( $mode == 'one' && isset($HTTP_POST_VARS['confirm']) )
{
	$time_now = time();
	$comp_time = $time_now - 604800;
	
	$sql = "DELETE FROM " . BOOKIE_BETS_TABLE . " 
		WHERE time < '$comp_time' 
			AND checked = 1";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
	
	bookie_sync();
	
	message_die(GENERAL_MESSAGE, $lang['bookie_one_week'], '', '', '', '');
}

// Leave two weeks bets
if ( $mode == 'two' && isset($HTTP_POST_VARS['confirm']) )
{
	$time_now = time();
	$comp_time = $time_now - 1209600;
	
	$sql = "DELETE FROM " . BOOKIE_BETS_TABLE . " 
		WHERE time < '$comp_time'  
			AND checked = 1";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
	
	bookie_sync();
	
	message_die(GENERAL_MESSAGE, $lang['bookie_two_weeks'], '', '', '', '');
}

// Leave one months bets
if ( $mode == 'mon' && isset($HTTP_POST_VARS['confirm']) )
{
	$time_now = time();
	$comp_time = $time_now - 2678400;
	
	$sql = "DELETE FROM " . BOOKIE_BETS_TABLE . " 
		WHERE time < '$comp_time' 
			AND checked = 1";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
	
	bookie_sync();
	
	message_die(GENERAL_MESSAGE, $lang['bookie_one_month'], '', '', '', '');
}

// Delete all bets and reset
if ( $mode == 'all' && isset($HTTP_POST_VARS['confirm']) )
{
	$sql = "DELETE FROM " . BOOKIE_BETS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
	
	$sql = "DELETE FROM " . BOOKIE_STATS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
		
	$sql = " DELETE FROM " . BOOKIE_ADMIN_BETS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in deleting past bets', '', __LINE__, __FILE__, $sql); 
	}
	
	message_die(GENERAL_MESSAGE, $lang['bookie_reset'], '', '', '', '');
}

if ( isset($HTTP_POST_VARS['cancel']) )
{
	message_die(GENERAL_MESSAGE, $lang['bookie_canceled'], '', '', '', '');
}
	
// Set template Vars
$template->assign_vars(array(
	'ONEWEEKBUTTONTEXT' => $lang['bookie_oneweekbuttontext'],
	'TWOWEEKSBUTTONTEXT' => $lang['bookie_twoweeksbuttontext'],
	'MONTHBUTTONTEXT' => $lang['bookie_monthbuttontext'],
	'DELETEALL' => $lang['bookie_deleteallbuttontext'],
	'L_PICKTIME' => $lang['bookie_purge_picktime'],
	'PURGE_HEADER' => $lang['bookie_purge_header'],
	'PURGE_EXPLAIN' => $lang['bookie_purge_explain'],
	'BOOKIE_VERSION' => $board_config['bookie_version'])
);

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>