<?php
/** 
*
* @package admin
* @version $Id: admin_ban.php,v 0.5.2 2003/03/01 01:23:00 sj26 Exp $
* @copyright (c) 2003 Samuel Cochran
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Banning']['Manage'] = $filename;

	return;
}

// 
// Used to find any array entries that are equal to the 
// value specified and delete them from the array
// 
function array_unset_value(&$array, $search_value)
{
	if( !is_array($array) )
	{
		return false;
	}
	
	$keys = array();
	
	foreach($array as $key => $value)
	{
		if( $value == $search_value )
		{
			$array[$key] = '';
			unset($array[$key]);
			$keys[] = $key;
		}
	}
	
	if( empty($keys) )
	{
		return true;
	}
	
	return $keys;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$template->set_filenames(array(
	'admin_ban_body' => 'admin/ban_list_body.tpl')
);

//
// Query variable setting
//
$mode = 'user';
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}

if( $mode != 'email' && $mode != 'ip' && $mode != 'user' )
{
	$mode = 'user';
}

$start = 0;
if( ( isset($HTTP_POST_VARS['start']) && intval($HTTP_POST_VARS['start']) ) || ( isset($HTTP_GET_VARS['start']) && intval($HTTP_GET_VARS['start']) ) )
{
	$start = ( isset($HTTP_POST_VARS['start']) && intval($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);
}

$order = '';
if( isset($HTTP_POST_VARS['order']) || isset($HTTP_GET_VARS['order']) )
{
	$order = ( isset($HTTP_POST_VARS['order']) ) ? $HTTP_POST_VARS['order'] : $HTTP_GET_VARS['order'];
}

if( $mode == 'user' && ( $order != 'username' && $order != 'user_email' && $order != 'user_posts' && $order != 'user_lastvisit' ) )
{
	$order = 'username';
}
else if( $mode == 'ip' )
{
	$order = 'ban_ip';
}
else if( $mode == 'email' )
{
	$order = 'ban_email';
}

$order_dir = 'ASC';
if( isset($HTTP_POST_VARS['dir']) || isset($HTTP_GET_VARS['dir']) )
{
	$order_dir = strtoupper(( isset($HTTP_POST_VARS['dir']) ? $HTTP_POST_VARS['dir'] : $HTTP_GET_VARS['dir'] ));
	
	if( $order_dir != 'ASC' && $order_dir != 'DESC' )
	{
		$order_dir = 'ASC';
	}
}

$ban_email = ( !empty($HTTP_POST_VARS['ban_email']) ) ? trim($HTTP_POST_VARS['ban_email']) : false;
$ban_ip = ( !empty($HTTP_POST_VARS['ban_ip']) ) ? trim($HTTP_POST_VARS['ban_ip']) : false;
$ban_users = ( !empty($HTTP_POST_VARS['ban_users']) && is_array($HTTP_POST_VARS['ban_users']) ) ? $HTTP_POST_VARS['ban_users'] : array();
$unban = ( !empty($HTTP_POST_VARS['unban']) && is_array($HTTP_POST_VARS['unban']) ? $HTTP_POST_VARS['unban'] : ( !empty($HTTP_GET_VARS['unban']) && is_array($HTTP_GET_VARS['unban']) ? $HTTP_POST_VARS['unban'] : array() ) );
$unban_list = ( !empty($HTTP_POST_VARS['unban_list']) ? $HTTP_POST_VARS['unban_list'] : ( !empty($HTTP_GET_VARS['unban_list']) ? $HTTP_GET_VARS['unban_list'] : '' ) );

$next_submit = ( !empty($HTTP_POST_VARS['next_submit']) ) || !empty($HTTP_GET_VARS['next_submit']) ? true : false;
$prev_submit = ( !empty($HTTP_POST_VARS['prev_submit']) ) || !empty($HTTP_GET_VARS['prev_submit']) ? true : false;
$unban_submit = ( !empty($HTTP_POST_VARS['unban_submit']) || !empty($HTTP_GET_VARS['unban_submit']) ) ? true : false;
$unban_all_submit = ( !empty($HTTP_POST_VARS['unban_all_submit']) || !empty($HTTP_GET_VARS['unban_all_submit']) ) ? true : false;
$export_submit = ( !empty($HTTP_POST_VARS['export_submit']) || !empty($HTTP_GET_VARS['export_submit']) ) ? true : false;
$export_all_submit = ( !empty($HTTP_POST_VARS['export_all_submit']) || !empty($HTTP_GET_VARS['export_all_submit']) ) ? true : false;
$start_download = ( !empty($HTTP_POST_VARS['start_download']) || !empty($HTTP_GET_VARS['start_download']) ) ? true : false;

$s_hidden_fields = $s_user_select = $kill_session_sql = $success_message = $in_sql = $pagnation = '';

//
// Start program
//

if( ( !empty($unban) && $unban_submit ) || $unban_all_submit )
{
	for( $i = 0; $i < sizeof($unban); $i++ )
	{
		if( intval($unban[$i]) )
		{
			$in_sql .= ( !empty($in_sql) ? ',' : '' ) . intval($unban[$i]);
		}
	}
	
	// Yellow & black card ban systems 
	$sql = "SELECT ban_userid 
		FROM " . BANLIST_TABLE . " 
	   	WHERE ban_id IN (" . $in_sql . ")"; 
	if ( !$result = $db->sql_query($sql) ) 
	{ 
	   message_die(GENERAL_ERROR, "Couldn't get user warnings information", '', __LINE__, __FILE__, $sql); 
	} 
	while ($user_id_list = $db->sql_fetchrow($result)) 
	{ 
	   $where_user_sql .= ( ( $where_user_sql != '' ) ? ', ' : '' ) . $user_id_list['ban_userid']; 
	} 
	
	$sql = "UPDATE " . USERS_TABLE . " 
	   SET user_warnings = 0 
	   WHERE user_id IN (" . $where_user_sql . ")"; 
	if ( !$db->sql_query($sql) ) 
	{ 
	     message_die(GENERAL_ERROR, "Couldn't update user warnings information", '', __LINE__, __FILE__, $sql); 
	}
	
	$sql = "DELETE FROM " . BANLIST_TABLE . " 
		" . ( $unban_submit ? "WHERE ban_id IN (" . $in_sql . ")" : "" );
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't delete banlist entries", "", __LINE__, __FILE__, $sql);
	}
	
	$success_message = $lang['Ban_delete_success'];
}

if( ( ( ( !empty($unban) || !empty($unban_list) ) && $export_submit ) || $export_all_submit ) && $mode != 'user' )
{
	if( empty($unban) )
	{
		$unban = explode(',', $unban_list);
	}
	
	if( empty($unban) )
	{
		message_die(GENERAL_ERROR, "Nothing to export!");
	}
	
	for( $i = 0; $i < count($unban); $i++ )
	{
		if( intval($unban[$i]) )
		{
			$in_sql .= ( !empty($in_sql) ? ',' : '' ) . intval($unban[$i]);
		}
	}
	
	if( $start_download )
	{
		$sql = "SELECT ban_$mode 
			FROM " . BANLIST_TABLE . " 
			WHERE ban_$mode <> '' 
				" . ( $export_submit ? "AND ban_id IN (" . $in_sql . ")" : "" );
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't query banlist entries for export", "", __LINE__, __FILE__, $sql);
		}
		
		header("Content-Type: text/x-delimtext; name=\"banlist.txt\"");
		header("Content-disposition: attachment; filename=banlist.txt");
		
		while( $row = $db->sql_fetchrow($result) )
		{
			echo ( $mode == 'ip' ? decode_ip($row['ban_ip'])  : $row['ban_' . $mode] ) . "\n";
		}
		
		exit;
	}
	
	$u_download = append_sid("admin_ban.$phpEx?mode=$mode" . ( $export_submit ? "&unban_list=$in_sql" : '' ) . "&export" . ( $export_all_submit ? '_all' : '' ) . "_submit=1&start_download=1");
	
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="2;url=' . $u_download . '">')
	);

	$success_message = sprintf($lang['Ban_export'], '<a href="' . $u_download . '">', '</a>');
}

switch($mode)
{
	case 'email':
		$sql = "SELECT COUNT(*) AS num_rows 
			FROM " . BANLIST_TABLE . " b 
			WHERE b.ban_email <> ''";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}
		$total = $db->sql_fetchrow($result);
		$total = $total['num_rows'];
		break;
		
	case 'ip':
		$sql = "SELECT COUNT(*) AS num_rows 
			FROM " . BANLIST_TABLE . " b 
			WHERE b.ban_ip <> ''";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}
		$total = $db->sql_fetchrow($result);
		$total = $total['num_rows'];
		break;
		
	default:
		$sql = "SELECT COUNT(*) AS num_rows 
			FROM " . BANLIST_TABLE . " b 
			WHERE b.ban_userid <> 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}
		$total = $db->sql_fetchrow($result);
		$total = $total['num_rows'];
		break;
}

if( $prev_submit )
{
	$start -= $board_config['posts_per_page'];
}
else if( $next_submit )
{
	$start += $board_config['posts_per_page'];
}

$start = ( $start < 0 ) ? 0 : $start;
$start = ( $start >= $total ) ? $total - 1 : $start; // $total starts at 1 while $start starts at 0 *hits head*

$total_pages = ceil($total / $board_config['posts_per_page']);
$on_page = floor($start / $board_config['posts_per_page']) + 1;
$start = ( $on_page - 1 ) * $board_config['posts_per_page'];

if ( $start < 0 ) { $start = 0; }

if ( $total_pages > 1 )
{
	$pagination = $lang['Goto_page'] . ': ';
	$pagination .= '<select name="start" onChange="unbanform.submit()">';
	
	for( $i = 0; $i < $total_pages; $i++ )
	{
		$pagination .= '<option value="' . ($i * $board_config['posts_per_page']) . '"' . ( $i + 1 == $on_page ? ' selected="selected"' : '' ) . '>' . ($i + 1) . '</option>';
	}
	$pagination .= '</select>';
	$pagination .= '<noscript> <input type="submit" value="' . $lang['Go'] . '" class="mainoption" /></noscript>';
	
	if ( $on_page > 1 )
	{
		$template->assign_block_vars('switch_prev', array());
	}

	if ( $on_page < $total_pages )
	{
		$template->assign_block_vars('switch_next', array());
	}
}

// .( !empty($ban_email) ? '&amp;ban_email='.$ban_email : '' ).( !empty($ban_ip) ? '&amp;ban_ip='.$ban_ip : '' ).( !empty($ban_users) ? '&amp;ban_users[]='.implode('&amp;ban_users[]=', $ban_users) : '' )
$click_return  = '<br /><br />' . sprintf($lang['Click_return_ban'], '<a href="' . append_sid('admin_ban.'.$phpEx.'?mode='.$mode.'&amp;start='.$start.'&amp;order='.$order.'&amp;dir='.$order_dir) . '">', '</a>');
$click_return .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

switch($mode)
{
	case 'email':
		if( $ban_email )
		{
			$sql = "SELECT * 
				FROM " . BANLIST_TABLE . "
				WHERE ban_email <> ''";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
			}
			
			while( $row = $db->sql_fetchrow($result) )
			{
				$current_banlist[$row['ban_email']] = $row;
			}
			$db->sql_freeresult($result);
			
			$ban_emails = explode("\n", $ban_email);
			
			for($i = 0; $i < count($ban_emails); $i++)
			{
				if( !preg_match('/^[a-z0-9\.\-_\+\*]+@[a-z0-9\-_\*]+\.([a-z0-9\-_\*]+\.)*?[a-z\*]+$/is', trim($ban_emails[$i])) )
				{
					message_die(GENERAL_ERROR, 'You have entered an invalid email address.' . $click_return, 'Invalid Email');
				}
				
				if( !empty($current_banlist[trim($ban_emails[$i])]) )
				{
					continue;
				}
				
				$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_email)
					VALUES ('" . str_replace("\\'", "''", trim($ban_emails[$i])) . "')";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert email address into ban table", "", __LINE__, __FILE__, $sql);
				}
			}
			
			$success_message .= ( !empty($success_message) ? '<br /><br />' : '' ) . $lang['Ban_emails_success'];
			
			break;
		}
		break;
		
	case 'ip':
		if( $ban_ip )
		{
			$ip_list = array();
			$ip_list_temp = explode("\n", $ban_ip);
			
			for($i = 0; $i < count($ip_list_temp); $i++)
			{
				if ( preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})[ ]*(?:\:[0-9]{1,5})?\-[ ]*([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})(?:\:[0-9]{1,5})?$/', trim($ip_list_temp[$i]), $ip_range_explode) )
				{
					//
					// Don't ask about all this, just don't ask ... !
					//
					$ip_1_counter = $ip_range_explode[1];
					$ip_1_end = $ip_range_explode[5];
					
					while ( $ip_1_counter <= $ip_1_end )
					{
						$ip_2_counter = ( $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[2] : 0;
						$ip_2_end = ( $ip_1_counter < $ip_1_end ) ? 254 : $ip_range_explode[6];
						
						if ( $ip_2_counter == 0 && $ip_2_end == 254 )
						{
							$ip_2_counter = 255;
							$ip_2_fragment = 255;
							
							$ip_list[] = encode_ip("$ip_1_counter.255.255.255");
						}
						
						while ( $ip_2_counter <= $ip_2_end )
						{
							$ip_3_counter = ( $ip_2_counter == $ip_range_explode[2] && $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[3] : 0;
							$ip_3_end = ( $ip_2_counter < $ip_2_end || $ip_1_counter < $ip_1_end ) ? 254 : $ip_range_explode[7];
							
							if ( $ip_3_counter == 0 && $ip_3_end == 254 )
							{
								$ip_3_counter = 255;
								$ip_3_fragment = 255;
								
								$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.255.255");
							}
							
							while ( $ip_3_counter <= $ip_3_end )
							{
								$ip_4_counter = ( $ip_3_counter == $ip_range_explode[3] && $ip_2_counter == $ip_range_explode[2] && $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[4] : 0;
								$ip_4_end = ( $ip_3_counter < $ip_3_end || $ip_2_counter < $ip_2_end ) ? 254 : $ip_range_explode[8];
								
								if ( $ip_4_counter == 0 && $ip_4_end == 254 )
								{
									$ip_4_counter = 255;
									$ip_4_fragment = 255;
									
									$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.$ip_3_counter.255");
								}
								
								while ( $ip_4_counter <= $ip_4_end )
								{
									$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.$ip_3_counter.$ip_4_counter");
									$ip_4_counter++;
								}
								$ip_3_counter++;
							}
							$ip_2_counter++;
						}
						$ip_1_counter++;
					}
				}
				else if ( preg_match('/^([0-9]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})(?:\:[0-9]{1,5})?$/', trim($ip_list_temp[$i])) )
				{
					$ip_list[] = encode_ip(str_replace('*', '255', trim($ip_list_temp[$i])));
				}
				else if ( preg_match('/^([\w\-_]\.?){2,}(?:\:[0-9]{1,5})?$/is', trim($ip_list_temp[$i])) )
				{
					$ip = gethostbynamel(trim($ip_list_temp[$i]));
					
					for($j = 0; $j < count($ip); $j++)
					{
						if ( !empty($ip[$j]) )
						{
							$ip_list[] = encode_ip($ip[$j]);
						}
					}
				}
			}
		}
		
		$sql = "SELECT * 
			FROM " . BANLIST_TABLE . "
			WHERE ban_ip <> ''";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$current_banlist[$row['ban_ip']] = $row;
		}
		$db->sql_freeresult($result);
		
		$success = false;
		
		for($i = 0; $i < sizeof($ip_list); $i++)
		{
			if ( empty($current_banlist[$ip_list[$i]]) )
			{
				if ( preg_match('/(ff\.)|(\.ff)/is', chunk_split($ip_list[$i], 2, '.')) )
				{
					$kill_ip_sql = "session_ip LIKE '" . str_replace('.', '', preg_replace('/(ff\.)|(\.ff)/is', '%', chunk_split($ip_list[$i], 2, "."))) . "'";
				}
				else
				{
					$kill_ip_sql = "session_ip = '" . $ip_list[$i] . "'";
				}
				
				$kill_session_sql .= ( ( $kill_session_sql != '' ) ? ' OR ' : '' ) . $kill_ip_sql;
				
				$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_ip)
					VALUES ('" . $ip_list[$i] . "')";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert ban_ip info into database", "", __LINE__, __FILE__, $sql);
				}
				
				$current_banlist[$ip_list[$i]] = $ip_list[$i];
				$success = true;
			}
		}
		
		if( $success )
		{
			$success_message .= ( !empty($success_message) ? '<br /><br />' : '' ) . $lang['Ban_ips_success'];
		}
		break;
		
	default:
		$sql = "SELECT * 
			FROM " . BANLIST_TABLE . "
			WHERE ban_userid <> 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}
		
		while( $row = $db->sql_fetchrow($result) )
		{
			$current_banlist[$row['ban_userid']] = $row;
		}
		$db->sql_freeresult($result);
		
		if( !empty($ban_users) )
		{
			for($i = 0; $i < count($ban_users); $i++)
			{
				if ( empty($current_banlist[$ban_users[$i]]) )
				{
					$kill_session_sql .= ( ( $kill_session_sql != '' ) ? ' OR ' : '' ) . "session_user_id = " . $ban_users[$i];
			
					$sql = "SELECT username 
						FROM " . USERS_TABLE . " 
						WHERE user_id = " . $ban_users[$i]; 
					if (!($result = $db->sql_query($sql))) 
					{ 
					        message_die(GENERAL_ERROR, "Couldn't not obtain user info", "", __LINE__, __FILE__, $sql); 
					} 
					$row = $db->sql_fetchrow($result); 
					$user_name = trim($row['username']); // for mssql compatibility 
		
					$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_userid, user_name, reason, baned_by, ban_time, ban_by_userid, ban_pub_reason_mode, ban_priv_reason, ban_pub_reason)
						VALUES (" . $ban_users[$i] . ", '" . addslashes($user_name) . "', 'NULL', '" . $userdata['username'] . "', " . time() . ", " . $userdata['user_id'] . ", 0, 'NULL', 'NULL')";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't insert ban_userid info into database", "", __LINE__, __FILE__, $sql);
					}
					
					// Yellow & black card ban systems 
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_warnings = " . $board_config['max_user_bancard'] . "
						WHERE user_id = " . $ban_users[$i];
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update user_warnings', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			
			$success_message .= ( !empty($success_message) ? '<br /><br />' : '' ) . $lang['Ban_users_success'];
			
			break;
		}
		
		$s_user_select .= '<select name="ban_users[]" multiple="yes" size="10">';
		
		$sql = "SELECT user_id, username 
			FROM " . USERS_TABLE . " 
			WHERE user_id != " . ANONYMOUS . " 
			ORDER BY username ASC";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain users for ban list", "", __LINE__, __FILE__, $sql);
		}
		while( $row = $db->sql_fetchrow($result) )
		{
			if( !isset($current_banlist[$row['user_id']]) )
			{
				$s_user_select .= '<option value="' . $row['user_id'] . '">' . $row['username'] . '</option>';
			}
		}
		
		$s_user_select .= '</select>';
		break;
}

//
// Now we'll delete all entries from the session table with any of the banned
// user or IP info just entered into the ban table ... this will force a session
// initialisation resulting in an instant ban
//
if ( $kill_session_sql != '' )
{
	$sql = "DELETE FROM " . SESSIONS_TABLE . "
		WHERE $kill_session_sql";
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't delete banned sessions from database", "", __LINE__, __FILE__, $sql);
	}
}

if( !empty($success_message) )
{
	message_die(GENERAL_MESSAGE, $success_message . $click_return);
}

switch($mode)
{
	case 'email':
		$sql = "SELECT b.ban_id, b.ban_email 
			FROM " . BANLIST_TABLE . " b 
			WHERE b.ban_email <> ''
			ORDER BY b.ban_email $order_dir 
			LIMIT $start, " . $board_config['posts_per_page'];
		break;
		
	case 'ip':
		$sql = "SELECT b.ban_id, b.ban_ip 
			FROM " . BANLIST_TABLE . " b 
			WHERE b.ban_ip <> ''
			ORDER BY b.ban_ip $order_dir 
			LIMIT $start, " . $board_config['posts_per_page'];
		break;
		
	default:
		$sql = "SELECT b.ban_id, u.user_id, u.username, u.user_level, u.user_email, u.user_posts, u.user_lastlogon 
			FROM " . BANLIST_TABLE . " b, " . USERS_TABLE . " u 
			WHERE b.ban_userid <> 0 
				AND u.user_id = b.ban_userid 
			ORDER BY u." . ( $order == 'user_email' || $order == 'user_posts' || $order == 'user_lastlogon' ? $order : 'username' ) . " $order_dir 
			LIMIT $start, " . $board_config['posts_per_page'];
		break;
}

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
}

if( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	
	do
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$checked = ( in_array($row['ban_id'], $unban) ? true : false );
		
		switch($mode)
		{
			case 'email':
				$template->assign_block_vars('ban_email_row', array(
					'ROW_CLASS' => $row_class,
					
					'EMAIL' => $row['ban_email'], 
					'BAN_ID' => $row['ban_id'],
					
					'S_UNBAN_CHECKED' => ( $checked ? ' checked="checked"' : '' ))
				);
				break;
			
			case 'ip':
				$template->assign_block_vars('ban_ip_row', array(
					'ROW_CLASS' => $row_class,
					
					'IP' => decode_ip($row['ban_ip']), 
					'BAN_ID' => $row['ban_id'],
					
					'S_UNBAN_CHECKED' => ( $checked ? ' checked="checked"' : '' ))
				);
				break;
			
			default:
				$template->assign_block_vars('ban_user_row', array(
					'ROW_CLASS' => $row_class,
					
					'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']), 
					'EMAIL' => $row['user_email'], 
					'POSTS' => $row['user_posts'], 
					'LAST_VISIT' => (!empty($row['user_lastlogon'])) ? create_date($board_config['default_dateformat'], $row['user_lastlogon'], $board_config['board_timezone']) : $lang['Never_last_logon'],
					'BAN_ID' => $row['ban_id'], 
					
					'S_UNBAN_CHECKED' => ( $checked ) ? ' checked="checked"' : '',
					
					'U_USER_PROFILE' => append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']))
				);
				break;
		}
		
		array_unset_value($unban, $row['ban_id']);
		
		$i++;
	}
	while( $row = $db->sql_fetchrow($result) );
	
	if( !empty($unban) && is_array($unban) )
	{
		foreach($unban as $ban_id)
		{
			$s_hidden_fields .= '<input type="hidden" name="unban[]" value="' . $ban_id . '" />';
		}
	}
}
else
{
	$template->assign_block_vars('ban_none_row', array(
		'MESSAGE' => sprintf($lang['None_banned'], $lang[ucfirst($mode).'s']))
	);
}

$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $SID . '" />';

include('./page_header_admin.'.$phpEx);

if( $mode != 'user' )
{
	$template->assign_block_vars('switch_export', array());
}

$template->assign_vars(array(
	'L_BANS' => $lang['Ban_Manage'],
	'L_BANS_EXPLAIN' => $lang['Bans_admin_explain'],
	'L_USER' => $lang['User'],
	'L_USERNAME' => $lang['Username'],
	'L_EMAIL' => $lang['Email'],
	'L_IP' => $lang['IP'],
	'L_POSTS' => $lang['Posts'],
	'L_LAST_VISIT' => $lang['Last_visit'],
	'L_ADD_BAN' => $lang['Add_ban'],
	'L_ADD_BAN_EXPLAIN' => $lang['Ban_' . $mode . '_line_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_GO' => $lang['Go'],
	'L_EXPORT' => $lang['Export'],
	'L_EXPORT_ALL' => $lang['Export_All'],
	'L_UNBAN' => $lang['Unban'],
	'L_UNBAN_ALL' => $lang['Unban_All'],
	'L_BAN_MODE' => $lang['Ban_mode'],
	'L_NEXT' => $lang['Next'],
	'L_PREV' => $lang['Prev'],
	
	'USERNAME_ORDER_IMG' => ( $order == 'username' ? ' <img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/' . strtolower($order_dir) . '.gif" align="baseline" alt="" title="" />' : '' ),
	'EMAIL_ORDER_IMG' => ( $order == 'user_email' || $order == 'ban_email' ? ' <img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/' . strtolower($order_dir) . '.gif" align="baseline" alt="" title="" />' : '' ),
	'POSTS_ORDER_IMG' => ( $order == 'user_posts' ? ' <img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/' . strtolower($order_dir) . '.gif" align="baseline" alt="" title="" />' : '' ),
	'LAST_VISIT_ORDER_IMG' => ( $order == 'user_lastvisit' ? ' <img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/' . strtolower($order_dir) . '.gif" align="baseline" alt="" title="" />' : '' ),
	'IP_ORDER_IMG' => ( $mode == 'ip' ? ' <img src="' . $phpbb_root_path . 'templates/' . $theme['template_name'] . '/images/' . strtolower($order_dir) . '.gif" align="baseline" alt="" title="" />' : '' ),
	
	'U_USERNAME_ORDER' => append_sid('admin_ban.'.$phpEx.'?mode=' . $mode . '&order=username&dir=' . ( $order == 'username' ? ( $order_dir == 'ASC' ? 'DESC' : 'ASC' ) : 'ASC') . '&start=' . $start),
	'U_EMAIL_ORDER' => append_sid('admin_ban.'.$phpEx.'?mode=' . $mode . '&order=' . ( $mode == 'user' ? 'user_email' : 'ban_email' ) . '&dir=' . ( $order == ( $mode == 'user' ? 'user_email' : 'ban_email' ) ? ( $order_dir == 'ASC' ? 'DESC' : 'ASC' ) : 'ASC') . '&start='.$start),
	'U_POSTS_ORDER' => append_sid('admin_ban.'.$phpEx.'?mode=' . $mode . '&order=user_posts&dir=' . ( $order == 'user_posts' ? ( $order_dir == 'ASC' ? 'DESC' : 'ASC' ) : 'ASC') . '&start=' . $start),
	'U_LAST_VISIT_ORDER' => append_sid('admin_ban.'.$phpEx.'?mode=' . $mode . '&order=user_lastvisit&dir=' . ( $order == 'user_lastvisit' ? ( $order_dir == 'ASC' ? 'DESC' : 'ASC' ) : 'ASC') . '&start=' . $start),
	'U_IP_ORDER' => append_sid('admin_ban.'.$phpEx.'?mode=' . $mode . '&order=ban_ip&dir=' . ( $order == 'ban_ip' ? ( $order_dir == 'ASC' ? 'DESC' : 'ASC' ) : 'ASC') . '&start=' . $start),
	
	'PAGE' => ( $total_pages > 1 ? sprintf($lang['Page_of'], $on_page, $total_pages) : '' ),
	'PAGINATION' => ( $total_pages > 1 ? $pagination : '' ),
	
	'S_ADD_BAN_ACTION' => append_sid('admin_ban.'.$phpEx.'?mode='.$mode.'&amp;start='.$start.'&amp;order='.$order.'&amp;dir='.$order_dir),
	'S_ADD_BAN_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="'.$SID.'" />',
	'S_USER_SELECT' => $s_user_select,

	'S_FORM_ACTION' => append_sid('admin_ban.'.$phpEx.'?mode='.$mode.'&amp;order='.$order.'&amp;dir='.$order_dir),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,

	'S_MODE_ACTION' => append_sid('admin_ban.'.$phpEx),
	'S_MODE_USERNAME_SELECTED' => ( $mode == 'user' ? ' selected="selected"' : '' ),
	'S_MODE_EMAIL_SELECTED' => ( $mode == 'email' ? ' selected="selected"' : '' ),
	'S_MODE_IP_SELECTED' => ( $mode == 'ip' ? ' selected="selected"' : '' ),
	'S_MODE_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $SID . '" />')
);

$template->assign_block_vars('switch_mode_' . $mode, array());

$template->pparse('admin_ban_body');

include('./page_footer_admin.'.$phpEx);

?>