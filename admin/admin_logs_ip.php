<?php
/** 
*
* @package admin
* @version $Id: admin_ip_logger.php,v 5.0.2 dwing Exp $
* @copyright (c) 2002 Dimitri Seitz
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users']['IP_Logger'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


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

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;
$log_list = ( isset($HTTP_POST_VARS['log_list']) ) ?  $HTTP_POST_VARS['log_list'] : array();
$delete = ( isset($HTTP_POST_VARS['delete']) ) ?  TRUE : FALSE ;

$ip = ( isset($HTTP_GET_VARS['ip']) ) ? trim($HTTP_GET_VARS['ip']) : '';
$host = ( isset($HTTP_GET_VARS['host']) ) ? trim($HTTP_GET_VARS['host']) : '';
$referrer = ( isset($HTTP_GET_VARS['referrer']) ) ? trim($HTTP_GET_VARS['referrer']) : '';
$forum = ( isset($HTTP_GET_VARS['forum']) ) ? trim($HTTP_GET_VARS['forum']) : '';
$browser = ( isset($HTTP_GET_VARS['browser']) ) ? trim($HTTP_GET_VARS['browser']) : '';
$user = ( isset($HTTP_GET_VARS['user']) ) ? trim($HTTP_GET_VARS['user']) : '';

//
// Delete all logged IPs
//
if( isset($HTTP_POST_VARS['delete_all']) || isset($HTTP_GET_VARS['delete_all']) )
{
	$sql = "DELETE FROM " . IP_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete all logged IPs.', '', __LINE__, __FILE__, $sql);
	}
	
	message_die(GENERAL_MESSAGE, $lang['IP_logger_delete_success'] . '<br /><br />' . sprintf($lang['IP_logger_click_return'], '<a href="' . append_sid('admin_logs_ip.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>'));
}


$log_list_sql = implode(', ', $log_list);

if ( $log_list_sql != '' )
{
	if ( $delete )
	{
		$sql = "DELETE 
			FROM " . IP_TABLE . " 
			WHERE id IN (" . $log_list_sql . ")";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete logged IPs', '', __LINE__, __FILE__, $sql);
		}
		else
		{
			$message = $lang['IP_logger_delete_success'] . '<br /><br />' . sprintf($lang['IP_logger_click_return'], '<a href=' . append_sid("admin_logs_ip.$phpEx") . '>', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// Let's get the DB data
//
$sql = "SELECT ip.*, u.user_level 
	FROM " . IP_TABLE . " ip
	LEFT JOIN " . USERS_TABLE . " AS u ON ip.username = u.username";

switch( $mode )
{
	case 'ip':
		$sql .= " WHERE ip.ip = '$ip'";
		break;
	case 'user':
		$sql .= " WHERE ip.username = '$user'";
		break;
	case 'host':
		$sql .= " WHERE ip.host = '$host'";
		break;
	case 'browser':
		$sql .= " WHERE ip.browser = '$browser'";
		break;
	case 'forum':
		$sql .= " WHERE ip.forum = '$forum'";
		break;
	case 'referrer':
		$sql .= " WHERE ip.referrer = '$referrer'";
		break;
	default:
		break;
}
$sql .= " LIMIT $start, " . $board_config['topics_per_page'];
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query logged IPs', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while($row = $db->sql_fetchrow($result))
{
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	$template->assign_block_vars('record_row', array(
		'L_SAME_REFERRER' => $lang['IP_logger_same_referrer'],
		'L_SHOW_REFERRER' => $lang['IP_logger_show_referrer'],

		'ROW_CLASS' => $row_class,
		'IP_ID' => $row['id'],
		'IP' => $row['ip'],
		'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
		'DATE' => create_date($board_config['default_dateformat'], $row['date'], $board_config['board_timezone']),
		'HOST' => $row['host'],
		'BROWSER' => $row['browser'],
		'FORUM' => $row['forum'],
		'REFERRER' => $row['referrer'],
			
		'U_IP' => append_sid('admin_logs_ip.'.$phpEx.'?mode=ip&amp;ip=' . $row['ip']),
		'U_USERNAME' => append_sid('admin_logs_ip.'.$phpEx.'?mode=user&amp;user=' . $row['username']),
		'U_HOST' => append_sid('admin_logs_ip.'.$phpEx.'?mode=host&amp;host=' . $row['host']),
		'U_BROWSER' => append_sid('admin_logs_ip.'.$phpEx.'?mode=browser&amp;browser=' . $row['browser']),
		'U_FORUM' => append_sid('admin_logs_ip.'.$phpEx.'?mode=forum&amp;forum=' . $row['forum']),
		'U_REFERRER' => append_sid('admin_logs_ip.'.$phpEx.'?mode=referrer&amp;referrer=' . $row['referrer']))
	);
	
	$i++;
}
$db->sql_freeresult($result);

if ( $board_config['topics_per_page'] > 10 )
{
	$sql = "SELECT COUNT(*) AS total
		FROM " . IP_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error getting total informations for ips', '', __LINE__, __FILE__, $sql); 
	}

	if ( $total = $db->sql_fetchrow($result) ) 
	{ 
		$total_records = $total['total']; 
	
		$pagination = generate_pagination("admin_logs_ip.$phpEx?mode=$mode", $total_records, $board_config['topics_per_page'], $start). '&nbsp;'; 
	} 
} 
else
{
	$pagination = '&nbsp;';
	$total_records = 10;
}

$template->assign_vars(array(
	'L_LOG_ACTIONS_TITLE' => $lang['IP_Logger'],
	'L_LOG_ACTION_EXPLAIN' => $lang['IP_logger_title_explain'] . ' ' . ((!$board_config['enable_ip_logger']) ? sprintf($lang['IP_logger_disabled'], '<a href="' . append_sid('admin_logs_config.'.$phpEx) . '">', '</a>') : ''),
	'L_IP' => $lang['IP_Address'],
	'L_USERNAME' => $lang['Username'],
	'L_DATE' => $lang['Date'],
	'L_BROWSER' => $lang['IP_logger_browser'],
	'L_URL' => $lang['URL_Link'],
	'L_DELETE_LOG' => $lang['Mark'],
	'L_DELETE_ALL' => $lang['Delete_all'],

	'S_MODE_ACTION' => append_sid('admin_logs_ip.'.$phpEx),

	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_records / $board_config['topics_per_page'] )), 	
	'TOTAL_LOGS' => $total_records . ' ' . $lang['Logger'] . 's',

	'L_GOTO_PAGE' => $lang['Goto_page'])
);

$template->set_filenames(array(
	'body' => 'admin/logs_ip_body.tpl')
);

$template->pparse('body');

include('page_footer_admin.'.$phpEx);

?>