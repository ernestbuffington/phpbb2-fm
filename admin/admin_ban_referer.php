<?php
/** 
*
* @package admin
* @version $Id: admin_ban_referer.php,v 1.35.2.9 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Banning']['Referering_sites'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.'.$phpEx);

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
	$mode = '';
}


if ( $mode == 'delete' )
{
	$sql = "DELETE FROM " . BANNED_VISITORS;
 	$result = $db->sql_query($sql);
	if (!$result)
  	{
 		message_die(GENERAL_ERROR, 'Could not remove visitor data.', '',__LINE__, __FILE__, $sql);
  	}
  	else
	{
		$message = $lang['Ban_sites_visitor_delete'];
  	}
  	$message .= '<br /><br />' . sprintf($lang['Ban_sites_click_return'], '<a href="' . append_sid('admin_ban_referer.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}
else if ( $mode == 'remove' )
{
	if( isset($HTTP_POST_VARS['site']) || isset($HTTP_GET_VARS['site']) )
	{
		$site = ( isset($HTTP_POST_VARS['site']) ) ? $HTTP_POST_VARS['site'] : $HTTP_GET_VARS['site'];
		
		$sql = "DELETE FROM " . BANNED_SITES . " 
			WHERE site_id = " .$site;
 		$result = $db->sql_query($sql);
  		if ( !$result )
  		{
 			message_die(GENERAL_ERROR, 'Could not remove site data.', '',__LINE__, __FILE__, $sql);
  		}
  		else
		{
		  	$message = $lang['Ban_sites_site_delete'];
  		}		
	}
	else
	{
		message_die(GENERAL_ERROR, 'Invaild site_id.');
	}
	
	$message .= '<br /><br />' . sprintf($lang['Ban_sites_click_return'], '<a href="' . append_sid('admin_ban_referer.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}
else if ( $mode == 'add' )
{
	$site_reason = ( isset($HTTP_POST_VARS['site_reason']) ) ? trim($HTTP_POST_VARS['site_reason']) : '';
	$site_url = ( isset($HTTP_POST_VARS['site_url']) ) ? trim($HTTP_POST_VARS['site_url']) : '';
		
	if ($site_reason == '' || $site_url == '')
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty'] . '<br /><br />' . sprintf($lang['Ban_sites_click_return'], '<a href="' . append_sid('admin_ban_referer.'.$phpEx) . '">', '</a>'));
	}
		
	if (strlen($site_url) < 4)
	{
		message_die(GENERAL_MESSAGE, $lang['Ban_sites_error_chars'] . '<br /><br />' . sprintf($lang['Ban_sites_click_return'], '<a href="' . append_sid('admin_ban_referer.'.$phpEx) . '">', '</a>'));
	}
		
	$sql = "INSERT " . BANNED_SITES . " (site_url, reason)
		VALUES ('" . str_replace("\'", "''", $site_url) . "', '" . str_replace("\'", "''", $site_reason) . "')";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not insert referring site.', '', __LINE__, __FILE__, $sql);
	}
		
	$message = $lang['Ban_sites_site_added'] . '<br /><br />' . sprintf($lang['Ban_sites_click_return'], '<a href="' . append_sid('admin_ban_referer.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}

//
// Let's set the template
//
$template->set_filenames(array(
	'body' => 'admin/ban_referer_body.tpl')
);

//
// Lets get all the sites
//
$sql = "SELECT *
	FROM " . BANNED_SITES;
$result = mysql_query($sql);	
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain banned sites.', '', __LINE__, __FILE__, $sql);
}

// Prepare the info
$rows = $db->sql_fetchrowset($result);
$count = sizeof($rows);

// Loop through the info
for($i = 0; $i < $count; $i++)
{
	$site_id = $rows[$i]['site_id'];
	$site = $rows[$i]['site_url'];
	$reason = $rows[$i]['reason'];

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('row',array(
		'ROW_CLASS' => $row_class,
		'SITE_ID' => $site_id,
		'SITE_URL' => $site,
		'REASON' => $reason)
	);
}
 
//
// Lets get all the banned visitors
// 
$sql2 = "SELECT *
	FROM " . BANNED_VISITORS;
$result2 = mysql_query($sql2);	
if( !$result2 = $db->sql_query($sql2) )
{
	message_die(GENERAL_ERROR, 'Could not obtain banned visitors.', '', __LINE__, __FILE__, $sql2);
}

// Prepare the info
$rows2 = $db->sql_fetchrowset($result2);
$count2 = sizeof($rows2);

// Loop through the info
for($i = 0; $i < $count2; $i++)
{
	$refer = $rows2[$i]['refer'];
	$ip = $rows2[$i]['ip'];
	$ipowner = $rows2[$i]['ip_owner'];
	$browser = $rows2[$i]['browser'];
	$user = $rows2[$i]['user'];

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('row2',array(
		'ROW_CLASS' => $row_class,
		'REFER' => $refer,
		'IP' => $ip,
		'IPOWNER' => $ipowner,
		'BROWSER' => $browser,
		'USER' => $user)
	);
}
  
$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Ban_sites_title'],
	'L_PAGE_EXPLAIN' => sprintf($lang['Ban_sites_explain'], $count, $count2),
	
	'L_BANNED_SITES' => $lang['Ban_sites_banlist'],
	'L_BAN_SITE' => $lang['Ban_sites_add'],
	'L_SITE_URL' => $lang['DL_url'],
	'L_REASON' => $lang['Reason'],
	'L_BANNED_VISITORS' => $lang['Ban_sites_visitors'],
	'L_DELETE_ALL' => $lang['Ban_sites_delete_all'],
	'L_REFERER' => $lang['Referer_url'],
	'L_IP' => $lang['IP_Address'],
	'L_IP_OWNER' => $lang['Ban_sites_ipowner'],
	'L_BROWSER' => $lang['IP_logger_browser'],
	'L_USERNAME' => $lang['Username'],
		
	'DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

	'S_ACTION' => append_sid('admin_ban_referer.'.$phpEx.'?mode=add'),
	'LIST_DELETE' => append_sid('admin_ban_referer.'.$phpEx.'?mode=delete'),
	'LIST_REMOVE' => append_sid('admin_ban_referer.'.$phpEx.'?mode=remove'))
);
  
$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>