<?php
/** 
*
* @package includes
* @version $Id: functions_points.php,v 1.0.1 2003/12/08 17:13:00 Robbie Shields Exp $
* @copyright (c) 2002 Bulletin Board Mods
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

function get_username_from_id($user_id)
{
	global $db;

	$sql = "SELECT username
		FROM " . USERS_TABLE . "
		WHERE user_id = $user_id
			AND user_id != " . ANONYMOUS;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not get username from $user_id.", '', __LINE__, __FILE__, $sql);
	}
	$username = $db->sql_fetchrow($result);
	
	return $username['username'];
}

function get_userid_from_name($username)
{
	global $db;
	
//old	$username = str_replace("\'", "''", trim($username));
//new	$username = phpbb_clean_username($username);

	$sql = "SELECT user_id
		FROM " . USERS_TABLE . "
		WHERE username = '" . phpbb_clean_username($username) . "'
			AND user_id != " . ANONYMOUS;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not get user_id from $username.", '', __LINE__, __FILE__, $sql);
	}
	$user_id = $db->sql_fetchrow($result);
	
	return $user_id['user_id'];
}

function get_user_points($user_id)
{
	global $db;
	
	$sql = "SELECT user_points
		FROM " . USERS_TABLE . "
		WHERE user_id = $user_id";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not get user_points from $user_id.", '', __LINE__, __FILE__, $sql);
	}
	$points = $db->sql_fetchrow($result);
	
	return $points['user_points'];
}

function add_points($user_id, $amount)
{
	global $db;

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + $amount
		WHERE user_id = $user_id";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not update user's points", '', __LINE__, __FILE__, $sql);
	}
	
	return;
}

function subtract_points($user_id, $amount)
{
	global $db;

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - $amount
		WHERE user_id = $user_id";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not update user's points", '', __LINE__, __FILE__, $sql);
	}
	
	return;
}

function user_is_authed($user_id)
{
	global $db, $board_config;

	static $is_authed;

	if (!isset($is_authed))
	{
		$is_authed = false;

		$points_user_group_auth_ids = explode("\n", $board_config['points_user_group_auth_ids']);

		$valid_ids = array();

		foreach ($points_user_group_auth_ids as $id)
		{
			$id = intval(trim($id));

			if (!empty($id))
			{
				$valid_ids[] = $id;
			}

			$valid_ids_sql = implode(',', $valid_ids);

			$sql = "SELECT group_id
				FROM " . USER_GROUP_TABLE . "
				WHERE group_id IN ($valid_ids_sql)
					AND user_id = $user_id
					AND user_pending = 0";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$is_authed = true;
			}
		}
	}

	return $is_authed;
}

function admin_track($user_id, $method_function, $amount)
{
	global $db, $userdata;
		
	$sql = "SELECT username
		FROM " . USERS_TABLE . "
		WHERE user_id = '$user_id'"; 
    if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain username for log transaction.', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$person	= $row['username'];
			
	$sql2 = "INSERT INTO " . POINTS_LOGGER_TABLE . " (admin, person, add_sub, total, time)
		VALUES ('" . $userdata['username'] . "', '$person', '$method_function', '$amount', " . time() . ")"; 

    if( !($result = $db->sql_query($sql2)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert log for this transaction.', '', __LINE__, __FILE__, $sql2);
	}
	
	return;
}


//
// Lottery function
//
function duration($seconds)
{
	global $lang;

	if ($seconds > 86399)
	{
		$days = floor($seconds / 86400);
		$seconds = ($seconds - ($days * 86400));
		$string .= ( $days > 1 ) ? $days .' ' . $lang['lottery_days'] . ', ' : $days .' ' . $lang['lottery_day'] . ', ';
	}
	if ($seconds > 3599)
	{
		$hours = floor($seconds / 3600);
		if ( $seconds != 0 ) 
		{ 
			$string .= ( $hours > 1 ) ? $hours .' ' . $lang['lottery_hours'] . ', ' : $hours .' ' . $lang['lottery_hour'] . ', '; 
		}
		$seconds = ( $days > 0 ) ? 0 : ($seconds - ($hours * 3600));
	}
	if ($seconds > 59)
	{
		$minutes = floor($seconds / 60);
		if ( $seconds != 0 ) 
		{ 
			$string .= ( $minutes > 1) ? $minutes .' ' . $lang['lottery_minutes'] . ', ' : $minutes .' ' . $lang['lottery_minute'] . ', '; 
		}
		$seconds = ( $hours > 0 ) ? 0 : ($seconds - ($minutes * 60));
	}
	if ($seconds > 0)
	{
		$string .= ( $seconds > 1 ) ? $seconds . ' ' . $lang['lottery_seconds'] . ', ' : $seconds . ' ' . $lang['lottery_second'] . ', ';
	}

	$string = substr($string, 0, -2);
	
	return $string;
}

?>