<?php
/***************************************************************************
                              functions_digests.php
                              ---------------------
    begin                : Sat Oct 4 2003
    copyright            : (C) 2000 The phpBB Group
    email                : support@phpBB.com

    $Id: $

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
// 
// Select the digest text length
//
function tl_select($default, $select_name = 'digest_text_length')
{
	global $lang;

	if ( !isset($default) )
	{
		$default = 150;
	}
	$tl_select = '<select name="' . $select_name . '">';

	while( list($offset, $digest_text_length) = @each($lang['tl']) )
	{
		$selected = ( $offset == $default ) ? ' selected="selected"' : '';
		$tl_select .= '<option value="' . $offset . '"' . $selected . '>' . $digest_text_length . '</option>';
	}
	$tl_select .= '</select>';

	return $tl_select;
}

//
// Select the digest frequency
//
function df_select($default, $mode, $select_name = 'digest_frequency')
{
	global $digest_config;
	global $lang;

	$df_select = '<select name="' . $select_name . '">';

	while( list($offset, $digest_frequency) = @each($lang['df']) )
	{
		if (((($mode == 'admin') || ($mode == 'group')) && ($offset < 998)) || ((($offset == 1) && ($digest_config['allow_hours1'] == 1)) || (($offset == 2) && ($digest_config['allow_hours2'] == 1)) || (($offset == 4) && ($digest_config['allow_hours4'] == 1)) || (($offset == 6) && ($digest_config['allow_hours6'] == 1)) || (($offset == 8) && ($digest_config['allow_hours8'] == 1)) || (($offset == 12) && ($digest_config['allow_hours12'] == 1)) || (($offset == 24) && ($digest_config['allow_daily'] == 1)) || (($offset == 168) && ($digest_config['allow_weekly'] == 1)) || (($offset == 672) && ($digest_config['allow_monthly'] == 1))))
		{
			$selected = ($offset == $default) ? ' selected="selected"' : '';
			$df_select .= '<option value="' . $offset . '"' . $selected . '>' . $digest_frequency . '</option>';
		}
	}
	$df_select .= '</select>';

	return $df_select;
}

//
// Select a day of the week
//
function ds_select($default, $select_name = 'days')
{
	global $lang;

	$days = array(
		$lang['Sun'],
		$lang['Mon'],
		$lang['Tue'],
		$lang['Wed'],
		$lang['Thu'],
		$lang['Fri'],
		$lang['Sat']
	);

	$ds_select = '<select name="' . $select_name . '">';
	for ($i = 0; $i < sizeof($days); $i++)
	{
		$display = $days[$i];
		$selected = ( $i == $default ) ? ' selected="selected"' : '';
		$ds_select .= '<option value="' . $i . '"' . $selected . '>'. $display . '</option>';
	}
	$ds_select .= '</select>';

	return $ds_select;
}

function ug_select($default, $select_name = '')
{
	global $db, $lang;

	$sql = "SELECT g.*
		FROM " . GROUPS_TABLE . " g
		WHERE g.group_digest = 1
		ORDER BY g.group_name";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not get user group list", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) > 0)
	{
		$ug_select = '<select name="' . $select_name . '">';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$selected = ( $row['group_id'] == $default ) ? ' selected="selected"' : '';
			$ug_select .= '<option value="' . $row['group_id'] . '"' . $selected . '>' . $row['group_name'] . '</option>';
		}
		$ug_select .= '</select>';
	}
	else
	{
		$ug_select = $lang['Digest_no_groups'];
	}

	return $ug_select;
}

// Select user for log filter
function log_user_select($default, $select_name = 'user_select')
{
	global $db, $lang;

	$sql = "SELECT u.user_id, u.username, l.user_id
		FROM " . USERS_TABLE . " u, " . DIGEST_LOG_TABLE . " l
		WHERE u.user_id = l.user_id
		AND u.user_id <> -1
		GROUP BY u.username
		ORDER BY u.username";

	$result = $db->sql_query($sql);

	$user_select = '<select name="' . $select_name . '">';
	$user_select .= '<option value=0> ' . $lang['All_users'];
	while($row = mysql_fetch_array($result))
	{
		$selected = ($row['user_id'] == $default) ? ' selected="selected"' : '';
		$user_select .= '<option value="' . $row['user_id'] . '"' . $selected . '>' . $row['username'] . '</option>';
	}
	$user_select .= '</select>';

	return $user_select;
}

// Select status for log filter
function log_status_filter($default, $select_name = 'status_select')
{
	global $db, $lang;

	$sql = "SELECT l.log_status
		FROM " . DIGEST_LOG_TABLE . " l
		GROUP BY l.log_status
		ORDER BY l.log_status";

	$result = $db->sql_query($sql);

	$status_select = '<select name="' . $select_name . '">';
	$status_select .= '<option value=0> ' . $lang['All_status'];
	while($row = mysql_fetch_array($result))
	{
		$selected = ($row['log_status'] == $default) ? ' selected="selected"' : '';
		$status_select .= '<option value="' . $row['log_status'] . '"' . $selected . '>' . $lang['lm'][$row['log_status']] . '</option>';
	}
	$status_select .= '</select>';

	return $status_select;
}

function digest_date_format_select($default, $timezone, $select_name = 'digest_date_format') 
{ 
	global $board_config; 

	// Include any valid PHP date format strings here, in your preferred order 
	$date_formats = array( 
		'D d-M-Y \a\t H:i:s', 
		'D d M, Y \a\t g:i:s a', 
		'D d M, Y \a\t H:i:s',  
		'D M d, Y \a\t g:i:s a', 
		'D M d, Y \a\t H:i:s',  
		'D jS F Y \a\t g:i:s a', 
		'D jS F Y \a\t H:i:s', 
		'D F jS Y \a\t g:i:s a', 
		'D F jS Y \a\t H:i:s',  
		'D j/n/Y \a\t g:i:s a', 
		'D j/n/Y \a\t H:i:s',  
		'D n/j/Y \a\t g:i:s a', 
		'D n/j/Y \a\t H:i:s', 
		'D Y-m-d \a\t g:i:s a', 
		'D Y-m-d \a\t H:i:s' 
	); 

	if ( !isset($timezone) ) 
	{ 
		$timezone == $board_config['board_timezone']; 
	} 
	$now = time() + (3600 * $timezone); 

	$df_select = '<select name="' . $select_name . '">'; 
	for ($i = 0; $i < sizeof($date_formats); $i++) 
	{ 
		$format = $date_formats[$i]; 
		$display = date($format, $now); 
		$df_select .= '<option value="' . $format . '"'; 
		if (isset($default) && ($default == $format)) 
		{ 
			$df_select .= ' selected'; 
		} 
		$df_select .= '>' . $display . '</option>'; 
	} 
	$df_select .= '</select>'; 

	return $df_select; 
}

function get_frequency_name($frequency_input)
{
	global $lang;

	$digest_frequency_name = $lang['df'][$frequency_input];
	return $digest_frequency_name;
}

function yes_no($input)
{
	global $lang;

	switch($input)
	{
		case 0:
			$output = $lang['No'];
			break;
		case 1:
			$output = $lang['Yes'];
			break;
	}
	return $output;
}

function get_group_name($group_id)
{
	global $db;

	$sql = "SELECT g.group_name 
		FROM " . GROUPS_TABLE . " g 
		WHERE g.group_id = $group_id";

		$row = $db->sql_fetchrow($db->sql_query($sql));
		return $row['group_name'];
}

function digest_ban($user_id, $mode)
{
	global $db;
	
	if ($mode == 1)
	{
		$sql = "SELECT *
			FROM " . BANLIST_TABLE . "
			WHERE ban_id = $user_id";
	
		$result = $db->sql_query($sql);		
		$row = $db->sql_fetchrow($db->sql_query($sql));
		$user_id = $row['ban_userid'];
	}

	$sql = "SELECT user_id
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user_id"; 

	$result = $db->sql_query($sql);

	if ($result != 0)
	{
		$sql = "UPDATE " . DIGEST_TABLE . "
			SET 
			digest_activity = $mode
			WHERE user_id = $user_id";

			$result = $db->sql_query($sql);
	}
}

function update_log($logging, $log_run_type, $digest_type, $user_id, $digest_frequency, $group_id, $log_status, $digest_log_days, $log_posts)
{
	global $db;

	if ($logging == 1)
	{	
		$log_time = time();
		$sql = "INSERT INTO " . DIGEST_LOG_TABLE . "
			(log_time, run_type, digest_type, user_id, digest_frequency, group_id, log_status, log_posts)
			VALUES (" . $log_time . ", " . $log_run_type . ", " . $digest_type . ", " . $user_id . ", " . $digest_frequency . ", " . $group_id . ", " . $log_status . ", " . $log_posts . ")";

		if (!($db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query log table:', '', __LINE__, __FILE__, $sql);
		}
	}

	// Remove old log entries
	$log_days = time() - ($digest_log_days * 86400);

	$sql = "DELETE FROM " . DIGEST_LOG_TABLE . "
		WHERE log_time <= '$log_days'";

	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query log table:', '', __LINE__, __FILE__, $sql);
	}
}

function get_urgent_run($user_id, $forum_id, $allow_urgent)
{
	global $db;
	$urgent_status = FALSE;

	if ($allow_urgent == 1)
	{
		$sql = "SELECT *
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Could not query database for digest forum data');
		}

		while($row = $db->sql_fetchrow($result))
		{
			if (($row['forum_id'] == $forum_id) || ($row['forum_id'] == ALL_FORUMS))
			{
				$urgent_status = TRUE;
			}
		}
	}
	return $urgent_status;
}

function get_site_url()
{
	global $board_config;

	$script_path = (trim($board_config['script_path'])) ? preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path'])) . '/' : ''; 
	$server_name = trim($board_config['server_name']); 
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://'; 
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
	$siteURL = $server_name . $server_port . $script_path;
	$siteURL = str_replace('//', '/', $siteURL);
	$siteURL = $server_protocol . $siteURL;

	return $siteURL;
}

function get_header_style($phpbb_root_path, $theme_name)
{
	$fcontents = file ($phpbb_root_path . 'templates/' . $theme_name . '/overall_header.tpl');
	while (list ($line_num, $line) = each ($fcontents))
	{
		$start_line = (substr($line, 1, 10) == 'style type') ? $line_num : $start_line;
		$end_line = (substr($line, 1, 6) == '/style') ? $line_num : $end_line;
	}

	$fcontents = file ($phpbb_root_path . 'templates/' . $theme_name . '/overall_header.tpl');
	while (list ($line_num, $line) = each ($fcontents))
	{
		if (($line_num > $start_line) && ($line_num < $end_line))
		{
			$header_data .= htmlspecialchars ($line);
		}
	}

	return $header_data;
}

?>