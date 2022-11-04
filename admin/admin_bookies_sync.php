<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_sync.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
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
	$module['Bookmakers']['Sync_attachments'] = $filename;

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
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_sync.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

if ( isset($HTTP_POST_VARS['sync']) )
{
	//
	// clean the stats db
	//
	$user_ids=array();
	$user_id_now=array();
	$sql=" DELETE FROM " . BOOKIE_STATS_TABLE;
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql);
	}
	//
	// Now let's start from scratch and create some stats
	//
	$sql=" SELECT user_id FROM " . BOOKIE_BETS_TABLE . "
	WHERE checked=1
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$user_ids[]=$row['user_id'];
	}
	for ( $i=0; $i<count($user_ids); $i++ )
	{
		if ( $user_ids[$i] != '*' )
		{
			$needle=$user_ids[$i];
			$user_ids[$i]='*';
			for ( $j=0; $j<count($user_ids); $j++ )
			{
				if ( $user_ids[$j] == $needle )
				{
					$user_ids[$j] = '*';
				}
			}
			$user_id_now[]=$needle;
		}
	}
	for ( $i=0; $i<count($user_id_now); $i++ )
	{
		$sql=" INSERT INTO " . BOOKIE_STATS_TABLE . "
		(user_id) VALUES (" . $user_id_now[$i] . ")
		";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql);
		}
	}
	$sql=" SELECT * FROM " . BOOKIE_BETS_TABLE . "
	WHERE checked=1
	";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql); 
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$this_user=$row['user_id'];
		$winnings=$row['win_lose'];
		
		$sql_field = ( $winnings > 0 ) ? "total_win=total_win+$winnings, bets_placed=bets_placed+1, netpos=netpos+$winnings" : "total_lose=total_lose+" . ($winnings*-1) . ", bets_placed=bets_placed+1, netpos=netpos+$winnings";
		
		$sql_a=" UPDATE " . BOOKIE_STATS_TABLE . "
		SET $sql_field
		WHERE user_id=$this_user
		";
		if ( !$db->sql_query($sql_a) )
		{
			message_die(GENERAL_ERROR, 'Error synchronizing', '', __LINE__, __FILE__, $sql_a);
		} 
	}
	$message = $lang['bookie_sync_success'];
	message_die(GENERAL_MESSAGE, $message);
}
	
// Set template Vars
$template->assign_vars(array(
'SYNC_HEADER' => $lang['bookie_sync_header'],
'SYNC_EXPLAIN' => $lang['bookie_sync_explain'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>