<?php
/** 
*
* @package admin
* @version $Id: admin_logs_referers.php,v 0.2.0 11/03/2003, 22:04:58 NKieTo Exp $
* @copyright (c) 2002 NKieTo
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['HTTP_Referers_Title'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Check to see what mode we should operate in.
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

$sql = "SELECT config_value AS all_admin
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'all_admin'";
if(!$result = $db->sql_query($sql)) 
{ 
   message_die(CRITICAL_ERROR, "Could not query log config informations", "", __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);
$all_admin_authorized = $row['all_admin'];

if ( $all_admin_authorized == 0 && $userdata['user_id'] <> 2 && $userdata['user_view_log'] <> 1 )
{
	message_die(GENERAL_MESSAGE, $lang['Admin_not_authorized']);
}

//
// Select main mode
//
if( isset($HTTP_POST_VARS['delete']) || isset($HTTP_GET_VARS['delete']) )
{
	//
	// Delete all referers data 
	//
	$sql = "DELETE FROM " . REFERERS_TABLE;
	if (!$query = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete HTTP Referers.', '', __LINE__, __FILE__, $sql);
	}
	$message = $lang['referer_del_success'] . "<br /><br />" . sprintf($lang['Click_return_referersadmin'], "<a href=\"" . append_sid("admin_logs_referers.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);		
}

if (isset($HTTP_POST_VARS['config']))
{
	$config_value = ( isset($HTTP_POST_VARS['enable_http_referrers']) ) ? intval($HTTP_POST_VARS['enable_http_referrers']) : 0;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = $config_value
		WHERE config_name = 'enable_http_referrers'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Failed to update general configuration for admin_logs_referrers", "", __LINE__, __FILE__, $sql);
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_referersadmin'], "<a href=\"" . append_sid("admin_logs_referers.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

if ( $mode == 'delete')
{
	//
	// Delete a individual referer
	//		
	$referer_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];		
	$referer_host = ( !empty($HTTP_POST_VARS['host']) ) ? $HTTP_POST_VARS['host'] : $HTTP_GET_VARS['host'];

	$sql = "DELETE FROM " . REFERERS_TABLE . "
		WHERE " . ($referer_id ? "referer_id = $referer_id" : "referer_host = '$referer_host'");
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete HTTP referer.', '', __LINE__, __FILE__, $sql);
	}

	$message = $lang['referer_del_success'] . "<br /><br />" . sprintf($lang['Click_return_referersadmin'], "<a href=\"" . append_sid("admin_logs_referers.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
	break;
}	
else
{
	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$start = (isset($HTTP_GET_VARS['start'])) ? intval($HTTP_GET_VARS['start']) : 0;

	if( isset($HTTP_POST_VARS['sort']) )
	{
		$sort_method = $HTTP_POST_VARS['sort'];
	}
	else if( isset($HTTP_GET_VARS['sort']) )
	{
		$sort_method = $HTTP_GET_VARS['sort'];
	}
	else
	{
		$sort_method = 'referer_host';
	}

	$rdns_ip_num = ( isset($HTTP_GET_VARS['rdns']) ) ? $HTTP_GET_VARS['rdns'] : "";

	if( isset($HTTP_POST_VARS['order']) )
	{
		$sort_order = $HTTP_POST_VARS['order'];
	}
	else if( isset($HTTP_GET_VARS['order']) )
	{
		$sort_order = $HTTP_GET_VARS['order'];
	}
	else
	{
		$sort_order = '';
	}

	$template->set_filenames(array(
		'body' => 'admin/logs_referers_body.tpl')
	);

	$template->assign_vars(array(
		'L_HTTP_REFERERS_TITLE' => $lang['HTTP_Referers_Title'],
		'L_HTTP_REFERERS_EXPLAIN' => $lang['HTTP_Referers_Explain'],
		'U_SHOW_URLS_ACTION' => append_sid("admin_logs_referers.$phpEx" . (($mode == 'showurls') ? '' : '?mode=showurls')),
		'L_DO_SHOW_URLS' => (($mode == 'showurls') ? $lang['Referer_urls_hide'] : $lang['Referer_urls_show']),
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'U_LIST_ACTION' => append_sid("admin_logs_referers.$phpEx"),
		'L_SORT' => $lang['Sort'],
		'L_ORDER' => $lang['Order'],
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
		'L_DELETE_ALL' => $lang['Delete_all'],
		'L_CONFIRM_DELETE_REFERER' => $lang['Confirm_delete_referer'],
		'L_CONFIRM_DELETE_REFERERS' => $lang['Confirm_delete_referers'],
		'L_SORT_DESCENDING' => $lang['Sort_Descending'],
		'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
		'L_REFERER' => $lang['Referer_http'],
		'L_REFERER_URL' => $lang['Referer_url'],
		'L_REFERER_IP' => $lang['Referer_ip'],
		'L_HITS' => $lang['Referer_hits'],
		'L_FIRSTVISIT' => $lang['Referer_firstvisit'],
		'L_LASTVISIT' => $lang['Referer_lastvisit'],
		'L_ENABLE_REFERERS' => $lang['Enable_referers'],
		'L_CONFIGURATION' => $lang['Configuration'],
		
		'ENABLE_REFERERS_YES' => ( $board_config['enable_http_referrers'] ) ? 'checked="checked"' : '',
		'ENABLE_REFERERS_NO' => ( !$board_config['enable_http_referrers'] ) ? 'checked="checked"' : '', 
		'U_CONFIG_ACTION' => append_sid('admin_logs_referers.'.$phpEx),
	
		'REFERER_SELECTED' => ($sort_method == 'referer_host') ? 'selected="selected"' : '',
		'HITS_SELECTED' => ($sort_method == 'referer_hits') ? 'selected="selected"' : '',
		'FIRSTVISIT_SELECTED' => ($sort_method == 'referer_firstvisit') ? 'selected="selected"' : '',
		'LASTVISIT_SELECTED' => ($sort_method == 'referer_lastvisit') ? 'selected="selected"' : '',
		'ASC_SELECTED' => ($sort_order != 'DESC') ? 'selected="selected"' : '',
		'DESC_SELECTED' => ($sort_order == 'DESC') ? 'selected="selected"' : '')	
	);

	if ( $mode == 'showurls' )
	{
		$template->assign_block_vars('switch_show_ref_urls', array());
		
		// Count referers
		$sql = "SELECT COUNT(*) AS count 
			FROM " . REFERERS_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query referers table", '', __LINE__, __FILE__, $sql);
		}
		$total_referers = $db->sql_fetchfield("count", 0, $result);

		// Query referer info...
		$sql = "SELECT * FROM " . REFERERS_TABLE . " 
			ORDER BY " . $sort_method . " " . $sort_order . " 
			LIMIT " . $start . "," . $board_config['topics_per_page'];
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query referers table", '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$template->assign_block_vars('switch_dont_show_ref_urls', array());
		
		// Count referers
		$sql = "SELECT COUNT(DISTINCT referer_host) AS count 
			FROM " . REFERERS_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query referers table", '', __LINE__, __FILE__, $sql);
		}
		$total_referers = $db->sql_fetchfield("count", 0, $result);

		// Query referer info...
		$sql = "SELECT DISTINCT referer_host, SUM(referer_hits) AS referer_hits, MIN(referer_firstvisit) AS referer_firstvisit, MAX(referer_lastvisit) AS referer_lastvisit 
			FROM " . REFERERS_TABLE . " 
			GROUP BY referer_host 
			ORDER BY " . $sort_method . " " . $sort_order . " 
			LIMIT " . $start . "," . $board_config['topics_per_page'];
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain HTTP referrers', '', __LINE__, __FILE__, $sql);
		}
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$refererrow[] = $row;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < $board_config['topics_per_page']; $i++)
	{
		if (empty($refererrow[$i]))
		{
			break;
		}

		$firstvisit = create_date($board_config['default_dateformat'], $refererrow[$i]['referer_firstvisit'], $board_config['board_timezone']);
		if ($refererrow[$i]['referer_lastvisit'] != 0) 
		{
			$lastvisit = create_date($board_config['default_dateformat'], $refererrow[$i]['referer_lastvisit'], $board_config['board_timezone']);
		}
		else 
		{
			$lastvisit = '';
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		if ( $mode == 'showurls' )
		{
			$l_ip = $refererrow[$i]['referer_ip'];
			if ( $l_ip == $rdns_ip_num )
			{
				$u_ip = append_sid("admin_logs_referers.$phpEx?mode=showurls");
				$l_ip = gethostbyaddr(decode_ip($l_ip));
			}
			else
			{
				$u_ip = append_sid("admin_logs_referers.$phpEx?mode=showurls&amp;rdns=$l_ip");
				$l_ip = decode_ip($l_ip);
			}
			
			$u_ip .= "&amp;sort=$sort_method&amp;order=$sort_order";
			$referer_url = substr($refererrow[$i]['referer_url'], strpos($refererrow[$i]['referer_url'], "/", strpos($refererrow[$i]['referer_url'], "//") + 2));
			$referer_url_title = '';
			
			if ( strlen($referer_url) > 48 )
			{
				$referer_url_title = ' title="' . $referer_url . '"';
				$referer_url = substr($referer_url, 0, 45) . '...';
			}
			
			$template->assign_block_vars('refererrow_with_ref_urls', array(
				'ROW_CLASS' => $row_class,
				'U_REFERER' => ($refererrow[$i]['referer_host'] ? "http://" . $refererrow[$i]['referer_host'] : ''),
				'REFERER' => ($refererrow[$i]['referer_host'] ? $refererrow[$i]['referer_host'] : '(empty)'),
				'U_URL' => htmlentities($refererrow[$i]['referer_url']),
				'URL' => htmlentities($referer_url),
				'URL_TITLE' => $referer_url_title,
				'U_IP' => $u_ip,
				'L_IP' => $l_ip,
				'HITS' => $refererrow[$i]['referer_hits'],
				'FIRSTVISIT' => $firstvisit,
				'LASTVISIT' => $lastvisit,
				'U_DELETE' => append_sid("admin_logs_referers.$phpEx?mode=delete&amp;id=" . $refererrow[$i]['referer_id']))
			);
		}
		else
		{
			$template->assign_block_vars('refererrow', array(
				'ROW_CLASS' => $row_class,
				'U_REFERER' => ($refererrow[$i]['referer_host'] ? "http://" . $refererrow[$i]['referer_host'] : ''),
				'REFERER' => ($refererrow[$i]['referer_host'] ? $refererrow[$i]['referer_host'] : '(empty)'),
				'HITS' => $refererrow[$i]['referer_hits'],
				'FIRSTVISIT' => $firstvisit,
				'LASTVISIT' => $lastvisit,
				'U_DELETE' => append_sid("admin_logs_referers.$phpEx?mode=delete&amp;host=" . $refererrow[$i]['referer_host']))
			);
		}
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid("admin_logs_referers.$phpEx?sort=$sort_method&amp;order=$sort_order" . (isset($mode) ? "&amp;mode=$mode" : "") . ($rdns_ip_num == "" ? "" : "&amp;rdns=$rdns_ip_num")), $total_referers, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_referers / $board_config['topics_per_page'] )))
	);

	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>